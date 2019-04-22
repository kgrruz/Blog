
      <?php echo form_open_multipart($this->uri->uri_string(), 'class="form-horizontal"'); ?>

    <?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-danger'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('blog_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($post->id_post) ? $post->id_post : '';

?>
<div class="row">
  <div class="col-md-8">

<div class="card">
<div class="card-body">

            <div class="form-group<?php echo form_error('title_post') ? ' error' : ''; ?>">
                <?php echo form_label(lang('blog_field_name') . lang('bf_form_label_required'), 'title_post', array('class' => 'control-label')); ?>

                    <input id='title_post' type='text' class="form-control" required='required' name='title_post' maxlength='255' value="<?php echo set_value('title_post', isset($post->title_post) ? $post->title_post : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('title_post'); ?></span>

            </div>


            <div class="form-group<?php echo form_error('category') ? ' error' : ''; ?>">
                <?php echo form_label(lang('blog_field_category') . lang('bf_form_label_required'), 'category', array('class' => 'col-form-label')); ?>

      <select id='category' class="form-control form-control-sm"  name='category[]'  >
            <?php foreach($tree['items'] as $groupp){ ?>
            <option <?php echo (isset($group) and $group->parent_category == $groupp['id_category'])? 'selected':($groupp['id_category'] == 1)? 'selected':''; ?>
               value="<?php echo $groupp['id_category']; ?>" >
            <?php echo str_repeat('-', $this->nested_set->getNodeLevel($groupp)*4); ?>
            <?php echo ucfirst($groupp['name_category']); ?>
            </option>
          <?php } ?>
      </select>
                    <span class='help-inline'><?php echo form_error('category'); ?></span>
            </div>

<div class="form-group">
  <input type="checkbox" value="1" name="enable_comments" <?php echo (isset($post) && $post->enable_comments)? 'checked':''; ?> >   <?php echo lang('blog_enable_comments'); ?>
</div>
<div class="form-group">
  <input type="checkbox" value="1" name="enable_attach" <?php echo (isset($post) && $post->enable_attach)? 'checked':''; ?> >   <?php echo lang('blog_enable_attach'); ?>
  <?php if(!is_really_writable(Modules::path('blog','assets/images/posts_body/'))){ ?>
    <div class="text-danger"><?php echo lang("blog_permission_folder"); ?> <?php echo Modules::path('blog','assets/images/posts_body/'); ?></div>
  <?php } ?>
</div>

            <div class="form-group<?php echo form_error('body_post') ? ' error' : ''; ?>">
                <?php echo form_label(lang('blog_field_description') . lang('bf_form_label_required'), 'body_post', array('class' => 'control-label')); ?>

                <textarea class="input-block-level" id="post_editor" name="body_post" ><?php echo (isset($post))? $post->body_post:''; ?></textarea>
                                    <span class='help-inline'><?php echo form_error('body_post'); ?></span>


            </div>


                      <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('blog_action_create'); ?>" />
                      <?php echo lang('bf_or'); ?>
                      <?php echo anchor('blog', lang('blog_cancel'), 'class="btn btn-warning"'); ?>

                    </div>
                    </div>
                    </div>




        <div class="col-md-4">

          <div class="card mb-3">
            <div class="card-header">
              <?php echo lang('blog_roles'); ?>
            </div>
            <div class="card-body">

              <?php foreach ($roles as $role) : ?>
      <input type="checkbox" name="roles_access[]" <?php echo (!empty($id) and in_array($role->role_id,explode(",",$post->roles_access)) or $current_user->role_id == $role->role_id)? 'checked':''; ?> value="<?php echo $role->role_id; ?>" ><?php echo $role->role_name; ?>
          <?php endforeach; ?>

            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <?php echo lang('blog_field_picture'); ?>
            </div>
            <div class="card-body">

              <label for="exampleFormControlFile1" style="display:none" class="btn btn-primary btn-sm" ><i class="fa fa-image"></i> <?php echo lang('blog_field_upload_picture'); ?></label>
              <input type="file" class="form-control-file" xid="exampleFormControlFile1" name="preview_image">
                <?php if(!empty($id)){ ?>
                  <div class="text-danger"><?php echo lang("blog_image_preview_edit_overwrite"); ?></div>
                <?php } ?>
                <?php if(!is_really_writable(Modules::path('blog','assets/images/posts_preview/'))){ ?>
                  <div class="text-danger"><?php echo lang("blog_permission_folder"); ?> <?php echo Modules::path('blog','assets/images/posts_preview/'); ?></div>
                <?php } ?>
            </div>
          </div>
        </div>

    </div>

    <?php echo form_close(); ?>
