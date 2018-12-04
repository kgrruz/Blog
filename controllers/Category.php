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

        //$this->nested_set->initialiseRoot(array('name_category'=>'all categories','desc_category'=>'all categories of itens','created_by'=>1,'slug_category'=>'all_categories'));

        Assets::add_module_css('item', 'category.css');
        Assets::add_module_js('item', 'category.js');
        Assets::add_module_js('item', 'category_pic.js');
    }



    public function index(){

			$this->authenticate($this->permissionView);

      if (isset($_POST['delete'])) {
          $checked = $this->input->post('checked');
          if (empty($checked)) {
              // No users checked.
              Template::set_message(lang('us_empty_id'), 'error');
          } else {
              foreach ($checked as $userId) {
                  $this->delete($userId);
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



    /*
    **
     * Create a Category object.
     *
     * @return void
     */
    public function create(){

        $this->authenticate($this->permissionCreate);

        if (isset($_POST['save'])) {

            if ($insert_id = $this->save_category()) {
                log_activity($this->auth->user_id(), lang('category_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'category');
                Template::set_message(lang('category_create_success'), 'success');

                redirect('item/category');
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
        Template::render('mod_index');
    }
    /**
     * Allows editing of Category data.
     *
     * @return void
     */
    public function edit()
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
                redirect('item/category');
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
        Template::set_view('category/create');
        Template::set_block('sub_nav_menu', '_menu_module');
        Template::render('mod_index');



    }


    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    private function delete($id){

      $category = $this->category_model->find($id);
      if (! isset($category)) {
          Template::set_message(lang('us_invalid_category_id'), 'error');
          Template::redirect('item/category');
      }

          //$this->auth->restrict($this->permissionDelete);

          $node = $this->nested_set->getNodeWhere('id_category = '.$id);

          if ($this->nested_set->deleteNode($node)) {

              $id_act = log_activity($this->auth->user_id(), lang('category_act_delete_record') . ': ' . $category->name_category , 'category');
              log_notify($this->user_model->get_id_users_role('id',array(4,1)), $id_act);

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
            'table' => 'categories',
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






}
