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

        $this->load->library('users/auth');
        $this->auth->restrict();
        $this->set_current_user();

				$this->load->model('blog/comments_model');

        }

					public function getComments(){

							if (!$this->input->is_ajax_request()) {
									exit('No direct script access allowed');
							}

							$qp = $this->uri->segment(4);
							$id_user = $this->session->userdata('user_id');

							$records = $this->db->query("select
						co_blog_comments.id as id,parent,created,modified,content,file_url,file_mime_type,creator,co_users.display_name as fullname,photo_avatar as profile_picture_url,
						created_by_admin,upvote_count,user_has_upvoted,
						IF(co_blog_comments.creator=$id_user,1,0) as created_by_current_user
						from co_blog_comments
						join co_users on co_blog_comments.creator = co_users.id
						where post_id = $qp")->result();


							foreach ($records as $result) {
									$result->created_by_current_user = (bool) $result->created_by_current_user;
									$result->profile_picture_url = base_url().'images/'.$result->profile_picture_url.'?width=50&module=users&assets=assets/images/users/thumbs';
							}


							$this->output->set_output(json_encode($records));
					}


					public function postComments()
					{
							if (!$this->input->is_ajax_request()) {
									exit('No direct script access allowed');
							}

							$this->load->config('blog/comments');

							$post = array(
						'modified'=>date('Y-m-d H:i:s'),
						'content'=>word_censor(strip_tags($this->input->post('content')), config_item('badwords'), '!#_+#@'),
						'post_id'=>$this->input->post('qp'),
						'creator'=>$this->session->userdata('user_id')
						);

							if ($this->input->post('parent') != '') {
									$post['parent'] = $this->input->post('parent');
							}

							if ($this->input->post('action') == 'insert') {
									$post['created'] =  date('Y-m-d H:i:s');

									$this->db->insert('co_blog_comments', $post);

									$insert_id = $this->db->insert_id();

									$post['id'] = $insert_id;


							$post['fullname'] = $this->current_user->display_name;
							$post['created_by_current_user'] = true;
							$post['profile_picture_url'] = $this->input->post('profile_picture_url');
							$this->output->set_output(json_encode($post));
					}
					}


					public function deleteComments()
					{
							if ($this->input->post('id')) {
									$this->db->where('id', $this->input->post('id'));
									$this->db->delete('co_blog_comments');
							}

							$this->output->set_output(json_encode(array('status'=>true)));
					}



					public function uploadAttachments()
					{
							if (!empty($_FILES['file']['name'])) {
									if ($this->user_model->check_limit_transfer()  == true) {
											$this->output->set_output(json_encode(array('status'=>false,"message"=>lang('reach_plan_limit'))));
									} else {
											$config['upload_path'] = './uploads/'.$this->path_file.'/comments';
											$config['allowed_types'] = 'doc|docx|xls|xlsx|pdf|jpeg|png|gif|jpg|zip|rar|7z';
											$config['max_size']     = '10240';
											$config['max_width'] = '1024';
											$config['max_height'] = '768';
											$config['encrypt_name'] = true;

											$this->load->library('upload', $config);

						 // Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
						 $this->upload->initialize($config);

											if (!$this->upload->do_upload('file')) {
													$error = array('error' => $this->upload->display_errors());
													Template::set_message($error['error'], 'error');
											} else {
													$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.



												$post = array(
												'created'=>date('Y-m-d H:i:s'),
												'modified'=>date('Y-m-d H:i:s'),
												'file_url'=> base_url().'uploads/'.$this->path_file.'/comments/'.$upload_data['file_name'],
												'file_mime_type'=>$upload_data['file_type'],
												'file_size'=>$upload_data['file_size'],
												'post_id'=>$this->input->post('qp'),
												'creator'=>$this->session->userdata('user_id')
												);

													if ($this->input->post('parent') != '') {
															$post['parent'] = $this->input->post('parent');
													}



													$this->db->insert('co_blog_comments', $post);
													$insert_id = $this->db->insert_id();
													$post['id'] = $insert_id;
													$post['fullname'] = $this->current_user->display_name;
													$post['created_by_current_user'] = true;
													$post['profile_picture_url'] = $this->input->post('profile_picture_url');

													$quote = $this->comment_model->find_with_comment($insert_id);

													$mess = '[quote_new_file_message]';

													if ($quote->created_by != $this->current_user->id) {
															$to = $quote->created_by;
															$mess = '[quote_new_file_message]';
													} elseif (isset($post['parent'])) {
															if ($this->comment_model->get_comment_autor($post['parent']) != $this->current_user->id) {
																	$to = $this->comment_model->get_comment_autor($post['parent']);
															}
															$mess = '[quote_answer_file_message]';
													}

													$message = $mess.' '. anchor('cotacao/'.alphaID($quote->id_quote, false, 11), ellipsize($quote->desc_quote, 40));

													$id_act = log_activity($this->auth->user_id(), $message, 'quote');

													if (isset($to)) {
															log_notify(array($to), $id_act);

															$this->notification_users->delivery_emails_users(array($to), $quote, '_emails/new_quote_message', 'quote/quote_mail', $mess.ellipsize($quote->desc_quote, 20));

															$this->notification_users->store_notification_queue('quote', array($to));
													}



													$this->output->set_output(json_encode($post));
											}
									}
							}
						}

}
