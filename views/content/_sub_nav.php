<?php

$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/blog/content';

?>
<ul class='nav nav-tabs flex-column card-header-tabs flex-sm-row '>
	<li class="nav-item dropdown">
	 <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo lang('blog_area_title'); ?></a>
	 <div class="dropdown-menu">
		 <?php echo anchor('blog/content/',lang('blog_area_title'),'class="dropdown-item '.check_url('blog/content',true).'"'); ?>
		 <?php echo anchor('blog/content/create',lang('blog_action_create'),'class="dropdown-item '.check_url('blog/content/create',true).'"'); ?>
	 </div>
	</li>
	<li class="nav-item dropdown">
	 <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo lang('category_area_title'); ?></a>
	 <div class="dropdown-menu">
		 <?php echo anchor('blog/content/categs',lang('category_area_title'),'class="dropdown-item '.check_url('blog/content/categs',true).'"'); ?>
		 <?php echo anchor('blog/content/create_category',lang('category_action_create'),'class="dropdown-item '.check_url('blog/content/create_category',true).'"'); ?>
	 </div>
	</li>
	<li class="nav-item">
		 <?php echo anchor('blog/content/comments',lang('blog_comments'),'class="nav-link '.check_url('blog/content/comments',true).'"'); ?>
	</li>
</ul>
