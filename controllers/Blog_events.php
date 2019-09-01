<?php defined('BASEPATH') || exit('No direct script access allowed');


class Blog_events{

  private $CI;

 function __construct(){

    $this->CI =& get_instance();
    $this->CI->lang->load("blog/blog");
  }

  function _show_widget_news(){


    $this->CI->load->model("blog/blog_model");

    $where = array('blog_posts.deleted'=>0);

    $this->CI->blog_model->select("title_post,slug_post,preview_image,blog_posts.created_on as created_on,email,display_name,photo_avatar,username");
    $this->CI->blog_model->order_by('blog_posts.created_on','desc');
    $this->CI->blog_model->join('users','blog_posts.created_by  = users.id','left');
    $this->CI->blog_model->limit(6)->where($where);
    if(isset($search_text)){ $this->CI->blog_model->like('title_post',$search_text); }
    $data['posts'] = $this->CI->blog_model->find_all();

    return $this->CI->load->view('blog/widgets/news',$data,true);

  }


  public function _get_user_notif(&$payload){

      $this->CI->lang->load('blog/blog');

      $notifications = $this->CI->notification_model->get_user_notifications('blog');

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

     $this->CI->db->select('*');
     $this->CI->db->from('email_preferences');
     $this->CI->db->where('module','blog');
     $result = $this->CI->db->get();

     foreach($result->result() as $p){

       array_push($data['prefs'],array("id_pref"=>$p->preference_id,"name"=>lang($p->preference_name),"desc"=>lang($p->preference_desc)));

     }

   }

   public function default_email_prefs($data){

   $this->CI->db->select('preference_id');
   $this->CI->db->from('email_preferences');
   $this->CI->db->where('module','blog');
   $prefs_mod = $this->CI->db->get();

   if($prefs_mod->num_rows()){

   foreach($prefs_mod->result() as $pref){

     $data = array(
           'id_email_pref'=>$pref->preference_id,
           'id_user_pref'=>$data['user_id']
         );

    $this->CI->db->insert('email_pref_users',$data);

   }
  }
 }
}
