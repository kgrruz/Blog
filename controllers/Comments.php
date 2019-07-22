<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Comments controller
 */
class Comments extends Front_Controller{

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

				$this->load->model('blog/comments_model');
				$this->load->model('blog/blog_model');
        $this->lang->load('blog/blog');

        }

					public function getComments(){

							if (!$this->input->is_ajax_request()) {
									exit('No direct script access allowed');
							}

              if($this->auth->is_logged_in()){

                $this->authenticate();
              	$id_user = $this->session->userdata('user_id');

              }else{
                	$id_user = 0;
              }

							$qp = $this->uri->segment(4);


							$records = $this->comments_model->get_comments($id_user,$qp);


							foreach ($records as $result) {
									$result->created_by_current_user = (bool) $result->created_by_current_user;
									$result->parent = ($result->parent == 0)? null:$result->parent;
									$result->profile_picture_url = base_url().'uploads/users/thumbs/'.$result->profile_picture_url;
							}


							$this->output->set_output(json_encode($records));
					}


					public function postComments()
					{
							if (!$this->input->is_ajax_request()) {
									exit('No direct script access allowed');
							}

              if(!$this->auth->is_logged_in()){

                $error = array('status'=>false,'message' => 'É preciso fazer login para postar comentários.');
                $this->output->set_output(json_encode($error));

            }else{

              $this->authenticate();

							$this->load->config('blog/comments');

              if(!$this->blog_model->check_enable_comment($this->input->post('qp'))){ exit(json_encode(array('status'=>false,'message'=>'lang:blog_block_comment')));  }

              $flood = $this->settings_lib->item('blog.comment_flood');

              if($this->comments_model->check_flood($this->current_user->id,$flood)){

              exit(json_encode(array('status'=>false,'message'=>lang("blog_flood_comment").$flood)));

              }

              $post_id = $this->input->post('qp');

							$post = array(
    						'modified'=>date('Y-m-d H:i:s'),
    						'content'=>word_censor(strip_tags($this->input->post('content')), config_item('badwords'), '!#_+#@'),
    						'post_id'=>$post_id,
    						'creator'=>$this->session->userdata('user_id')
						);


							if ($this->input->post('parent') != '') {
									$post['parent'] = $this->input->post('parent');
							}

							if ($this->input->post('action') == 'insert') {

									$post['created'] =  date('Y-m-d H:i:s');
									$this->db->insert('blog_comments', $post);
									$insert_id = $this->db->insert_id();
									$post['id'] = $insert_id;
                  $post['status'] = true;

				  	}else if($this->input->post('action') == 'edit'){

                  $post['content'] = word_censor(strip_tags($this->input->post('content')), config_item('badwords'), '!#_+#@');
                  $this->db->where('id',$this->input->post('id'));
                	$this->db->update('blog_comments', array('content'=>$post['content'],'modified'=>date('Y-m-d H:i:s',strtotime($this->input->post('modified')))));
                  $post['status'] = true;
                  $post['id'] = $this->input->post('id');
          }

          $this->load->library('emailer/emailer');

          if($this->settings_lib->item('blog.email_new_comment') or $this->settings_lib->item('blog.must_aprove_comment')){

          $subject = ($this->settings_lib->item('blog.must_aprove_comment'))? lang("blog_subject_new_comment_mod"):lang("blog_subject_new_comment");

          $blog_post = $this->blog_model->find($post_id);

          $id_act = log_activity($post['creator'], '[blog_subject_new_comment] : ' . '<a href="blog/post/'.$blog_post->slug_post.'">'.$blog_post->title_post.'</a>', 'blog');
          log_notify($this->auth->users_has_permission($this->permissionDelete), $id_act);

          $data_body['blog_post'] = $blog_post;
          $data_body['comment'] = $post;

          $data = array(
              'to'      => $this->settings_lib->item('site.system_email'),
              'subject' => $subject.$blog_post->title_post,
              'message' => $this->load->view(
                  '_emails/new_comment',
                  $data_body,
                  true
              ),
           );

          if ($this->emailer->send($data,true)) {
              Template::set_message(lang('blog_new_comment_email_success'), 'success');
          } else {
              Template::set_message(lang('blog_new_comment_email_error') . $this->emailer->error, 'danger');
          }

        }


          $post['fullname'] = $this->current_user->display_name;
          $post['created_by_current_user'] = true;
          $post['profile_picture_url'] = $this->input->post('profile_picture_url');

          $this->output->set_output(json_encode($post));
  }

			}


					public function deleteComments()
					{

            if (!$this->input->is_ajax_request()) {
                exit('No direct script access allowed');
            }

            $this->authenticate();

							if ($this->input->post('id')) {
									$this->comments_model->delete($this->input->post('id'));
							}

							$this->output->set_output(json_encode(array('status'=>true)));
					}



					public function uploadAttachments(){

            if (!$this->input->is_ajax_request()) {
                exit('No direct script access allowed');
            }

            $this->authenticate();

            if(!$this->blog_model->check_enable_attach($this->input->post('qp'))){ exit(json_encode(array('status'=>false,'message'=>'lang:blog_block_attach')));  }

            $flood = $this->settings_lib->item('blog.comment_flood');

            if($this->comments_model->check_flood($this->current_user->id,$flood)){

            exit(json_encode(array('status'=>false,'message'=>lang("blog_flood_comment").$flood)));

            }

							if (!empty($_FILES['file']['name'])) {

                      $path = './uploads/blog/comments/';

											$config['upload_path'] =  $path;
											$config['allowed_types'] = 'jpeg|png|gif|jpg';
											$config['max_size']     = '2500';
											$config['max_width'] = '1024';
											$config['max_height'] = '768';
											$config['encrypt_name'] = true;

											$this->load->library('upload', $config);
          						 // Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
          						 $this->upload->initialize($config);

											if (!$this->upload->do_upload('file')) {

													$error = array('status'=>false,'message' => $this->upload->display_errors());
                          $this->output->set_output(json_encode($error));

											}else{

												$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

												$post = array(
												'created'=>date('Y-m-d H:i:s'),
												'modified'=>date('Y-m-d H:i:s'),
												'file_url'=> 'uploads/blog/comments/'.$upload_data['file_name'],
												'file_mime_type'=>$upload_data['file_type'],
												'file_size'=>$upload_data['file_size'],
												'post_id'=>$this->input->post('qp'),
												'creator'=>$this->session->userdata('user_id')
												);

													if ($this->input->post('parent') != '') {
															$post['parent'] = $this->input->post('parent');
													}

													$this->db->insert('blog_comments', $post);
													$insert_id = $this->db->insert_id();
													$post['id'] = $insert_id;
													$post['fullname'] = $this->current_user->display_name;
													$post['created_by_current_user'] = true;
													$post['status'] = true;
													$post['profile_picture_url'] = $this->input->post('profile_picture_url');
													$this->output->set_output(json_encode($post));
								}
							}
						}

            public function attach($file){

              if($this->auth->is_logged_in()){

              $this->authenticate();

             }

              $this->load->helper('file');
              $path = './uploads/blog/comments/'.$file;

              header('Content-Type: '.get_mime_by_extension($file));
              header('Content-length: '.filesize($path));
              header('Content-Disposition: inline; filename="'.$file.'";'); //<-- sends filename header
              readfile($path);
              die();
              exit;

            }

}
