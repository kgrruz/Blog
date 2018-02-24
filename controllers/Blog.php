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

        $this->load->library('users/auth');
        $this->auth->restrict();
        $this->set_current_user();

        $this->load->model('blog/blog_model');
        $this->lang->load('blog/blog');

        $this->load->library('users/Online_Users');

          Assets::add_module_css('blog', 'summernote-bs4.css');
          Assets::add_module_css('blog', 'blog.css');
          Assets::add_module_js('blog', 'summernote-bs4.min.js');
          Assets::add_module_font('blog', 'summernote.woff');
          Assets::add_module_font('blog', 'summernote.ttf');
          Assets::add_module_font('blog', 'summernote.eot');

          Assets::add_module_js('blog', 'blog.js');

					Assets::add_module_css('blog', 'jquery-comments.css');
          Assets::add_module_js('blog', 'jquery-comments.min.js');
          Assets::add_module_js('blog', 'comments.js');

        }

    /**
     * Display a list of Blog data.
     *
     * @return void
     */
    public function index(){

        $this->auth->restrict($this->permissionView);
        $this->online_users->run_online();

        $this->db->order_by('blog_posts.created_on','desc');
        $this->db->join('users','blog_posts.created_by  = users.id','left');
        Template::set('posts',$this->blog_model->find_all());
        Template::set('toolbar_title', lang('blog_list'));
        Template::set_block('sub_nav_menu', '_menu_module');
        Template::render('mod_index');

    }


    public function post(){

        $this->auth->restrict($this->permissionView);
        $this->online_users->run_online();

        $id = $this->uri->segment(3);
        if (empty($id)) {

            Template::set_message(lang('blog_invalid_id'), 'error');
            redirect('blog');
        }

         $this->db->join('users','blog_posts.created_by  = users.id','left');
        if($post = $this->blog_model->find_by('slug_post',$id)){

        Template::set('post',$post);
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

        $this->auth->restrict($this->permissionCreate,'blog');
        $this->online_users->run_online();


        if (isset($_POST['save'])) {

          $upload_path = Modules::path('blog','assets/images/posts');

          if(!is_dir($upload_path)){ mkdir($upload_path,0777);  }

					$_POST['preview_image'] = '';

					if($_FILES['preview_image']['error'] == 0) {

          $config = array(
        'upload_path' => $upload_path,
        'allowed_types' => "jpg|png|jpeg",
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

                $blog = $this->blog_model->find($insert_id);

                $id_act = log_activity($this->auth->user_id(), lang('blog_act_create_record') . ': ' . anchor('blog/post/'.$blog->slug_post,$blog->title_post), 'blog');
                log_notify($this->user_model->get_id_users_role('id',array(1,4)), $id_act);

                Template::set_message(lang('blog_create_success'), 'success');
                Template::redirect('blog');

            }

            // Not validation error
            if ( ! empty($this->blog_model->error)) {
                Template::set_message(lang('blog_create_failure') . $this->blog_model->error, 'error');
            }
        }

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

            $this->auth->restrict($this->permissionEdit);

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

          $this->auth->restrict($this->permissionDelete);

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

        $config = array(
            'field' => 'slug_post',
            'title' => 'title_post',
            'table' => 'co_blog_posts',
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



}
