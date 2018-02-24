
    <div class="card border-0">


 <div class="container-fluid">
   <div class="row">

  <!-- Blog Entries Column -->
       <div class="col-md-8">

         <h1 class="my-4"><?php echo lang('blog_area_title'); ?> <?php echo html_escape($this->settings_lib->item('site.title')); ?>
           <small><?php echo lang('blog_list'); ?></small>
         </h1>

    <?php if($posts){ foreach($posts as $post){ ?>

         <div class="card mb-4">
          <?php if($post->preview_image){ ?>
					 <img class="card-img-top img-fluid" src="<?php echo base_url(); ?>images/<?php echo $post->preview_image; ?>?module=blog&assets=assets/images/posts" alt="Card image cap">
				 <?php } ?>
				  <div class="card-body">
             <h2 class="card-title"><?php echo $post->title_post; ?></h2>
             <p class="card-text"><?php // echo ellipsize($post->body_post,200); ?></p>
             <?php echo anchor('blog/post/'.$post->slug_post, lang('blog_know_more').' &rarr;','class="btn btn-primary"'); ?>
           </div>
           <div class="card-footer text-muted">
             <?php echo date('F j, Y H:i',strtotime($post->created_on)); ?> <?php echo lang('blog_post_created_by'); ?>
        <?php echo anchor('partner/'.$post->username,$post->display_name); ?>
           </div>
         </div>

       <?php } ?>

         <!-- Pagination -->
         <ul class="pagination justify-content-center mb-4">
           <li class="page-item">
             <a class="page-link" href="#">&larr; Anteriores</a>
           </li>
           <li class="page-item disabled">
             <a class="page-link" href="#">Novos &rarr;</a>
           </li>
         </ul>
<?php } else{ ?><div class="card-text"><?php echo lang('blog_empty_posts'); ?></div> <?php } ?>
       </div>

       <!-- Sidebar Widgets Column -->
       <div class="col-md-4" style="position: sticky">

         <!-- Search Widget -->
         <div class="card my-4">
           <h5 class="card-header">Procurar</h5>
           <div class="card-body">
             <div class="input-group">
               <input type="text" class="form-control" placeholder="Procurar por...">
               <span class="input-group-btn">
                 <button class="btn btn-secondary" type="button">Buscar!</button>
               </span>
             </div>
           </div>
         </div>

         <!-- Categories Widget -->
         <div class="card my-4">
           <h5 class="card-header">Categorias</h5>
           <div class="card-body">
             <div class="row">
               <div class="col-lg-6">
                 <ul class="list-unstyled mb-0">
                   <li>
                     <a href="#">Empreendimento</a>
                   </li>
                   <li>
                     <a href="#">Contabilidade</a>
                   </li>
                   <li>
                     <a href="#">Dicas gerais</a>
                   </li>
                 </ul>
               </div>
               <div class="col-lg-6">
                 <ul class="list-unstyled mb-0">
                   <li>
                     <a href="#">Técnicas de preparo</a>
                   </li>
                   <li>
                     <a href="#">Mercado</a>
                   </li>
                   <li>
                     <a href="#">Atualizações</a>
                   </li>
                 </ul>
               </div>
             </div>
           </div>
         </div>



       </div>
       </div>
       </div>

       </div>
