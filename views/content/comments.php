<div class="card">
  <div class="card-header"><?php echo lang('blog_comments'); ?></div>
<?php if($comments){ ?>
<div class="table-responsive">
<table class="table table-hover table-sm">
  <tbody>
    <?php foreach($comments as $comment){ ?>
    <tr class="bg-light">
      <td class="pl-3">

        <div class="media">
          <?php echo user_avatar($comment->photo_avatar,$comment->email,36,'rounded mr-3',true,'thumbs'); ?>
          <div class="media-body text-break">
            <h6 class="my-0 mb-1"><?php echo anchor($comment->username,$comment->display_name); ?></h6>

            <?php echo ($comment->file_url)? anchor($comment->file_url,"<i class='fa fa-file'></i> ".lang("blog_comment_file"),"target='_blank'"):$comment->content; ?>

            <div class="d-block">
              <small><?php echo anchor("blog/post/".$comment->slug_post,ellipsize($comment->title_post,80)); ?></small> - <small class="text-muted"><?php echo ut_date($comment->created,$current_user->d_format.' '.$current_user->t_format); ?></small>
               <div class="btn-group btn-group-sm" role="group" >
                <?php echo ($this->settings_lib->item("blog.must_aprove_comment") and $comment->approved == 0)? anchor('blog/content/approve_comment/'.$comment->id,'<i class="fa fa-thumbs-up"></i>','data-message="'.lang("blog_comment_approve_confirm").'" class="btn btn-success exc_bot"'):''; ?>
                <?php echo anchor('blog/content/delete_comment/'.$comment->id,'<i class="fa fa-trash"></i>','data-message="'.lang("blog_comment_delete_confirm").'" class="btn btn-light exc_bot"'); ?>
              </div>
              </div>
          </div>
        </div>

         </td>

    </tr>

  <?php } ?>
</tbody>
</table>
</div>
<?php if($pags = $this->pagination->create_links()){ ?>
  <div class="card-footer">
  <ul class="pagination">
    <?php echo $pags; ?>
  </ul>
</div>
<?php } ?>
<?php } else{ ?>

   <div class="card-body">
     <div class="card-text"><?php echo lang('blog_empty_posts'); ?></div>
   </div>

<?php } ?>

</div>
