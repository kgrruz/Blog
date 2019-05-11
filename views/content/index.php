<div class="card">
  <div class="card-header"><?php echo lang('blog_area_title'); ?></div>
<?php if($posts){ ?>

<div class="table-responsive">
<table class="table table-hover table-sm">
  <thead>
    <tr>
      <th class="pl-3"><?php echo lang("blog_column_name"); ?></th>
      <th><?php echo lang("blog_column_categ"); ?></th>
      <th class="text-center"><?php echo lang("blog_column_ec"); ?></th>
      <th class="text-center"><?php echo lang("blog_column_ea"); ?></th>
      <th><?php echo lang("blog_column_created_by"); ?></th>
      <th><?php echo lang("blog_column_created"); ?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($posts as $post){ $categs_post = $this->category_model->get_blog_categories($post->id_post)->result(); ?>
    <tr>
      <td class="pl-3"><?php echo anchor("blog/post/".$post->slug_post,ellipsize($post->title_post,30)); ?></td>
      <td>    <?php foreach($categs_post as $categ){ ?>
          <span class="badge badge-info"><?php echo $categ->name_category; ?></span>
        <?php } ?></td>
      <td class="text-center"><?php echo ($post->enable_comments)?'<i class="fa fa-check"></i>':'<i class="fas fa-times"></i>';  ?></td>
      <td class="text-center"><?php echo ($post->enable_attach)?'<i class="fa fa-check"></i>':'<i class="fas fa-times"></i>'; ?></td>
      <td><?php echo user_avatar($post->photo_avatar,$post->email,30,'rounded mr-2',true,'thumbs'); ?> <?php echo anchor($post->username,$post->display_name); ?></td>
      <td><?php echo ut_date($post->created_on,$current_user->d_format.' '.$current_user->t_format); ?></td>
      <td>
        <div class="btn-group btn-group-sm" role="group" >
          <?php echo anchor('blog/content/edit/'.$post->id_post,'<i class="fa fa-edit"></i>','class="btn btn-light"'); ?>
          <?php echo anchor('blog/content/delete/'.$post->id_post,'<i class="fa fa-trash"></i>','data-message="'.lang("blog_delete_confirm").'" class="btn btn-light exc_bot"'); ?>
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
