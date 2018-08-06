

   <div class="row">

  <!-- Blog Entries Column -->
       <div class="col-md-8">

         <h1><?php echo lang('blog_area_title'); ?> <?php echo html_escape($this->settings_lib->item('site.title')); ?>
           <small><?php echo lang('blog_list'); ?></small>
         </h1>

    <?php if($posts){ foreach($posts as $post){ ?>

         <div class="card mb-4">
          <?php if($post->preview_image){ ?>
					 <img class="card-img-top img-fluid" src="<?php echo base_url(); ?>images/<?php echo $post->preview_image; ?>?module=blog&assets=assets/images/posts_preview" alt="image_preview">
				 <?php } ?>
				  <div class="card-body">
             <h2 class="card-title mb-0"><?php echo anchor('blog/post/'.$post->slug_post, $post->title_post); ?></h2>
           </div>
           <div class="card-footer text-muted">
             <?php echo ut_date($post->created_on,'F j, Y H:i'); ?>
        <?php echo user_avatar($post->photo_avatar,$post->email,20,'rounded thumb_nav',true,'thumbs'); ?>  <?php echo anchor('partner/'.$post->username,$post->display_name); ?>

           </div>
         </div>

       <?php } ?>

         <!-- Pagination -->
         <ul class="pagination justify-content-center mb-4">
           <?php echo $this->pagination->create_links(); ?>
         </ul>
     <?php } else{ ?>
  <div class="card border-0">
        <div class="card-body">
          <div class="card-text"><?php echo lang('blog_empty_posts'); ?></div>
        </div>
      </div>
    <?php } ?>
       </div>


       <!-- Sidebar Widgets Column -->
       <div class="col-md-4" style="position: sticky">

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

                 <ul class="list-group list-group-flush">
                    <?php foreach($tree['items'] as $groupp){ ?>
               <li class="list-group-item <?php echo check_url('blog/categp/'.$groupp['slug_category'],true); ?>">
                    <?php echo str_repeat('&nbsp', $this->nested_set->getNodeLevel($groupp)*4); ?>
                    <?php echo anchor('blog/categp/'.$groupp['slug_category'],ucfirst($groupp['name_category'])); ?>
                  </li>
                  <?php } ?>
                </ul>
               </div>




       </div>
       </div>
