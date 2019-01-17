<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Category controller
 */
class Category extends Front_Controller{

    protected $permissionCreate = 'Blog.Content.Create';
    protected $permissionDelete = 'Blog.Content.Delete';
    protected $permissionEdit   = 'Blog.Content.Edit';
    protected $permissionView   = 'Blog.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(){

        parent::__construct();

        $this->load->model('blog/category_model');
        $this->load->model('blog/blog_model');

        $this->lang->load('blog/blog');
        $this->lang->load('blog/category');

        $this->load->library('blog/Nested_set');

        $this->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');

    }






    public function profile(){

      $this->authenticate($this->permissionView);

          $id = $this->uri->segment(4);

          if (empty($id)) {
              Template::set_message(lang('category_invalid_id'), 'error');
              redirect('blog/category');
          }

          if($category = $this->category_model->find_by('slug_category',$id)){

            $offset = $this->uri->segment(5);
            $where = array('blog_posts.deleted'=>0,'blog_categs.category_id'=>$category->id_category);
            $this->blog_model->select("slug_post,title_post,blog_posts.created_on as created_on");
            $this->blog_model->join('blog_categs','blog_categs.blog_post_id = blog_posts.id_post','left');
            $this->blog_model->order_by('title_post','asc');
            $this->blog_model->group_by('id_post');
            $this->blog_model->limit($this->limit, $offset)->where($where);
            $posts = $this->blog_model->find_all();

            $this->load->library('pagination');

            $this->pager['base_url']    = base_url()."blog/category/profile/".$category->slug_category."/";
            $this->pager['per_page']    = $this->limit;

            $this->blog_model->join('blog_categs','blog_categs.blog_post_id = blog_posts.id_post','left');
            $this->pager['total_rows']  = $this->blog_model->where($where)->count_all();
            $this->pager['uri_segment'] = 5;

            $this->pagination->initialize($this->pager);

            Template::set('posts',$posts);
            Template::set('toolbar_title', $category->name_category);
            Template::set('categ', $category);
            Template::render();

          }else{

            Template::set_message(lang('category_invalid_id'), 'error');
            redirect('blog/category');
          }
    }





}
