<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('products_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;

$id = isset($products->id_product) ? $products->id_product : '';

?>
<div class='admin-box'>
    <h3>Products</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
        <fieldset>
            

            <div class="control-group<?php echo form_error('product_name') ? ' error' : ''; ?>">
                <?php echo form_label(lang('products_field_product_name') . lang('bf_form_label_required'), 'product_name', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='product_name' type='text' required='required' name='product_name' maxlength='255' value="<?php echo set_value('product_name', isset($products->product_name) ? $products->product_name : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('product_name'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('product_description') ? ' error' : ''; ?>">
                <?php echo form_label(lang('products_field_product_description') . lang('bf_form_label_required'), 'product_description', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php echo form_textarea(array('name' => 'product_description', 'id' => 'product_description', 'rows' => '5', 'cols' => '80', 'value' => set_value('product_description', isset($products->product_description) ? $products->product_description : ''), 'required' => 'required')); ?>
                    <span class='help-inline'><?php echo form_error('product_description'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('product_price') ? ' error' : ''; ?>">
                <?php echo form_label(lang('products_field_product_price') . lang('bf_form_label_required'), 'product_price', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='product_price' type='text' required='required' name='product_price' maxlength='9' value="<?php echo set_value('product_price', isset($products->product_price) ? $products->product_price : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('product_price'); ?></span>
                </div>
            </div>
        </fieldset>
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="<?php echo lang('products_action_edit'); ?>" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor(SITE_AREA . '/developer/products', lang('products_cancel'), 'class="btn btn-warning"'); ?>
            
            <?php if ($this->auth->has_permission('Products.Developer.Delete')) : ?>
                <?php echo lang('bf_or'); ?>
                <button type='submit' name='delete' formnovalidate class='btn btn-danger' id='delete-me' onclick="return confirm('<?php e(js_escape(lang('products_delete_confirm'))); ?>');">
                    <span class='icon-trash icon-white'></span>&nbsp;<?php echo lang('products_delete_record'); ?>
                </button>
            <?php endif; ?>
        </fieldset>
    <?php echo form_close(); ?>
</div>