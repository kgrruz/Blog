<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Blog controller
 */
class Blog extends Front_Controller{


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

        $this->authenticate();
        $offset = $this->uri->segment(3);

        if($this->input->post('search') != NULL ){
          $search_text = $this->input->post('search');
          $this->session->set_userdata(array("search"=>$search_text));
        }else{
        if($this->session->userdata('search') != NULL and $this->uri->segment(2) == 'index'){
          $search_text = $this->session->userdata('search');
        }else{  $this->session->unset_userdata('search'); }
      }

       $find_in_set = "FIND_IN_SET(".$this->current_user->role_id.", ".$this->db->dbprefix."blog_posts.roles_access)";

       $where = array('blog_posts.deleted'=>0);

        $this->blog_model->select("title_post,slug_post,preview_image,blog_posts.created_on as created_on,email,display_name,photo_avatar,username");
        $this->blog_model->order_by('blog_posts.created_on','desc');
        $this->blog_model->join('users','blog_posts.created_by  = users.id','left');
        $this->blog_model->limit(6, $offset)->where($where)->where($find_in_set)->or_where('created_by',$this->current_user->id)->where($where);
        if(isset($search_text)){ $this->blog_model->like('title_post',$search_text); }
        $posts = $this->blog_model->find_all();

        $this->load->library('pagination');

        $this->pager['base_url']    = base_url()."blog/index/";
        $this->pager['per_page']    = 6;
        if(isset($search_text)){ $this->blog_model->like('title_post',$search_text); }
        $this->pager['total_rows']  = $this->blog_model->where($where)->where($find_in_set)->or_where('created_by',$this->current_user->id)->where($where)->count_all();
        $this->pager['uri_segment'] = 3;

        $this->pagination->initialize($this->pager);

                    $this->load->library('blog/Nested_set');
                    $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');
                    $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
                    $tree = $this->nested_set->getSubTree($parent_node);
                    Template::set('tree', $tree);

        Template::set('posts',$posts);
        Template::set('toolbar_title', lang('blog_list'));

        Template::render();


    }

    public function categp($id){

      $this->authenticate();

          $id = $this->uri->segment(3);

          if (empty($id)) {
              Template::set_message(lang('category_invalid_id'), 'danger');
              redirect('blog');
          }

          if($category = $this->category_model->find_by('slug_category',$id)){

            $offset = $this->uri->segment(4);

            $find_in_set = "FIND_IN_SET(".$this->current_user->role_id.", ".$this->db->dbprefix."blog_posts.roles_access)";

            $where = array('blog_posts.deleted'=>0,'blog_categs.category_id'=>$category->id_category);

            $this->blog_model->select("title_post,slug_post,preview_image,blog_posts.created_on as created_on,blog_posts.created_by as created_by, email,display_name,photo_avatar,username");
            $this->blog_model->join('blog_categs','blog_categs.blog_post_id = blog_posts.id_post','left');
            $this->blog_model->join('users','blog_posts.created_by  = users.id','left');
            $this->blog_model->order_by('blog_posts.created_on','desc');
            $this->blog_model->group_by('id_post');
            $this->blog_model->limit(6, $offset)->where($where)->where($find_in_set)->or_where('blog_posts.created_by',$this->current_user->id)->where($where);
            $posts = $this->blog_model->find_all();

            $this->load->library('pagination');

            $this->pager['base_url']    = base_url()."blog/categp/".$category->slug_category."/";
            $this->pager['per_page']    = 6;

            $this->blog_model->join('blog_categs','blog_categs.blog_post_id = blog_posts.id_post','left');
            $this->pager['total_rows']  = $this->blog_model->where($where)->where($find_in_set)->or_where('blog_posts.created_by',$this->current_user->id)->where($where)->count_all();
            $this->pager['uri_segment'] = 4;

            $this->pagination->initialize($this->pager);

            $this->load->library('blog/Nested_set');
            $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');
            $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
            $tree = $this->nested_set->getSubTree($parent_node);
            Template::set('tree', $tree);

            Template::set('posts',$posts);
            Template::set_view('blog/index');

            Template::set('toolbar_title', $category->name_category);
            Template::render();

          }else{

            Template::set_message(lang('category_invalid_id'), 'danger');
            redirect('blog');
          }


    }


    public function post(){

      if($this->auth->is_logged_in()){ $this->authenticate(); }

        $id = $this->uri->segment(3);
        if (empty($id)) {

            Template::set_message(lang('blog_invalid_id'), 'danger');
            redirect('blog');
        }

          $this->load->library("htmlfixer");

        $this->db->select("email,username,display_name,photo_avatar,id_post,title_post,slug_post,body_post,enable_comments,enable_attach,roles_access,blog_posts.created_on as created_on,created_by");
        $this->db->join('users','blog_posts.created_by  = users.id','left');
        $this->db->where('blog_posts.deleted',0);
        if($post = $this->blog_model->find_by('slug_post',$id)){

         if($this->auth->is_logged_in()){

          if($post->created_by != $this->current_user->id and !in_array($this->current_user->role_id,explode(',',$post->roles_access))){

            Template::set_message('Sem permissão para acessar o conteúdo.', 'danger');
            Template::redirect('blog');

              }
             }


          $this->load->library('blog/Nested_set');
          $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');
          $parent_node = $this->nested_set->getNodeWhere(array('id_category'=>1));
          $tree = $this->nested_set->getSubTree($parent_node);
          Template::set('tree', $tree);

        Template::set('post',$post);
        Template::set('categs_post',$this->category_model->get_blog_categories($post->id_post)->result());
        Template::set('toolbar_title', $post->title_post);

        Template::render();

      }else{

        Template::set_message(lang('blog_invalid_id'), 'danger');
        redirect('blog');
      }

    }



     public function _get_user_notif(&$payload){

         $this->lang->load('blog/blog');

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

      public function default_email_prefs($data){

      $this->db->select('preference_id');
      $this->db->from('email_preferences');
      $this->db->where('module','blog');
      $prefs_mod = $this->db->get();

      if($prefs_mod->num_rows()){

      foreach($prefs_mod->result() as $pref){

        $data = array(
              'id_email_pref'=>$pref->preference_id,
              'id_user_pref'=>$data['user_id']
            );

       $this->db->insert('email_pref_users',$data);

      }
     }
    }



}
