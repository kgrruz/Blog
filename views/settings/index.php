<div class="row">
 <div class="col-md-7">
     <legend><?php echo lang('blog_settings'); ?></legend>

     <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>


     <div class="form-group row<?php echo form_error('post_visibility') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="post_visibility"><?php echo lang("blog_settings_post_visibility"); ?></label>
         <div class="col-sm-8">
           <label class="form-check-label" for="week_account">
               <input type="radio" class="form-check-input" name="post_visibility"  value="1"  />
               <?php echo lang("blog_settings_only_logged"); ?>
           </label>
           <label class="form-check-label" for="week_account">
               <input type="radio" class="form-check-input" name="post_visibility"  value="0" />
               <?php echo lang("blog_settings_post_public"); ?>
           </label>
         </div>
     </div>
     <div class="form-group row<?php echo form_error('new_comment') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="new_comment"> <?php echo lang("blog_settings_email_enever"); ?></label>
         <div class="col-sm-8">
           <label class="form-check-label" for="new_comment">
               <input type="checkbox" class="form-check-input" name="email_new_comment" id="email_new_comment" value="1" />
                <?php echo lang("blog_settings_new_comments"); ?>
           </label>
           <label class="form-check-label" for="new_comment_mod">
               <input type="checkbox" class="form-check-input" name="email_new_comment_mod" id="email_new_comment_mod" value="1"  />
                <?php echo lang("blog_settings_new_comment_mod"); ?>
           </label>
         </div>
     </div>

     <div class="form-group row<?php echo form_error('must_aprove_comment') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="must_aprove_comment"><?php echo lang("blog_settings_before_comments_appear"); ?></label>
         <div class="col-sm-8">
           <label class="form-check-label" for="week_account">
               <input type="checkbox" class="form-check-input" name="must_aprove_comment" id="must_aprove_comment" value="1" />
                <?php echo lang("blog_settings_comment_must_be_approved"); ?>
           </label>
         </div>
     </div>

     <div class="form-group row<?php echo form_error('block_post_after') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="block_post_after"><?php echo lang("blog_settings_close_comments"); ?></label>
         <div class="col-sm-8">
         <div class="row">
         <div class="col-sm-6">
               <input type="number" class="form-control" name="block_post_after" id="block_post_after" />
           </div>
            <div class="col-sm-6">
              <select class="form-control" name="block_post_after_period" id="block_post_after_period">
                <option value="hour"><?php echo lang("blog_hour");?></option>
                <option value="day"><?php echo lang("blog_day");?></option>
                <option value="month"><?php echo lang("blog_month");?></option>
              </select>
         </div>
     </div>
     </div>
     </div>
     <div class="form-group row<?php echo form_error('comment_flood') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="comment_flood"><?php echo lang("blog_settings_comment_flood_time"); ?></label>
         <div class="col-sm-8">
         <div class="row">
           <div class="col-sm-6">
                 <input type="number" class="form-control" name="comment_flood" id="comment_flood" />
             </div>
              <div class="col-sm-6">
                <select class="form-control" name="comment_flood_period" id="bcomment_flood_period">
                  <option value="hour"><?php echo lang("blog_hour");?></option>
                  <option value="day"><?php echo lang("blog_day");?></option>
                  <option value="month"><?php echo lang("blog_month");?></option>
                </select>
           </div>
     </div>
     </div>
     </div>

     <div class='row'>
     <div class='col-sm-4'></div>
     <div class="col-sm-8">
         <input type='submit' name='save' class='btn btn-primary mx-0' value="<?php echo lang('bf_action_save'); ?>" />
     </div>
     </div>

     <?php echo form_close(); ?>


     </div>
     </div>
