<div class="card my-3">
  <div class="card-header">
    <?php echo lang("blog_news"); ?>
  </div>

  <?php if($posts){ ?>

  <ul class="list-group list-group-flush">

<?php foreach($posts as $post){ ?>
  <li class="list-group-item">
     <div class="media">
       <img style="width:48px" class="mr-3 img-fluid rounded" src="<?php echo base_url(); ?>uploads/blog/posts_preview/thumbs/<?php echo ($img = $post->preview_image)?$img:'no_image.png'; ?>" alt="image_preview">
      <div class="media-body">
        <h5 class="mt-0"><?php echo anchor('blog/post/'.$post->slug_post, ellipsize($post->title_post,50)); ?></h5>
        <small class="text-muted"><?php echo ut_date($post->created_on,'F j, Y H:i'); ?></small>
       </div>
    </div>
</li>
   <?php } ?>
</ul>
<?php } else{ ?>
  <div class="card-body text-center">
      <p class="card-text"><?php echo lang("blog_no_records"); ?></p>
      </div>
    <?php } ?>
</div>
