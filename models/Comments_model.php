<?php defined('BASEPATH') || exit('No direct script access allowed');

class Comments_model extends BF_Model{

    protected $table_name    = 'blog_comments';
    protected $key            = 'id';
    protected $date_format    = 'datetime';

    protected $log_user    = true;
    protected $set_created    = true;
    protected $set_modified = true;
    protected $soft_deletes    = true;

    protected $created_field     = 'created_on';
    protected $created_by_field  = 'created_by';
    protected $modified_field    = 'modified_on';
    protected $modified_by_field = 'modified_by';
    protected $deleted_field     = 'deleted';
    protected $deleted_by_field  = 'deleted_by';

    // Customize the operations of the model without recreating the insert,
    // update, etc. methods by adding the method names to act as callbacks here.
    protected $before_insert    = array();
    protected $after_insert    = array();
    protected $before_update    = array();
    protected $after_update    = array();
    protected $before_find        = array();
    protected $after_find        = array();
    protected $before_delete    = array();
    protected $after_delete    = array();

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
  	protected $validation_rules        = array();
    protected $insert_validation_rules  = array();
    protected $skip_validation            = false;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function get_comments($id_user,$qp){

    $this->db->select("blog_comments.id as id,parent,created,modified,content,file_url,file_mime_type,creator,users.display_name as fullname,photo_avatar as profile_picture_url,created_by_admin,upvote_count,user_has_upvoted,IF(creator = {$id_user},1,0) as created_by_current_user");
    $this->db->from("blog_comments");
    $this->db->join("users","users.id = blog_comments.creator");
    $this->db->where("post_id",$qp);
    $this->db->where("blog_comments.deleted",0);
    $this->db->group_by("blog_comments.id");
    return $this->db->get()->result();

    }


    public function register_files_up($upload_data, $idq, $idu)
    {
        foreach ($upload_data as $file) {
            $this->db->insert('quote_files', array(
  'id_quote_upload'=>$idq,
  'id_user_upload'=>$idu,
  'file_id'=>$file['file_name'],
  'file_name'=>$file['orig_name'],
  'file_type'=>$file['file_ext'],
  'file_size'=>$file['file_size'])
   );
        }
    }

    public function get_files_quote($idq)
    {
        return $this->db->query("select * from quote_files where id_quote_upload = '$idq' and deleted = 0");
    }

    public function delete_files_quote($id)
    {
        $this->db->where('id_quote_upload', $id);
        $this->db->where('deleted', 0);
        $this->db->update('quote_files', array('deleted'=>1));
    }

    public function get_file($file_id)
    {
        return $this->db->query("select file_name from quote_files where file_id = '$file_id' and deleted = 0")->row();
    }

    public function get_comment_autor($id_comment)
    {
        return $this->db->query("select creator from co_blog_comments where id = '$id_comment' limit 1 ")->row()->creator;
    }
}
