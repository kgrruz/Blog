<ul class="nav nav-tabs card-header-tabs">
  <li class="nav-item">
   <?php echo anchor('blog',lang('blog_area_title'),'class="nav-link '.check_method('index',true).'"'); ?>
  </li>
  <li class="nav-item">
      <?php echo anchor('blog/create',lang('bf_action_create'),'class="nav-link '.check_url('blog/create',true).'"'); ?>
  </li>

</ul>
