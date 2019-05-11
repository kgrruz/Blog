<div class="card">
     <div class="card-header"><?php echo lang('blog_settings'); ?></div>



     <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>

     <div class="card-body">
    <div class="col-md-7">

     <div class="form-group row<?php echo form_error('post_visibility') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="post_visibility"><?php echo lang("blog_settings_post_visibility"); ?></label>
         <div class="col-sm-8">
           <div class="form-check">
               <input type="radio" class="form-check-input" <?php echo ($settings['blog.post_visibility'] == 1)? "checked":""; ?> name="post_visibility"  value="1"  />
                    <label class="form-check-label" for="week_account"><?php echo lang("blog_settings_only_logged"); ?>
           </label>
         </div>
            <div class="form-check">
               <input type="radio" class="form-check-input" <?php echo ($settings['blog.post_visibility'] == 0)? "checked":""; ?> name="post_visibility"  value="0" />
                 <label class="form-check-label" for="week_account">   <?php echo lang("blog_settings_post_public"); ?>
           </label>
         </div>
         </div>
     </div>
     <div class="form-group row<?php echo form_error('new_comment') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="new_comment"> <?php echo lang("blog_settings_email_enever"); ?></label>
         <div class="col-sm-8">
                 <div class="form-check">

               <input type="checkbox" class="form-check-input" name="email_new_comment" <?php echo ($settings['blog.email_new_comment'] == 1)? "checked":""; ?> id="email_new_comment" value="1" />
                   <label class="form-check-label" for="new_comment">  <?php echo lang("blog_settings_new_comments"); ?>
           </label>
         </div>
                 <div class="form-check">

               <input type="checkbox" class="form-check-input" name="email_new_comment_mod"<?php echo ($settings['blog.email_new_comment_mod'] == 1)? "checked":""; ?> id="email_new_comment_mod" value="1"  />
                 <label class="form-check-label" for="new_comment_mod">  <?php echo lang("blog_settings_new_comment_mod"); ?>
           </label>
         </div>
     </div>
     </div>

     <div class="form-group row<?php echo form_error('must_aprove_comment') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="must_aprove_comment"><?php echo lang("blog_settings_before_comments_appear"); ?></label>
         <div class="col-sm-8">
              <div class="form-check">

               <input type="checkbox" class="form-check-input" name="must_aprove_comment" id="must_aprove_comment" <?php echo ($settings['blog.must_aprove_comment'] == 1)? "checked":""; ?> value="1" />
                 <label class="form-check-label" for="week_account">  <?php echo lang("blog_settings_comment_must_be_approved"); ?>
           </label>
         </div>
     </div>
     </div>
     <?php $bloc_post = explode(" ",$settings['blog.block_post_after']); ?>
     <div class="form-group row<?php echo form_error('block_post_after') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="block_post_after"><?php echo lang("blog_settings_close_comments"); ?></label>
         <div class="col-sm-8">
         <div class="row">
         <div class="col-sm-6">
               <input type="number" class="form-control" min="1" value="<?php echo set_value('block_post_after', isset($bloc_post[0]) ? $bloc_post[0] : 1); ?>" name="block_post_after" id="block_post_after" />
           </div>
            <div class="col-sm-6">
              <select class="form-control" name="block_post_after_period" id="block_post_after_period">
                <option value="hour" <?php echo (isset($bloc_post[1]) and $bloc_post[1] == "hour")? "selected":""; ?> ><?php echo lang("blog_hour");?></option>
                <option value="day" <?php echo (isset($bloc_post[1]) and $bloc_post[1] == "day")? "selected":""; ?>><?php echo lang("blog_day");?></option>
                <option value="month" <?php echo (isset($bloc_post[1]) and $bloc_post[1] == "month")? "selected":""; ?> ><?php echo lang("blog_month");?></option>
              </select>
         </div>
     </div>
     </div>
     </div>
      <?php $comment_flood = explode(" ",$settings['blog.comment_flood']); ?>
     <div class="form-group row<?php echo form_error('comment_flood') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="comment_flood"><?php echo lang("blog_settings_comment_flood_time"); ?></label>
         <div class="col-sm-8">
         <div class="row">
           <div class="col-sm-6">
                 <input type="number" class="form-control" min="1" name="comment_flood" value="<?php echo set_value('comment_flood', isset($comment_flood[0]) ? $comment_flood[0] : 1); ?>"  id="comment_flood" />
             </div>
              <div class="col-sm-6">
                <select class="form-control" name="comment_flood_period" id="bcomment_flood_period">
                  <option value="minute" <?php echo (isset($comment_flood[1]) and $comment_flood[1] == "minute")? "selected":""; ?>><?php echo lang("blog_minute");?></option>
                  <option value="hour" <?php echo (isset($comment_flood[1]) and $comment_flood[1] == "hour")? "selected":""; ?>><?php echo lang("blog_hour");?></option>
                  <option value="day" <?php echo (isset($comment_flood[1]) and $comment_flood[1] == "day")? "selected":""; ?>><?php echo lang("blog_day");?></option>
                  <option value="month" <?php echo (isset($comment_flood[1]) and $comment_flood[1] == "month")? "selected":""; ?> ><?php echo lang("blog_month");?></option>
                </select>
           </div>
     </div>
     </div>
     </div>
     </div>
     </div>
<div class="card-footer">
  <div class="col-md-7">
     <div class='row'>
     <div class='col-sm-4'></div>
     <div class="col-sm-8">
         <input type='submit' name='save' class='btn btn-success mx-0' value="<?php echo lang('bf_action_save'); ?>" />
     </div>
     </div>
     </div>
     </div>

     <?php echo form_close(); ?>


     </div>
