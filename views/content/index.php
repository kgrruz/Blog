<div class="card">
  <div class="card-header"><?php echo lang('blog_area_title'); ?></div>

<div class="table-responsive">
<table class="table table-hover table-sm">
  <thead>
    <tr>
      <th>Title</th>
      <th>Categ</th>
      <th>Enable coments</th>
      <th>Enable attach</th>
      <th>Crated by</th>
      <th>Created on</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($posts as $post){ ?>
    <tr>
      <td><?php echo anchor("blog/post/".$post->slug_post,ellipsize($post->title_post,30)); ?></td>
      <td></td>
      <td><?php echo ($post->enable_comments)?'<i class="fa fa-check"></i>':'';  ?></td>
      <td><?php echo ($post->enable_attach)?'<i class="fa fa-check"></i>':''; ?></td>
      <td><?php echo anchor($post->username,$post->display_name); ?></td>
      <td><?php echo ut_date($post->created_on,$current_user->d_format.' '.$current_user->t_format); ?></td>
      <td>
        <div class="btn-group" role="group" >
          <?php echo anchor('blog/content/edit/'.$post->id_post,'<i class="fa fa-edit"></i>','class="btn btn-light"'); ?>
          <?php echo anchor('blog/content/delete/'.$post->id_post,'<i class="fa fa-trash"></i>','class="btn btn-light"'); ?>
        </div>
      </td>
    </tr>
  <?php } ?>
</tbody>
</table>
</div>

</div>
