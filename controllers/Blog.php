<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Blog controller
 */
class Blog extends Front_Controller{

    protected $permissionCreate = 'Blog.Content.Create';
    protected $permissionDelete = 'Blog.Content.Delete';
    protected $permissionEdit   = 'Blog.Content.Edit';
    protected $permissionView   = 'Blog.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('blog/blog_model');
        $this->load->model('blog/category_model');
        $this->load->model('roles/role_model');

        $this->lang->load('blog/blog');
        $this->lang->load('blog/category');

          Assets::add_module_css('blog', 'blog.css');
          Assets::add_module_js('blog', 'blog.js');

					Assets::add_module_css('blog', 'jquery-comments.css');
          Assets::add_module_js('blog', 'jquery-comments.min.js');
          Assets::add_module_js('blog', 'comments.js');

          $this->load->library('blog/Nested_set');
          $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');

        }

    /**
     * Display a list of Blog data.
     *
     * @return void
     */
    public function index(){

        $this->authenticate($this->permissionView);

        $offset = $this->uri->segment(3);

        if($this->input->post('search') != NULL ){
          $search_text = $this->input->post('search');
          $this->session->set_userdata(array("search"=>$search_text));
        }else{
        if($this->session->userdata('search') != NULL and $this->uri->segment(2) == 'index'){
          $search_text = $this->session->userdata('search');
        }else{  $this->session->unset_userdata('search'); }
      }

        $where = array('blog_posts.deleted'=>0);

        $this->blog_model->select("title_post,slug_post,preview_image,blog_posts.created_on as created_on,email,display_name,photo_avatar,username");
        $this->blog_model->order_by('blog_posts.created_on','desc');
        $this->blog_model->join('users','blog_posts.created_by  = users.id','left');
        $this->blog_model->limit(6, $offset)->where($where);
        if(isset($search_text)){ $this->blog_model->like('title_post',$search_text); }
        $posts = $this->blog_model->find_all();

        $this->load->library('pagination');

        $this->pager['base_url']    = base_url()."blog/index/";
        $this->pager['per_page']    = 6;
        if(isset($search_text)){ $this->blog_model->like('title_post',$search_text); }
        $this->pager['total_rows']  = $this->blog_model->where($where)->count_all();
        $this->pager['uri_segment'] = 3;

        $this->pagination->initialize($this->pager);

                    $this->load->library('blog/Nested_set');
                    $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');
                    $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
                    $tree = $this->nested_set->getSubTree($parent_node);
                    Template::set('tree', $tree);

        Template::set('posts',$posts);
        Template::set('toolbar_title', lang('blog_list'));
        Template::set_block('sub_nav_menu', '_menu_module');
        Template::render('mod_index');


    }

    public function categp($id){

      $this->authenticate($this->permissionView);

          $id = $this->uri->segment(3);

          if (empty($id)) {
              Template::set_message(lang('category_invalid_id'), 'error');
              redirect('blog');
          }

          if($category = $this->category_model->find_by('slug_category',$id)){

            $offset = $this->uri->segment(4);
            $where = array('blog_posts.deleted'=>0,'blog_categs.category_id'=>$category->id_category);

            $this->blog_model->join('blog_categs','blog_categs.blog_post_id = blog_posts.id_post','left');
            $this->blog_model->join('users','blog_posts.created_by  = users.id','left');
            $this->blog_model->order_by('blog_posts.created_on','desc');
            $this->blog_model->group_by('id_post');
            $this->blog_model->limit(6, $offset)->where($where);
            $posts = $this->blog_model->find_all();

            $this->load->library('pagination');

            $this->pager['base_url']    = base_url()."blog/catep/".$category->slug_category."/";
            $this->pager['per_page']    = 6;

            $this->blog_model->join('blog_categs','blog_categs.blog_post_id = blog_posts.id_post','left');
            $this->pager['total_rows']  = $this->blog_model->where($where)->count_all();
            $this->pager['uri_segment'] = 4;

            $this->pagination->initialize($this->pager);

            $this->load->library('blog/Nested_set');
            $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');
            $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
            $tree = $this->nested_set->getSubTree($parent_node);
            Template::set('tree', $tree);

            Template::set('posts',$posts);
            Template::set_view('blog/index');
            Template::set_block('sub_nav_menu', '_menu_module');
            Template::set('toolbar_title', $category->name_category);
            Template::render('mod_index');

          }else{

            Template::set_message(lang('category_invalid_id'), 'error');
            redirect('blog');
          }


    }


    public function post(){

        $this->authenticate($this->permissionView);

        $id = $this->uri->segment(3);
        if (empty($id)) {

            Template::set_message(lang('blog_invalid_id'), 'error');
            redirect('blog');
        }

         $this->db->join('users','blog_posts.created_by  = users.id','left');
        if($post = $this->blog_model->find_by('slug_post',$id)){

          $this->load->library('blog/Nested_set');
          $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');
          $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
          $tree = $this->nested_set->getSubTree($parent_node);
          Template::set('tree', $tree);

        Template::set('post',$post);
        Template::set('categs_post',$this->category_model->get_blog_categories($post->id_post)->result());
        Template::set('toolbar_title', $post->title_post);
        Template::set_block('sub_nav_menu', '_menu_module');
        Template::render('mod_index');

      }else{

        Template::set_message(lang('blog_invalid_id'), 'error');
        redirect('blog');
      }

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
              echo $error['error'];
              Template::redirect('blog/create');

            }
          }

            if ($insert_id = $this->save_blog()) {

              $data_insert = array('blog_post_id'=>$insert_id,'data'=>$_POST,'created_by'=>$this->current_user->id);

                Events::trigger('insert_post_blog',$data_insert);

                $blog = $this->blog_model->find($insert_id);

                $id_act = log_activity($this->auth->user_id(), lang('blog_act_create_record') . ': ' . anchor('blog/post/'.$blog->slug_post,$blog->title_post), 'blog');
                $ids = $this->user_model->get_id_users_role('id',$this->input->post('roles_access'));
                log_notify($ids, $id_act);

                $this->send_blog_email($ids,$blog);

                Template::set_message(lang('blog_create_success'), 'success');
                Template::redirect('blog');

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
        Template::render('mod_index');
    }
    /**
     * Allows editing of Blog data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(3);
        if (empty($id)) {

            Template::set_message(lang('blog_invalid_id'), 'error');
            redirect('blog');
        }


        $this->load->model('category_model');

        if (isset($_POST['save'])) {

            $this->authenticate($this->permissionEdit);

            if ($this->save_blog('update', $id)) {

              $data_insert = array('blog_id'=>$id,'categs_id'=>$this->input->post('category'));

                Events::trigger('insert_blog',$data_insert);

                log_activity($this->auth->user_id(), lang('blog_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'blog');
                Template::set_message(lang('blog_edit_success'), 'success');
                redirect('itens');
            }

            // Not validation error
            if ( ! empty($this->blog_model->error)) {
                Template::set_message(lang('blog_edit_failure') . $this->blog_model->error, 'error');
            }
        }

        elseif (isset($_POST['delete'])) {

          $this->authenticate($this->permissionDelete);

            if ($this->blog_model->delete($id)) {

                log_activity($this->auth->user_id(), lang('blog_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'blog');
                Template::set_message(lang('blog_delete_success'), 'success');
                return;

            }

            Template::set_message(lang('blog_delete_failure') . $this->blog_model->error, 'error');
        }

        Template::set('blog', $this->blog_model->find($id));
        Template::set('category', $this->category_model->get_blog_category($id));
        Template::set('toolbar_title', lang('blog_edit_heading'));
        Template::set_view('create');
        Template::set_block('sub_nav_menu', '_menu_module');
        Template::render('mod_index');
    }

    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------
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

     public function _get_user_notif(&$payload){

         $this->lang->load('forum/topic');

         $notifications = $this->notification_model->get_user_notifications('blog');

         foreach($notifications->result() as $not){

           array_push($payload['data'],
           array(
           'photo_avatar'=>$not->photo_avatar,
           'activity'=>$not->activity,
           'display_name'=>$not->display_name,
           'email'=>$not->email,
           'created_on'=>$not->created_on,
           'username'=>$not->username
         ));

         }
       }

       public function emails_prefs(&$data){

     		$this->db->select('*');
     		$this->db->from('email_preferences');
     		$this->db->where('module','blog');
     		$result = $this->db->get();

     		foreach($result->result() as $p){

     			array_push($data['prefs'],array("id_pref"=>$p->preference_id,"name"=>lang($p->preference_name),"desc"=>lang($p->preference_desc)));

     		}

     	}



}
