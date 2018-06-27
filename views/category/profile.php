<div class="container-fluid">

	<nav aria-label="breadcrumb" class="my-3" role="navigation">
<ol class="breadcrumb">
	<li class="breadcrumb-item"><?php echo anchor('item/category','categorias'); ?></li>
 <li class="breadcrumb-item active" aria-current="page"><?php echo $categ->name_category; ?></li>
</ol>
</nav>

<div class="row">

  <div class="col-md-3">

    <div class="card">

	<img src="<?php echo base_url().'images/'.$categ->image_category.'?module=item&assets=assets/images/categories/med'; ?>" class="card-img-top img-fluid w-100" id="previewing_profile" >

			<ul class="list-group list-group-flush">
		    <li class="list-group-item">

					<label for="uploadimage_category"  class="btn btn-success btn-block custom-file-upload_category pull-right"><?php echo lang('us_label_upload'); ?></label>
					<input id="uploadimage_category" name="category_pic" class="input_user_photo" type="file"/>

					<div id="photo_progress" class="progress">
						<div class="progress-bar bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><span class="indicator">25%</span></div>
					</div>
				</li>
		  </ul>
  <div class="card-body">
    <h4 class="card-title"><?php echo ucfirst($categ->name_category); ?></h4>
    <p class="card-text"><?php echo $categ->desc_category; ?></p>
  </div>


</div>

</div>

<div class="col-md-9">

	<div class="card"><div class="card-header"><?php echo lang('item_list'); ?></div>
<?php if($itens){ ?>
	<table id="table_item_category" class="table table-sm nowrap text-left" cellspacing="0" width="100%">

<thead>
		<tr>
				<th></th>
				<th><?php echo lang('item_column_name'); ?></th>
				<th></th>
		</tr>
</thead>
<tbody>

	<?php  foreach($itens as $item){ ?>

		<?php $image = $this->item_model->get_image_item($item->id_item);  ?>

		<tr>
			<td class="pl-3"><img class="img-fluid rounded" src="<?php echo base_url(); ?>images/<?php echo $image; ?>?module=item&assets=assets/images/products/thumbs&width=25" ></td>
			<td><?php echo anchor('item/profile/'.$item->slug_item,$item->name_item); ?></td>

			<td><?php echo anchor('item/edit/'.$item->slug_item,'<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
','class="btn btn-sm btn-secondary"'); ?>
</td>
		</tr>

	<?php } ?>


</tbody>

</table>

<div class="card-footer">
  <?php echo $this->pagination->create_links(); ?>
</div>

<?php } else{ ?> Sem itens adicionados. <?php } ?>

</div>
</div>
</div>
</div>

<script> var id_category = <?php echo $categ->id_category; ?>; </script>
