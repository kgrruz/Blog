<?php defined('BASEPATH') || exit('No direct script access allowed');


class Events_category{

  private $CI;
  private $permissionView = 'Category.Content.View';

 function __construct(){

    $this->CI =& get_instance();
    $this->CI->lang->load('blog/category');
    $this->CI->load->model('blog/category_model');
    $this->CI->load->library('blog/Nested_set');
    $this->CI->nested_set->setControlParams('blog_categories','lft','rgt','id_category','parent_category','name_category');

 }

 public function _search_categs(&$records){

   array_push($records,$this->CI->category_model->search_categs_json($this->CI->input->post('search')));


 }


 public function _add_to_categ(&$data){

   if(isset($data['data']['category'])){

     $categs = $data['data']['category'];

   $this->CI->db->where('blog_post_id',$data['blog_post_id']);
   $this->CI->db->delete('blog_categs');

   foreach($categs as $categ){

     $this->CI->db->insert('blog_categs',array('blog_post_id'=>$data['blog_post_id'],'category_id'=>$categ,'created_by'=>$data['created_by']));

     }
    }
   }

   public function _get_categs(&$data){

     $parent_node = $this->CI->nested_set->getNodeWhere(array('id_category'=>1));
     $tree = $this->CI->nested_set->getSubTree($parent_node);

     array_push($data['categories'],$tree);

   }

 }
