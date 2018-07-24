<?php defined('BASEPATH') || exit('No direct script access allowed');

class Category_model extends BF_Model{

  protected $table_name	= 'blog_categories';
	protected $key			  = 'id_category';
	protected $date_format	= 'datetime';

	protected $log_user 	= true;
	protected $set_created	= true;
	protected $set_modified = true;
	protected $soft_deletes	= true;

	protected $created_field     = 'created_on';
  protected $created_by_field  = 'created_by';
	protected $modified_field    = 'modified_on';
  protected $modified_by_field = 'modified_by';
  protected $deleted_field     = 'deleted';
  protected $deleted_by_field  = 'deleted_by';

	// Customize the operations of the model without recreating the insert,
    // update, etc. methods by adding the method names to act as callbacks here.
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 	    = array();
	protected $after_find 		= array();
	protected $before_delete 	= array();
	protected $after_delete 	= array();

	// For performance reasons, you may require your model to NOT return the id
	// of the last inserted row as it is a bit of a slow method. This is
    // primarily helpful when running big loops over data.
	protected $return_insert_id = true;

	// The default type for returned row data.
	protected $return_type = 'object';

	// Items that are always removed from data prior to inserts or updates.
	protected $protected_attributes = array();

	// You may need to move certain rules (like required) into the
	// $insert_validation_rules array and out of the standard validation array.
	// That way it is only required during inserts, not updates which may only
	// be updating a portion of the data.
	protected $validation_rules 		= array(
		array(
			'field' => 'name_category',
			'label' => 'lang:category_field_name_category',
			'rules' => 'required',
		)
	);
	protected $insert_validation_rules  = array();
	protected $skip_validation 			= false;

  /**
   * Constructor
   *
   * @return void
   */
  public function __construct(){

      parent::__construct();
  }

  public function search_categs_json($term){

    $this->db->cache_on();
    $this->db->select("id_category,name_category");
    $this->db->from($this->table_name);
    $this->db->where('deleted', 0);
    $this->db->like('name_category',$term);
    $this->db->limit(10);
    $query = $this->db->get()->result();
    $this->db->cache_off();
    return $query;

  }

  function get_blog_categories($id){

    $this->db->select("id_category,name_category");
    $this->db->from('blog_categs');
    $this->db->join($this->table_name,$this->table_name.'.id_category = blog_categs.category_id','left');
    $this->db->where('blog_post_id', $id);
    return $this->db->get();

  }

	  function get_items_category($categ){

		$this->db->select("id_variation,id_item,name_item,description_item,name_unit,item_price");
    $this->db->from("items_categories");
		$this->db->join("items","items_categories.item_id = items.id_item","left");
    $this->db->join("items_variations","items_variations.id_item_variation = items.id_item","left");
		$this->db->join("items_price","items.id_item = items_price.item_id","left");
		$this->db->join("items_unit","items_unit.id_unit = items.unit","left");
		$this->db->where("items_categories.category_id",$categ);
    $this->db->where("items.deleted",0);
    $this->db->where("items_variations.is_default",1);

    return $this->db->get();

		}

	  function count_items_in_categ($categ){

    $this->db->select('id_join_category');
    $this->db->from('blog_categs');
    $this->db->where('category_id',$categ);
		return $this->db->get()->num_rows();

		}
}
