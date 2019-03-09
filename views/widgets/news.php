<h4 class="mb-3"><?php echo lang("blog_news"); ?></h4>

<?php if($posts){ foreach($posts as $post){ ?>

     <div class="media mb-3">
       <img class="mr-3" src="<?php echo base_url(); ?>images/<?php echo $post->preview_image; ?>?module=blog&width=100&assets=assets/images/posts_preview" alt="image_preview">
      <div class="media-body">
        <h5 class="mt-0"><?php echo anchor('blog/post/'.$post->slug_post, $post->title_post); ?></h5>
        <?php echo ut_date($post->created_on,'F j, Y H:i'); ?>
       </div>
    </div>

   <?php } ?>

 <?php } ?>
