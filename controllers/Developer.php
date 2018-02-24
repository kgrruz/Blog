<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Developer controller
 */
class Developer extends Admin_Controller
{
    protected $permissionCreate = 'Products.Developer.Create';
    protected $permissionDelete = 'Products.Developer.Delete';
    protected $permissionEdit   = 'Products.Developer.Edit';
    protected $permissionView   = 'Products.Developer.View';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->auth->restrict($this->permissionView);
        $this->load->model('products/products_model');
        $this->lang->load('products');
        
            $this->form_validation->set_error_delimiters("<span class='error'>", "</span>");
        
        Template::set_block('sub_nav', 'developer/_sub_nav');

        Assets::add_module_js('products', 'products.js');
    }

    /**
     * Display a list of Products data.
     *
     * @return void
     */
    public function index()
    {
        // Deleting anything?
        if (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);
            $checked = $this->input->post('checked');
            if (is_array($checked) && count($checked)) {

                // If any of the deletions fail, set the result to false, so
                // failure message is set if any of the attempts fail, not just
                // the last attempt

                $result = true;
                foreach ($checked as $pid) {
                    $deleted = $this->products_model->delete($pid);
                    if ($deleted == false) {
                        $result = false;
                    }
                }
                if ($result) {
                    Template::set_message(count($checked) . ' ' . lang('products_delete_success'), 'success');
                } else {
                    Template::set_message(lang('products_delete_failure') . $this->products_model->error, 'error');
                }
            }
        }
        
        
        
        $records = $this->products_model->find_all();

        Template::set('records', $records);
        
    Template::set('toolbar_title', lang('products_manage'));

        Template::render();
    }
    
    /**
     * Create a Products object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_products()) {
                log_activity($this->auth->user_id(), lang('products_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'products');
                Template::set_message(lang('products_create_success'), 'success');

                redirect(SITE_AREA . '/developer/products');
            }

            // Not validation error
            if ( ! empty($this->products_model->error)) {
                Template::set_message(lang('products_create_failure') . $this->products_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('products_action_create'));

        Template::render();
    }
    /**
     * Allows editing of Products data.
     *
     * @return void
     */
    public function edit()
    {
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('products_invalid_id'), 'error');

            redirect(SITE_AREA . '/developer/products');
        }
        
        if (isset($_POST['save'])) {
            $this->auth->restrict($this->permissionEdit);

            if ($this->save_products('update', $id)) {
                log_activity($this->auth->user_id(), lang('products_act_edit_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'products');
                Template::set_message(lang('products_edit_success'), 'success');
                redirect(SITE_AREA . '/developer/products');
            }

            // Not validation error
            if ( ! empty($this->products_model->error)) {
                Template::set_message(lang('products_edit_failure') . $this->products_model->error, 'error');
            }
        }
        
        elseif (isset($_POST['delete'])) {
            $this->auth->restrict($this->permissionDelete);

            if ($this->products_model->delete($id)) {
                log_activity($this->auth->user_id(), lang('products_act_delete_record') . ': ' . $id . ' : ' . $this->input->ip_address(), 'products');
                Template::set_message(lang('products_delete_success'), 'success');

                redirect(SITE_AREA . '/developer/products');
            }

            Template::set_message(lang('products_delete_failure') . $this->products_model->error, 'error');
        }
        
        Template::set('products', $this->products_model->find($id));

        Template::set('toolbar_title', lang('products_edit_heading'));
        Template::render();
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
    private function save_products($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id_product'] = $id;
        }

        // Validate the data
        $this->form_validation->set_rules($this->products_model->get_validation_rules());
        if ($this->form_validation->run() === false) {
            return false;
        }

        // Make sure we only pass in the fields we want
        
        $data = $this->products_model->prep_data($this->input->post());

        // Additional handling for default values should be added below,
        // or in the model's prep_data() method
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->products_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->products_model->update($id, $data);
        }

        return $return;
    }
}