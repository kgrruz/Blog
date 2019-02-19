<div class="row">
 <div class="col-md-7">
     <legend><?php echo lang('blog_settings'); ?></legend>

     <div class="form-group row<?php echo form_error('user_limit_sell') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="user_limit_sell"><?php echo lang("blog_settings_post_visibility"); ?></label>
         <div class="col-sm-8">
           <label class="form-check-label" for="week_account">
               <input type="radio" class="form-check-input" name="week_account" id="week_account" value="1" <?php echo set_checkbox('week_account', 1, isset($settings['market.week_account']) && $settings['market.week_account'] == 1); ?> />
               <?php echo lang("blog_settings_only_logged"); ?>
           </label>
           <label class="form-check-label" for="week_account">
               <input type="radio" class="form-check-input" name="week_account" id="week_account" value="1" <?php echo set_checkbox('week_account', 1, isset($settings['market.week_account']) && $settings['market.week_account'] == 1); ?> />
               <?php echo lang("blog_settings_post_public"); ?>
           </label>
         </div>
     </div>
     <div class="form-group row<?php echo form_error('user_limit_sell') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="user_limit_sell"> <?php echo lang("blog_settings_email_enever"); ?></label>
         <div class="col-sm-8">
           <label class="form-check-label" for="week_account">
               <input type="checkbox" class="form-check-input" name="week_account" id="week_account" value="1" <?php echo set_checkbox('week_account', 1, isset($settings['market.week_account']) && $settings['market.week_account'] == 1); ?> />
                <?php echo lang("blog_settings_new_comments"); ?>
           </label>
           <label class="form-check-label" for="week_account">
               <input type="checkbox" class="form-check-input" name="week_account" id="week_account" value="1" <?php echo set_checkbox('week_account', 1, isset($settings['market.week_account']) && $settings['market.week_account'] == 1); ?> />
                <?php echo lang("blog_settings_new_comment_mod"); ?>
           </label>
         </div>
     </div>

     <div class="form-group row<?php echo form_error('user_limit_sell') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="user_limit_sell"><?php echo lang("blog_settings_before_comments_appear"); ?></label>
         <div class="col-sm-8">
           <label class="form-check-label" for="week_account">
               <input type="checkbox" class="form-check-input" name="week_account" id="week_account" value="1" <?php echo set_checkbox('week_account', 1, isset($settings['market.week_account']) && $settings['market.week_account'] == 1); ?> />
                <?php echo lang("blog_settings_comment_must_be_approved"); ?>
           </label>
         </div>
     </div>

     <div class="form-group row<?php echo form_error('user_limit_sell') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="user_limit_sell"><?php echo lang("blog_settings_close_comments"); ?></label>
         <div class="col-sm-8">
         <div class="row">
         <div class="col-sm-6">
               <select class="form-check-input" name="week_account" id="week_account">
                 <option value=""></option>
               </select>
           </div>
            <div class="col-sm-6">
              <select class="form-check-input" name="week_account" id="week_account">
                <option value=""></option>
              </select>
         </div>
     </div>
     </div>
     </div>
     <div class="form-group row<?php echo form_error('user_limit_sell') ? $errorClass : ''; ?>">
         <label class="col-sm-4 col-form-label" for="user_limit_sell"><?php echo lang("blog_settings_comment_flood_time"); ?></label>
         <div class="col-sm-8">
         <div class="row">
         <div class="col-sm-6">
               <select class="form-check-input" name="week_account" id="week_account">
                 <option value=""></option>
               </select>
           </div>
            <div class="col-sm-6">
              <select class="form-check-input" name="week_account" id="week_account">
                <option value=""></option>
              </select>
         </div>
     </div>
     </div>
     </div>


     </div>
     </div>
