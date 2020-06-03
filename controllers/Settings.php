<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Settings controller
 */
class Settings extends Admin_Controller
{
    protected $permissionCreate = 'Blog.Settings.Create';
    protected $permissionDelete = 'Blog.Settings.Delete';
    protected $permissionEdit   = 'Blog.Settings.Edit';
    protected $permissionView   = 'Blog.Settings.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->auth->restrict($this->permissionView);
        $this->load->model('blog/blog_model');
        $this->lang->load('blog');

        Template::set_block('sub_nav', 'settings/_sub_nav');

        Assets::add_module_js('blog', 'blog.js');
    }

    /**
     * Display a list of Products data.
     *
     * @return void
     */
    public function index(){

      if (isset($_POST['save'])) {

           $this->form_validation->set_rules('post_visibility', 'lang:blog_settings_post_visibility', 'required|integer');
           $this->form_validation->set_rules('email_new_comment', 'lang:blog_settings_email_enever', 'integer');
           $this->form_validation->set_rules('email_new_comment_mod', 'lang:blog_settings_email_enever', 'integer');
           $this->form_validation->set_rules('must_aprove_comment', 'lang:blog_settings_before_comments_appear', 'integer');
           $this->form_validation->set_rules('block_post_after', 'lang:blog_settings_close_comments', 'required|integer');
           $this->form_validation->set_rules('block_post_after_period', 'lang:blog_settings_close_comments', 'required');
           $this->form_validation->set_rules('comment_flood', 'lang:blog_settings_comment_flood_time', 'required|integer');
           $this->form_validation->set_rules('comment_flood_period', 'lang:blog_settings_comment_flood_time', 'required');
           $this->form_validation->set_rules('maxsize_preview_image', 'lang:blog_settings_maxsize_preview_image', 'required|integer');
           $this->form_validation->set_rules('maxsize_body_image', 'lang:blog_settings_maxsize_body_image', 'required|integer');
           $this->form_validation->set_rules('maxsize_comment_attach', 'lang:blog_settings_maxsize_comment_attach', 'required|integer');
           $this->form_validation->set_rules('allow_types_attach', 'lang:blog_settings_allow_types_attach', 'required');

           if ($this->form_validation->run() == FALSE){

             Template::set_message(validation_errors(), 'danger');
            Template::redirect('admin/settings/blog');

          }else{

          $data = array(
            array('name'=>'blog.post_visibility', 'value'=> $this->input->post("post_visibility")),
            array('name'=>'blog.email_new_comment', 'value'=>$this->input->post("email_new_comment")),
            array('name'=>'blog.email_new_comment_mod', 'value'=>$this->input->post("email_new_comment_mod")),
            array('name'=>'blog.must_aprove_comment', 'value'=>$this->input->post("must_aprove_comment")),
            array('name'=>'blog.block_post_after', 'value'=>$this->input->post("block_post_after").' '.$this->input->post("block_post_after_period")),
            array('name'=>'blog.comment_flood', 'value'=>$this->input->post("comment_flood").' '.$this->input->post("comment_flood_period")),
            array('name'=>'blog.maxsize_preview_image', 'value'=>$this->input->post("maxsize_preview_image")),
            array('name'=>'blog.maxsize_body_image', 'value'=>$this->input->post("maxsize_body_image")),
            array('name'=>'blog.maxsize_comment_attach', 'value'=>$this->input->post("maxsize_comment_attach")),
            array('name'=>'blog.allow_types_attach', 'value'=>$this->input->post("allow_types_attach"))
          );

          $updated = $this->settings_lib->update_batch($data);

          Template::set_message(lang("blog_settings_create_success"), 'success');
         Template::redirect('admin/settings/blog');

        }
      }

        Template::set('settings',$this->settings_lib->find_all());
        Template::set('toolbar_title', lang('blog_manage'));
        Template::render();

    }


}
