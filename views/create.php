

<div class="card border-0">
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
  <div class="col-md-7">


            <div class="form-group<?php echo form_error('title_post') ? ' error' : ''; ?>">
                <?php echo form_label(lang('blog_field_name') . lang('bf_form_label_required'), 'title_post', array('class' => 'control-label')); ?>

                    <input id='title_post' type='text' class="form-control" required='required' name='title_post' maxlength='255' value="<?php echo set_value('title_post', isset($post->title_post) ? $post->title_post : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('title_post'); ?></span>

            </div>

            <div class="form-group<?php echo form_error('body_post') ? ' error' : ''; ?>">
                <?php echo form_label(lang('blog_field_description') . lang('bf_form_label_required'), 'body_post', array('class' => 'control-label')); ?>


<textarea class="input-block-level" id="summernote" name="body_post" rows="18"></textarea>
                    <span class='help-inline'><?php echo form_error('body_post'); ?></span>

            </div>
        </div>

        <div class="col-md-5">

          <div class="card">
            <div class="card-header">
              <?php echo lang('blog_field_picture'); ?>
            </div>
            <div class="card-body">

              <label for="exampleFormControlFile1" class="btn btn-primary btn-sm" ><i class="fa fa-image"></i> <?php echo lang('blog_field_upload_picture'); ?></label>
              <input type="file" class="form-control-file" name="preview_image" id="exampleFormControlFile1">

            </div>
          </div>



        </div>
        </div>




            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('blog_action_create'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor('blog', lang('blog_cancel'), 'class="btn btn-warning"'); ?>



    <?php echo form_close(); ?>


</div>



<script>


  </script>
