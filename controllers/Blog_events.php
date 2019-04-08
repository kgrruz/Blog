<?php defined('BASEPATH') || exit('No direct script access allowed');


class Blog_events{

  private $CI;

 function __construct(){

    $this->CI =& get_instance();

  }

  function _show_widget_news(){

    $this->CI->lang->load("blog/blog");
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
}
