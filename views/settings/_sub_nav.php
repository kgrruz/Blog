<?php

$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/settings/blog';

?>
<ul class='nav nav-tabs flex-column card-header-tabs flex-sm-row '>

	<li<?php echo $checkSegment == '' ? ' class="nav-item"' : ''; ?>>
		<a class="nav-link <?php echo check_url('admin/settings/blog',true); ?>" href="<?php echo site_url($areaUrl); ?>" id='blog_settings'>
            <?php echo lang('blog_settings'); ?>
        </a>
	</li>
</ul>
