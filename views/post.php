


    <!-- Page Content -->
    <div class="container-fluid">

      <div class="row">

        <!-- Post Content Column -->
        <div class="col-lg-8 pt-0">

          <div class="card pt-0">
          <div class="card-body pt-0 pb-0">

          <h1 class="mt-4"><?php echo ucfirst($post->title_post); ?></h1>

          <!-- Author -->
          <p class="lead">
           <?php echo ut_date($post->created_on,'F j, Y H:i'); ?>
      <?php echo user_avatar($post->photo_avatar,$post->email,20,'rounded thumb_nav',true,'thumbs'); ?>  <?php echo anchor($post->username,$post->display_name); ?>

      <?php foreach($categs_post as $categ){ ?>
      <span class="badge badge-info"><?php echo $categ->name_category; ?></span>
    <?php } ?>

          </p>

          <hr>

          <!-- Post Content -->
          <div class="lead post_body"><?php echo $this->htmlfixer->getFixedHtml(html_entity_decode($post->body_post)); ?></div>
        </div>
        </div>

<?php if($post->enable_comments){ ?>
          <!-- Comments Form -->
          <div class="card my-4">
            <h5 class="card-header"><?php echo lang('blog_lets_comment'); ?></h5>
            <div class="card-body">

<div id="comments-container"> </div>

            </div>
          </div>
<?php } ?>


        </div>


        <!-- Sidebar Widgets Column -->
        <div class="col-md-4">

          <!-- Search Widget -->
          <div class="card">
            <h5 class="card-header"><?php echo lang('blog_search'); ?></h5>
            <div class="card-body">
              <?php echo form_open('blog/index'); ?>
              <div class="input-group">
                <input type="text" class="form-control rounded-0" maxlength="20" name="search" placeholder="<?php echo lang('blog_search_placeholder'); ?>">
                <span class="input-group-btn">
                  <button class="btn btn-success rounded-0" type="submit"><?php echo lang('blog_search'); ?></button>
                </span>
              </div>
              <?php echo form_close(); ?>
            </div>
          </div>

          <!-- Categories Widget -->
          <div class="card my-4">
            <h5 class="card-header"><?php echo lang('category_area_title'); ?></h5>
                 <?php if($tree['items']){ ?>
                   <div class="list-group list-group-flush">
                      <?php foreach($tree['items'] as $groupp){ ?>
                <a href="<?php echo base_url().'blog/categp/'.$groupp['slug_category']; ?>" class="list-group-item d-flex justify-content-between align-items-center <?php echo check_url('blog/categp/'.$groupp['slug_category'],true); ?>">
                      <?php echo str_repeat('&nbsp', $this->nested_set->getNodeLevel($groupp)*4); ?>
                      <?php echo ucfirst($groupp['name_category']); ?>
                    </a>
                    <?php } ?>
                  </div>
              <?php } else{ ?>
                <div class="card-body">
                  <?php echo lang("category_empty"); ?>
                </div>
              <?php } ?>
            </div>
          </div>

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->
<script>
var enable = <?php echo ($post->enable_comments)? 'true':'false'; ?>;
var uid = <?php echo $current_user->id; ?>;
var id_refer = <?php echo $post->id_post; ?>;
var author = <?php echo $post->created_by; ?>;
var enable_attach = <?php echo ($post->enable_attach)? 'true':'false'; ?>;

</script>
