<?php defined('BASEPATH') || exit('No direct script access allowed');

class Content extends Admin_Controller{

  protected $permissionCreate = 'Blog.Content.Create';
  protected $permissionDelete = 'Blog.Content.Delete';
  protected $permissionEdit   = 'Blog.Content.Edit';
  protected $permissionView   = 'Blog.Content.View';

    /**
     * Basic constructor. Calls the Admin_Controller's constructor, then sets
     * the toolbar title displayed on the admin/content/blog page.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();


        $this->load->model('blog/category_model');
        $this->load->model('blog/blog_model');
        $this->load->model('blog/comments_model');

        $this->lang->load('blog/blog');
        $this->lang->load('blog/category');

        $this->load->library('blog/Nested_set');

        $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');

        $this->load->model('roles/role_model');

        Assets::add_module_js('blog', 'blog.js');

        Template::set_block('sub_nav', 'content/_sub_nav');

    }

    /**
     * The default page for this context.
     *
     * @return void
     */
    public function index(){

      $this->authenticate($this->permissionView);
      $offset = $this->uri->segment(5);

      $where = array('blog_posts.deleted'=>0);

       $this->blog_model->select("id_post,title_post,enable_attach,enable_comments,slug_post,preview_image,blog_posts.created_on as created_on,email,display_name,photo_avatar,username");
       $this->blog_model->order_by('blog_posts.created_on','desc');
       $this->blog_model->join('users','blog_posts.created_by  = users.id','left');
       $this->blog_model->limit($this->limit, $offset)->where($where);
       $posts = $this->blog_model->find_all();

       $this->load->library('pagination');

       $this->pager['base_url']    = base_url()."admin/content/blog/index/";
       $this->pager['per_page']    = $this->limit;
       $this->pager['total_rows']  = $this->blog_model->where($where)->count_all();
       $this->pager['uri_segment'] = 5;

       $this->pagination->initialize($this->pager);

      Template::set('posts', $posts);
      Template::set('toolbar_title', lang("blog_manage"));
      Template::render();
    }

