<div class="card">
  <div class="card-header"><?php echo lang('blog_comments'); ?></div>
<?php if($comments){ ?>
<div class="table-responsive">
<table class="table table-hover table-sm">
  <thead>
    <tr>
      <th class="pl-3"><?php echo lang("blog_column_created_by"); ?></th>
      <th><?php echo lang("blog_column_name"); ?></th>
      <th><?php echo lang("blog_column_created"); ?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($comments as $comment){ ?>
    <tr class="bg-light">
      <td class="pl-3"><?php echo user_avatar($comment->photo_avatar,$comment->email,30,'rounded mr-2',true,'thumbs'); ?>  <?php echo anchor($comment->username,$comment->display_name); ?></td>
      <td><?php echo anchor("blog/post/".$comment->slug_post,ellipsize($comment->title_post,80)); ?></td>
      <td><?php echo ut_date($comment->created,$current_user->d_format.' '.$current_user->t_format); ?></td>
       <td rowspan="2">
        <div class="btn-group btn-group-sm" role="group" >
          <?php echo ($this->settings_lib->item("blog.must_aprove_comment") and $comment->approved == 0)? anchor('blog/content/approve_comment/'.$comment->id,'<i class="fa fa-thumbs-up"></i>','data-message="'.lang("blog_comment_approve_confirm").'" class="btn btn-success exc_bot"'):''; ?>
          <?php echo anchor('blog/content/delete_comment/'.$comment->id,'<i class="fa fa-trash"></i>','data-message="'.lang("blog_comment_delete_confirm").'" class="btn btn-light exc_bot"'); ?>
        </div>
      </td>
    </tr>
    <tr>
      <td class="pl-3" colspan="3">
      <?php if($comment->file_url){
        echo anchor($comment->file_url,"<i class='fa fa-file'></i> ".lang("blog_comment_file"),"target='_blank'");
      }else{
        echo $comment->content;
      } ?>
    </td></tr>
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
