<?php echo lang("blog_subject_new_comment"); ?> <?php echo anchor("blog/post/".$blog_post->slug_post,$blog_post->title_post); ?>
<p>
  <i><?php echo $comment['content']; ?></i>
</p>
