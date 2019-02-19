
<div class="card">
        <?php echo form_open_multipart($this->uri->uri_string(), 'class="form-horizontal"'); ?>
<div class="card-header"><?php echo $toolbar_title; ?></div>
<div class="card-body">

      <?php echo lang('category_desc_form_create'); ?>



    <?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-danger'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('items_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;


?>
<div class="row">
  <div class="col-md-5">

    <div class="form-group<?php echo form_error('name_category') ? ' error' : ''; ?>">
        <?php echo form_label(lang('category_field_name_category') . lang('bf_form_label_required'), 'name_category', array('class' => 'control-label')); ?>

            <input id='name_category' type='text' class="form-control form-control-sm" required='required' name='name_category' maxlength='255' value="<?php echo set_value('name_category', isset($category->name_category) ? $category->name_category : ''); ?>" />
            <span class='help-inline'><?php echo form_error('category_field_name_category'); ?></span>

    </div>

		<div class="form-group<?php echo form_error('desc_category') ? ' error' : ''; ?>">
        <?php echo form_label(lang('category_field_category_description'), 'desc_category', array('class' => 'control-label')); ?>

            <textarea id='desc_category' class="form-control form-control-sm" name='desc_category'  ><?php if(isset($category)){ echo $category->desc_category; } ?></textarea>
            <span class='help-inline'><?php echo form_error('category_field_category_description'); ?></span>

    </div>

    <div class="form-group<?php echo form_error('parent_category') ? ' error' : ''; ?>">
        <?php echo form_label(lang('category_field_parent_category') . lang('bf_form_label_required'), 'parent_category', array('class' => 'control-label')); ?>

            <select id='parent_category' class="form-control form-control-sm" name='parent_category'  >
            <?php foreach($tree['items'] as $groupp){ ?>
            <option <?php echo (isset($category) and $category->parent_category == $groupp['id_category'])? 'selected':''; ?>
               value="<?php echo $groupp['id_category']; ?>" >
            <?php echo str_repeat('-', $this->nested_set->getNodeLevel($groupp)*4); ?>
            <?php echo ucfirst($groupp['name_category']); ?>
            </option>
          <?php } ?>
            </select>
            <span class='help-inline'><?php echo form_error('category_field_parent_category'); ?></span>

    </div>

  </div>
  </div>
  </div>

<div class="card-footer">

      <input type='submit' name='save' class='btn btn-sm btn-primary' value="<?php echo (empty($id))? lang('category_action_create'):lang('category_action_edit'); ?>" />
      <?php echo lang('bf_or'); ?>
      <?php echo anchor('item/category', lang('category_cancel'), 'class="btn btn-sm btn-warning"'); ?>
</div>

<?php echo form_close(); ?>

</div>
