<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Category controller
 */
class Category extends Front_Controller{

    protected $permissionCreate = 'Category.Content.Create';
    protected $permissionDelete = 'Category.Content.Delete';
    protected $permissionEdit   = 'Category.Content.Edit';
    protected $permissionView   = 'Category.Content.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(){

        parent::__construct();

        $this->load->model('blog/category_model');

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
      Template::render('mod_index');

    }


    public function profile(){

      $this->authenticate($this->permissionView);

          $id = $this->uri->segment(4);

          if (empty($id)) {
              Template::set_message(lang('category_invalid_id'), 'error');
              redirect('item/category');
          }

          if($category = $this->category_model->find_by('slug_category',$id)){


            $offset = $this->uri->segment(5);
            $where = array('items.deleted'=>0,'items_categories.category_id'=>$category->id_category);

            $this->item_model->join('items_categories','items_categories.item_id = items.id_item','left');
            $this->item_model->order_by('name_item','asc');
            $this->item_model->group_by('id_item');
            $this->item_model->limit($this->limit, $offset)->where($where);
            $itens = $this->item_model->find_all();

            $this->load->library('pagination');

            $this->pager['base_url']    = base_url()."item/category/profile/".$category->slug_category."/";
            $this->pager['per_page']    = $this->limit;

            $this->item_model->join('items_categories','items_categories.item_id = items.id_item','left');
            $this->pager['total_rows']  = $this->item_model->where($where)->count_all();
            $this->pager['uri_segment'] = 5;

            $this->pagination->initialize($this->pager);

            Template::set('itens',$itens);
            Template::set('toolbar_title', $category->name_category);
            Template::set('categ', $category);
            Template::render();

          }else{

            Template::set_message(lang('category_invalid_id'), 'error');
            redirect('item/category');
          }
    }

    public function send_pic(){

      $upload_path = Modules::path('item','assets/images/categories/');

      $config = array(
       'upload_path' => $upload_path,
       'allowed_types' => "*",
       'encrypt_name' => true,
       'max_size' => 2048, // Can be set to particular file size , here it is 2 MB(2048 Kb)
       'max_height' => 2000,
       'max_width' => 2000,
       'min_height'=> 300,
       'min_width' => 300
     );

     $this->load->library('upload',$config);

     if ($this->upload->do_upload('category_pic')) {

       $image_data = $this->upload->data();

        $this->load->library('users/image_op');

        $this->image_op->padronize($upload_path,$image_data,300,200,50);

            $this->db->where('id_category',$this->input->post('id_category'));
            $this->db->update('categories',array('image_category'=>$image_data['file_name']));
            $this->output->set_output(json_encode(array('status'=>true,'message'=>lang('us_profile_avatar_send_success'),'classe'=>'alert-success','image'=>$image_data['file_name'])));

     }else{

   $error = array('error' => $this->upload->display_errors());
   $this->output->set_output(json_encode(array('message'=>$error['error'],'classe'=>'alert-danger','image'=>false)));

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
