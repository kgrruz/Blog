


    <!-- Page Content -->
    <div class="container-fluid">

      <div class="row">

        <!-- Post Content Column -->
        <div class="col-lg-8 pt-0 mt-4">

          <div class="card pt-0">
          <div class="card-body pt-0 pb-0">

          <h1 class="mt-4"><?php echo ucfirst($post->title_post); ?></h1>

          <!-- Author -->
          <p class="lead">
           <?php echo ut_date($post->created_on,'F j, Y H:i'); ?>
      <?php echo user_avatar($post->photo_avatar,$post->email,20,'rounded thumb_nav',true,'thumbs'); ?>  <?php echo anchor('partner/'.$post->username,$post->display_name); ?>

      <?php foreach($categs_post as $categ){ ?>
      <span class="badge badge-info"><?php echo $categ->name_category; ?></span>
    <?php } ?>

          </p>

          <hr>

          <!-- Post Content -->
          <div class="lead post_body"><?php echo html_entity_decode($post->body_post); ?></div>
        </div>
        </div>

<?php if($post->enable_comments){ ?>
          <!-- Comments Form -->
          <div class="card my-4">
            <h5 class="card-header">Deixe seu comentário:</h5>
            <div class="card-body">

<div id="comments-container"> </div>

            </div>
          </div>
<?php } ?>


        </div>


        <!-- Sidebar Widgets Column -->
        <div class="col-md-4">

          <!-- Search Widget -->
          <div class="card my-4">
            <h5 class="card-header">Procurar</h5>
            <div class="card-body">
              <?php echo form_open('blog/index'); ?>
              <div class="input-group">
                <input type="text" class="form-control rounded-0" maxlength="20"  name="search" placeholder="Procurar por...">
                <span class="input-group-btn">
                  <button class="btn btn-success rounded-0" type="submit"><?php echo lang('bf_search'); ?></button>
                </span>
              </div>
              <?php echo form_close(); ?>
            </div>
          </div>

          <!-- Categories Widget -->
          <div class="card my-4">
            <h5 class="card-header"><?php echo lang('category_area_title'); ?></h5>

                 <ul class="list-group list-group-flush">
                    <?php foreach($tree['items'] as $groupp){ ?>
               <li class="list-group-item">
                    <?php echo str_repeat('&nbsp', $this->nested_set->getNodeLevel($groupp)*4); ?>
                    <?php echo anchor('blog/categp/'.$groupp['id_category'],ucfirst($groupp['name_category'])); ?>
                  </li>
                  <?php } ?>
                </ul>

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