    /*
    **
     * Create a Blog object.
     *
     * @return void
     */
    public function create(){

        $this->authenticate($this->permissionCreate);

        Assets::add_js('js/editors/ckeditor/ckeditor.js');

        if (isset($_POST['save'])) {

            if ($insert_id = $this->save_blog()) {

              $data_insert = array('blog_post_id'=>$insert_id,'data'=>$_POST,'created_by'=>$this->current_user->id);

                Events::trigger('insert_post_blog',$data_insert);

                $blog = $this->blog_model->find($insert_id);

                $id_act = log_activity($this->auth->user_id(), '[blog_act_create_record] : ' . '<a href="blog/post/'.$blog->slug_post.'">'.$blog->title_post.'</a>', 'blog');
                $ids = $this->user_model->get_id_users_role('id',explode(",",$blog->roles_access));
                log_notify($ids, $id_act);

                $this->send_blog_email($ids,$blog);

                Template::set_message(lang('blog_create_success'), 'success');
                Template::redirect('blog/content/');

            }

            // Not validation error
            if ( ! empty($this->blog_model->error)) {
                Template::set_message(lang('blog_create_failure') . $this->blog_model->error, 'error');
            }
          }

            $this->load->library('blog/Nested_set');
            $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');
            $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
            $tree = $this->nested_set->getSubTree($parent_node);

            Template::set('tree', $tree);

        Template::set('roles', $this->role_model->where('deleted', 0)->find_all());
        Template::set('toolbar_title', lang('blog_action_create'));
        Template::set_block('sub_nav_menu', '_menu_module');
        Template::render();
    }
    /**
     * Allows editing of Blog data.
     *
     * @return void
     */
    public function edit($id)
    {

        if (empty($id)) {

            Template::set_message(lang('blog_invalid_id'), 'error');
            redirect('blog');
        }

        $this->authenticate($this->permissionEdit);

        Assets::add_js('js/editors/ckeditor/ckeditor.js');

        $post = $this->blog_model->find($id);

        if (isset($_POST['save'])) {

            if ($this->save_blog('update', $id)) {

              $data_insert = array('blog_id'=>$id,'categs_id'=>$this->input->post('category'));

                Events::trigger('insert_blog',$data_insert);

                log_activity($this->auth->user_id(), lang('blog_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'blog');
                Template::set_message(lang('blog_edit_success'), 'success');
                redirect('blog/content/');
            }

            // Not validation error
            if ( ! empty($this->blog_model->error)) {
                Template::set_message(lang('blog_edit_failure') . $this->blog_model->error, 'error');
            }
        }



        $this->load->library('blog/Nested_set');
        $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');
        $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
        $tree = $this->nested_set->getSubTree($parent_node);

        Template::set('tree', $tree);

        Template::set('post', $post);
        Template::set('roles', $this->role_model->where('deleted', 0)->find_all());
        Template::set('category', $this->category_model->get_blog_categories($id));
        Template::set('toolbar_title', lang('blog_edit_heading').' '.$post->title_post);
        Template::set_view('create');
        Template::set_block('sub_nav_menu', '_menu_module');
        Template::render();
    }

    public function delete($id){

      if (empty($id)) {

          Template::set_message(lang('blog_invalid_id'), 'error');
          redirect('blog/content/');
      }

        $this->authenticate($this->permissionDelete);

          if ($this->blog_model->delete($id)) {

              log_activity($this->auth->user_id(), '[blog_act_delete_record] : ' . $id . ' : ' . $this->input->ip_address(), 'blog');
              Template::set_message(lang('blog_delete_success'), 'success');
              Template::redirect($this->agent->referrer());

          }

          Template::set_message(lang('blog_delete_failure') . $this->blog_model->error, 'error');

    }

    public function categs(){

			$this->authenticate($this->permissionView);

      if (isset($_POST['delete'])) {
          $checked = $this->input->post('checked');
          if (empty($checked)) {
              // No users checked.
              Template::set_message(lang('us_empty_id'), 'error');
          } else {
              foreach ($checked as $userId) {
                  $this->delete_category($userId);
              }
          }
      }


      $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
      $tree = $this->nested_set->getSubTree($parent_node);

      Template::set('tree', $tree);

      Template::set('toolbar_title', lang('category_list'));
      Template::set_block('sub_nav_menu', '_menu_module');
      Template::render();

    }


    /*
    **
     * Create a Category object.
     *
     * @return void
     */
    public function create_category(){

        $this->authenticate($this->permissionCreate);

        if (isset($_POST['save'])) {

            if ($insert_id = $this->save_category()) {
                log_activity($this->auth->user_id(), lang('category_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'category');
                Template::set_message(lang('category_create_success'), 'success');

                redirect('blog/content/categs');
            }

            // Not validation error
            if ( ! empty($this->category_model->error)) {
                Template::set_message(lang('category_create_failure') . $this->category_model->error, 'error');
            }
        }

        Template::set('categories',$this->category_model->find_all());
        Template::set('toolbar_title', lang('category_action_create'));

        $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
        $tree = $this->nested_set->getSubTree($parent_node);

        Template::set('tree', $tree);


        Template::set_block('sub_nav_menu', '_menu_module');
        Template::render();
    }
    /**
     * Allows editing of Category data.
     *
     * @return void
     */
    public function edit_category()
    {
        $id = $this->uri->segment(4);
        if (empty($id)) {
            Template::set_message(lang('category_invalid_id'), 'error');

            redirect('category');
        }

        $this->authenticate($this->permissionCreate);


          $id = $this->category_model->find_by('slug_category',$id)->id_category;

        if (isset($_POST['save'])) {

            if ($this->save_category('update', $id)) {
                log_activity($this->auth->user_id(), lang('category_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'category');
                Template::set_message(lang('category_edit_success'), 'success');
                redirect('blog/content/categs');
            }

            // Not validation error
            if ( ! empty($this->category_model->error)) {
                Template::set_message(lang('category_edit_failure') . $this->category_model->error, 'error');
            }
        }

        $category = $this->category_model->find($id);

        Template::set('category', $category);

        $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
        $this->db->where('id_category <>',$category->id_category);
        $tree = $this->nested_set->getSubTree($parent_node);

        Template::set('tree', $tree);

        Template::set('toolbar_title', lang('category_edit_heading'));
        Template::set_view('create_category');
        Template::set_block('sub_nav_menu', '_menu_module');
        Template::render();



    }

    /**
     * The default page for this context.
     *
     * @return void
     */
    public function comments(){

      $this->authenticate($this->permissionView);
      $offset = $this->uri->segment(4);

      $where = array('blog_comments.deleted'=>0,'blog_posts.deleted'=>0);

       $this->comments_model->select("blog_comments.id as id,content,file_url,approved,file_mime_type,creator,created,id_post,title_post,slug_post,preview_image,email,display_name,photo_avatar,username");
       $this->comments_model->order_by('blog_comments.created','desc');
       $this->comments_model->join('blog_posts','blog_posts.id_post  = blog_comments.post_id','left');
       $this->comments_model->join('users','blog_comments.creator  = users.id','left');
       $this->comments_model->limit($this->limit, $offset)->where($where);
       $comments = $this->comments_model->find_all();

       $this->load->library('pagination');

       $this->pager['base_url']    = base_url()."blog/content/comments/";
       $this->pager['per_page']    = $this->limit;
       $this->comments_model->join('blog_posts','blog_posts.id_post  = blog_comments.post_id','left');
       $this->pager['total_rows']  = $this->comments_model->where($where)->count_all();
       $this->pager['uri_segment'] = 4;

       $this->pagination->initialize($this->pager);

      Template::set('comments', $comments);
      Template::set('toolbar_title', lang("blog_comments"));
      Template::render();
    }

    public function approve_comment($id){

      $comment = $this->comments_model->find($id);
      if (! isset($comment)) {
          Template::set_message(lang('blog_invalid_comment_id'), 'error');
          Template::redirect($this->agent->referrer());
      }

          $this->auth->restrict($this->permissionDelete);

          if ($this->comments_model->update($id,array("approved"=>1))) {

              $id_act = log_activity($this->auth->user_id(), lang('blog_comment_act_approve_record') . ': ' . $id , 'commments');
              log_notify($this->auth->users_has_permission($this->permissionDelete), $id_act);

              Template::set_message(lang('blog_comment_approve_success'), 'success');

          }else{

          Template::set_message(lang('blog_comment_approve_failure') . $this->comments_model->error, 'error');

    }

    Template::redirect($this->agent->referrer());

  }


    public function delete_comment($id){

      $comment = $this->comments_model->find($id);
      if (! isset($comment)) {
          Template::set_message(lang('blog_invalid_comment_id'), 'error');
          Template::redirect($this->agent->referrer());
      }

          $this->auth->restrict($this->permissionDelete);

          if ($this->comments_model->delete($id)) {

              $id_act = log_activity($this->auth->user_id(), lang('blog_comment_act_delete_record') . ': ' . $id , 'commments');
              log_notify($this->auth->users_has_permission($this->permissionDelete), $id_act);

              Template::set_message(lang('blog_comment_delete_success'), 'success');

          }else{

          Template::set_message(lang('blog_comment_delete_failure') . $this->comments_model->error, 'error');

    }

    Template::redirect($this->agent->referrer());

    }

    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    private function delete_category($id){

      $category = $this->category_model->find($id);
      if (! isset($category)) {
          Template::set_message(lang('us_invalid_category_id'), 'error');
          Template::redirect('blog/category');
      }

          $this->auth->restrict($this->permissionDelete);

          $node = $this->nested_set->getNodeWhere('id_category = '.$id);

          if ($this->nested_set->deleteNode($node)) {

              $id_act = log_activity($this->auth->user_id(), lang('category_act_delete_record') . ': ' . $category->name_category , 'category');
              log_notify($this->auth->users_has_permission($this->permissionDelete), $id_act);

              Template::set_message(lang('category_delete_success'), 'success');
              return;
          }

          Template::set_message(lang('category_delete_failure') . $this->category_model->error, 'error');

    }


    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    private function save_category($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id_category'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->category_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want

        $data = $this->category_model->prep_data($this->input->post());
        $data['created_by'] = $this->current_user->id;
        // Additional handling for default values should be added below,
        // or in the model's prep_data() method

        $config = array(
            'field' => 'slug_category',
            'title' => 'name_category',
            'table' => 'blog_categories',
            'id' => 'id_category',
        );

        $this->load->library('slug', $config);
        $data['slug_category'] = $this->slug->create_uri($this->input->post('name_category'));
        $data['created_on'] = date('Y-m-d H:i:s');

        $return = false;
        if ($type == 'insert') {


            $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>$this->input->post('parent_category')));
            $node = $this->nested_set->insertNewChild($parent_node,$data);
            $id   = $node['id_category'];

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {

          $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>$this->input->post('parent_category')));
          $node = $this->nested_set->getNodeFromId($id);
          $this->nested_set->setNodeAsFirstChild($node,$parent_node);


          $return = $this->category_model->update($id, $data);

        }

        return $return;
    }



    public function get_tree_categ(){

      if (!$this->input->is_ajax_request()) {  exit('No direct script access allowed'); }

      $id_node = $this->input->post('parent_categ');
      $this->db->select('id_category,name_category');
      $this->db->from('categories');
      $this->db->where('parent_category',$id_node);
      $tree = $this->db->get()->result();

        $this->output->set_output(json_encode($tree));

    }


    private function send_blog_email($ids,$blog){

      $this->load->library('emailer/emailer');

      $ids = explode(',',$ids);

      foreach($ids as $id){

      if(has_email_prefname('blog_new_post',$id) and $this->online->is_online($id)){

      $data = array(
          'to'      => $this->user_model->find($id)->email,
          'subject' => $blog->title_post,
          'message' => $this->load->view(
              '_emails/blog_email',
              array('blog' => $blog),
              true
          ),
       );

      if ($this->emailer->send($data,true)) {

          Template::set_message(lang('blog_send_email_success'), 'success');

      } else {

          Template::set_message(lang('blog_send_email_error') . $this->emailer->error, 'danger');

      }

    }

    }
  }
    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    private function save_blog($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id_blog'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->blog_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }



        $upload_path = Modules::path('blog','assets/images/posts_preview');

        if(!is_dir($upload_path)){ mkdir($upload_path,0777);  }

        $_POST['preview_image'] = '';

        if($_FILES['preview_image']['error'] == 0) {

        $config = array(
          'upload_path' => $upload_path,
          'allowed_types' => "jpg|png|jpeg|gif",
          'encrypt_name' => true,
          'max_size' => 2048, // Can be set to particular file size , here it is 2 MB(2048 Kb)
          'max_height' => 2000,
          'max_width' => 2000,
          'min_height'=> 200,
          'min_width' => 200
        );

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('preview_image')) {

          $upload_data = $this->upload->data();
          $file_name = $upload_data['file_name'];
          $_POST['preview_image'] = $file_name;

        }else{

            $error = array('error' => $this->upload->display_errors());
            Template::set_message($error['error'], 'error');
            Template::redirect($this->agent->referrer());

          }
        }
        // Make sure we only pass in the fields we want

        $data = $this->blog_model->prep_data($this->input->post());
        $data['created_by'] = $this->current_user->id;
        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        $roles = implode(",",$this->input->post('roles_access'));
        $data['roles_access'] = $roles;

        $config = array(
            'field' => 'slug_post',
            'title' => 'title_post',
            'table' => 'blog_posts',
            'id' => 'id_post',
        );

        $this->load->library('slug', $config);
        $data['slug_post'] = $this->slug->create_uri($this->input->post('title_post'));


        $return = false;
        if ($type == 'insert') {
            $id = $this->blog_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->blog_model->update($id, $data);
        }

        return $return;
    }


     public function upload_ck(){

    	 $this->authenticate();

    	 //if (!$this->input->is_ajax_request()) { exit('No direct script access allowed');  }

    		 ob_get_level();

    		 //Image Save Option

    		 $this->load->helper('cookie');

    		 $cookie_csrf = get_cookie('ckCsrfToken');
    		 $csrf = $this->input->post('ckCsrfToken');

    		 if($cookie_csrf != $csrf){

    			 $jsondata = array('uploaded'=> 0, 'fileName'=> 'null', 'url'=> 'null');
    			 echo json_encode($jsondata);
    			 return false;

    		 }

    		 $path = Modules::path('blog','assets/images/posts_body');

    		 $config['upload_path'] = $path; //YOUR PATH
    		 $config['allowed_types'] = 'gif|jpg|jpeg|png';
    		 $config['max_size'] = '90000';
    		 $config['encrypt_name'] = TRUE;

    		 //Form Upload, Drag & Drop
    		 $CKEditorFuncNum = $this->input->get('CKEditorFuncNum');
    		 if(empty($CKEditorFuncNum))
    		 {
    				 ////////////////////////////////////////////////////////////////////////////////////////////////////////
    				 // Drag & Drop
    				 ////////////////////////////////////////////////////////////////////////////////////////////////////////
    				 header('Content-Type: application/json');

    				 $this->load->library('upload', $config);
    				 if ( !$this->upload->do_upload("upload"))
    				 {

    				  $error = array('error' => $this->upload->display_errors());

    						 $jsondata = array('uploaded'=> 0, 'fileName'=> 'null', 'url'=> 'null', 'error'=>array('message'=>$error['error']));
    						 echo json_encode($jsondata);
    				 }
    				 else
    				 {
    						 $data = $this->upload->data();

    						 // JPG compression
    						 if($this->upload->data('file_ext') === '.jpg') {
    								 $filename = $this->upload->data('full_path');
    								 $img = imagecreatefromjpeg($filename);
    								 imagejpeg($img, $filename, 80);
    						 }

    						 $filename = $data['file_name'];
    						 $url = base_url().'images/'.$filename.'?module=blog&assets=assets/images/posts_body';

    						 $jsondata = array('uploaded'=> 1, 'fileName'=> $filename, 'url'=> $url);
    						 echo json_encode($jsondata);
    				 }
    		 }
    		 else
    		 {
    				 ////////////////////////////////////////////////////////////////////////////////////////////////////////
    				 // Form Upload
    				 ////////////////////////////////////////////////////////////////////////////////////////////////////////
    				 $this->load->library('upload', $config);
    				 if ( !$this->upload->do_upload("upload"))
    				 {
    						 echo "<script>alert('Send Fail".$this->upload->display_errors('','')."')</script>";
    				 }
    				 else
    				 {
    						 $CKEditorFuncNum = $this->input->get('CKEditorFuncNum');
    						 $data = $this->upload->data();

    						 // JPG compression
    						 if($this->upload->data('file_ext') === '.jpg') {
    								 $filename = $this->upload->data('full_path');
    								 $img = imagecreatefromjpeg($filename);
    								 imagejpeg($img, $filename, 80);
    						 }

    						 $filename = $data['file_name'];

    						 $url = base_url().'images/'.$filename.'?module=blog&assets=assets/images/posts_body';
    						 echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$CKEditorFuncNum."', '".$url."', 'Send OK')</script>";
    				 }
    		 }

    		 ob_end_flush();
     }
}
