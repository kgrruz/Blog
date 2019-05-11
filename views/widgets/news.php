<div class="card mb-3">
  <div class="card-header">
    <?php echo lang("blog_news"); ?>
  </div>

  <?php if($posts){ ?>

  <ul class="list-group list-group-flush">

<?php foreach($posts as $post){ ?>
  <li class="list-group-item">
     <div class="media">
       <img class="mr-3" style="height:67px;width:100px" src="./uploads/blog/posts_preview/<?php echo ($img = $post->preview_image)?$img:'no_image.png'; ?>" alt="image_preview">
      <div class="media-body">
        <h5 class="mt-0"><?php echo anchor('blog/post/'.$post->slug_post, ellipsize($post->title_post,35)); ?></h5>
        <?php echo ut_date($post->created_on,'F j, Y H:i'); ?>
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
