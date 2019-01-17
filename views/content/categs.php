
      <div class="card">
        <div class="card-header">
          <?php echo lang('category_area_title'); ?>
        </div>

<?php if($tree['items']){ ?>

<?php echo form_open(); ?>

      <div class="table-responsive">
      <table id="table_categories" class="table table-hover table-outline table-vcenter text-nowrap mb-0" >
    <thead>
        <tr>

<th></th>
            <th><?php echo lang('category_column_name'); ?></th>
              <th></th>
            <th class="text-center" ><?php echo lang('category_column_items'); ?></th>

            <th><?php echo lang('category_column_created'); ?></th>

            <th></th>
        </tr>
    </thead>
    <tbody>


      <?php foreach($tree['items'] as $category){ ?>
      <tr>
          <td class="pl-3"><?php if($category['id_category'] != 1){ ?>
          <input type="checkbox" name="checked[]" value="<?php echo $category['id_category']; ?>" />
        <?php } ?>
       </td>

        <td>
      <?php echo str_repeat('&nbsp;', $this->nested_set->getNodeLevel($category)*4); ?>
        <?php echo anchor('blog/category/profile/'.$category['slug_category'],$category['name_category']); ?></td>
        <td><?php echo ellipsize($category['desc_category'],60); ?></td>
        <td class="text-center"><span class="badge badge-pill badge-primary"><?php echo $this->category_model->count_items_in_categ($category['id_category']); ?></span></td>
        <td><?php echo date('d/m/Y',strtotime($category['created_on'])); ?></td>
        <td>
          <?php if($category['id_category'] != 1){ ?>
          <?php echo anchor('blog/category/edit/'.$category['slug_category'],'<i class="fa fa-edit" aria-hidden="true"></i>
      ','class="btn btn-sm btn-secondary"'); ?>  <?php } ?></td>
      </tr>
      <?php } ?>

    </tbody>

    <tfoot>
      <tr><td></td>
      <td colspan="6"><?php
      echo lang('bf_with_selected'); ?>

      <input type="submit" name="delete" class="btn  btn-sm btn-danger" id="delete-me" value="<?php echo lang('bf_action_delete'); ?>" onclick="return confirm('<?php e(js_escape(lang('category_delete_confirm'))); ?>')" />
</td>
</tr>
</tfoot>
</table>


</div>

<?php echo form_close(); ?>

<?php } else{ ?>
  <div class="card-body">
    <?php echo lang('category_empty'); ?>
  </div>
<?php } ?>


</div>
