<div class="container-fluid">

	<nav aria-label="breadcrumb" class="my-3" role="navigation">
<ol class="breadcrumb">
	<li class="breadcrumb-item"><?php echo anchor('blog/category','categorias'); ?></li>
 <li class="breadcrumb-item active" aria-current="page"><?php echo $categ->name_category; ?></li>
</ol>
</nav>

<div class="row">

  <div class="col-md-3">

    <div class="card">

  <div class="card-body">
    <h4 class="card-title"><?php echo ucfirst($categ->name_category); ?></h4>
    <p class="card-text"><?php echo $categ->desc_category; ?></p>
  </div>


</div>

</div>

<div class="col-md-9">

	<div class="card"><div class="card-header"><?php echo lang('blog_list'); ?></div>
<?php if($posts){ ?>
	<table id="table_blog_category" class="table table-hover table-outline table-vcenter text-nowrap mb-0" >

<thead>
		<tr>
				<th class="pl-3" ><?php echo lang('blog_column_name'); ?></th>
				<th><?php echo lang('blog_column_created'); ?></th>
		</tr>
</thead>
<tbody>

	<?php  foreach($posts as $post){ ?>

		<tr>
			<td class="pl-3"><?php echo anchor('blog/post/'.$post->slug_post,$post->title_post); ?></td>
			<td><?php echo $post->created_on; ?></td>
		</tr>

	<?php } ?>


</tbody>

</table>

<?php if($pags = $this->pagination->create_links()){ ?>
<div class="card-footer">
  <?php echo $pags; ?>
</div>
<?php } ?>

<?php } else{ ?> Sem posts adicionados. <?php } ?>

</div>
</div>
</div>
</div>

<script> var id_category = <?php echo $categ->id_category; ?>; </script>
