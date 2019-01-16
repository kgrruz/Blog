<div class="container mt-3">
   <ul class="nav nav-pills flex-column flex-sm-row mb-3">
  <?php if($this->auth->has_permission('Blog.Content.View')){ ?>
  <li class="nav-item">
   <?php echo anchor('blog',lang('blog_area_title'),'class="nav-link '.check_url('blog',true).check_url('blog/index',true).'"'); ?>
  </li>
<?php } ?>
  <?php if($this->auth->has_permission('Blog.Content.Create')){ ?>
  <li class="nav-item">
      <?php echo anchor('blog/create',lang('blog_action_create'),'class="nav-link '.check_url('blog/create',true).'"'); ?>
  </li>

  <li class="nav-item dropdown">
   <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo lang('category_area_title'); ?></a>
   <div class="dropdown-menu">
     <?php echo anchor('blog/category/',lang('category_area_title'),'class="dropdown-item '.check_url('blog/category',true).'"'); ?>
     <?php echo anchor('blog/category/create',lang('category_action_create'),'class="dropdown-item '.check_url('blog/category/create',true).'"'); ?>
   </div>
  </li>

  <?php } ?>
</ul>

</div>
