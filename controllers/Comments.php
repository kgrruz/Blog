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

        }

					public function getComments(){

							if (!$this->input->is_ajax_request()) {
									exit('No direct script access allowed');
							}

              $this->authenticate();

							$qp = $this->uri->segment(4);
							$id_user = $this->session->userdata('user_id');

							$records = $this->comments_model->get_comments($id_user,$qp);


							foreach ($records as $result) {
									$result->created_by_current_user = (bool) $result->created_by_current_user;
									$result->parent = ($result->parent == 0)? null:$result->parent;
									$result->profile_picture_url = base_url().'images/'.$result->profile_picture_url.'?width=50&module=users&assets=assets/images/users/thumbs';
							}


							$this->output->set_output(json_encode($records));
					}


					public function postComments()
					{
							if (!$this->input->is_ajax_request()) {
									exit('No direct script access allowed');
							}

              $this->authenticate();

							$this->load->config('blog/comments');

              if(!$this->blog_model->check_enable_comment($this->input->post('qp'))){ exit(json_encode(array('status'=>false,'message'=>'lang:blog_block_comment')));  }

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

          $post['fullname'] = $this->current_user->display_name;
          $post['created_by_current_user'] = true;
          $post['profile_picture_url'] = $this->input->post('profile_picture_url');

          $this->output->set_output(json_encode($post));

				}


					public function deleteComments()
					{

            if (!$this->input->is_ajax_request()) {
                exit('No direct script access allowed');
            }

            $this->authenticate();

							if ($this->input->post('id')) {
									$this->db->where('id', $this->input->post('id'));
									$this->db->delete('blog_comments');
							}

							$this->output->set_output(json_encode(array('status'=>true)));
					}



					public function uploadAttachments(){

            if (!$this->input->is_ajax_request()) {
                exit('No direct script access allowed');
            }

            $this->authenticate();

            if(!$this->blog_model->check_enable_attach($this->input->post('qp'))){ exit(json_encode(array('status'=>false,'message'=>'lang:blog_block_attach')));  }

							if (!empty($_FILES['file']['name'])) {

                      $path = Modules::path('blog','uploads/comments');

											$config['upload_path'] =  $path;
											$config['allowed_types'] = 'doc|pdf|jpeg|png|gif|jpg|zip|rar';
											$config['max_size']     = '10240';
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
												'file_url'=> base_url().'blog/comments/attach/'.$upload_data['file_name'],
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

              $this->authenticate();

              $this->load->helper('file');
              $path = Modules::path('blog','uploads/comments').'/'.$file;

              header('Content-Type: '.get_mime_by_extension($file));
              header('Content-length: '.filesize($path));
              header('Content-Disposition: inline; filename="'.$file.'";'); //<-- sends filename header
              readfile($path);
              die();
              exit;

            }

}
