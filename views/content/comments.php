<div class="card">
  <div class="card-header"><?php echo lang('blog_comments'); ?></div>

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
    <tr>
      <td class="pl-3"><?php echo anchor($comment->username,$comment->display_name); ?></td>
      <td><?php echo anchor("blog/post/".$comment->slug_post,ellipsize($comment->title_post,30)); ?></td>
      <td><?php echo relative_time(ut_date($comment->created)); ?></td>
      <td>
        <div class="btn-group btn-group-sm" role="group" >
          <?php echo anchor('blog/content/approve/'.$comment->id,'<i class="far fa-thumbs-up"></i>','class="btn btn-light"'); ?>
          <?php echo anchor('blog/content/delete/'.$comment->id,'<i class="fa fa-trash"></i>','data-message="'.lang("blog_delete_confirm").'" class="btn btn-light exc_bot"'); ?>
        </div>
      </td>
    </tr>
    <tr><td class="pl-3 bg-light" colspan="4"><?php echo $comment->content; ?></td></tr>
  <?php } ?>
</tbody>
</table>
</div>

</div>
