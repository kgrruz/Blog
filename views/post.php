


    <!-- Page Content -->
    <div class="container-fluid">

      <div class="row">

        <!-- Post Content Column -->
        <div class="col-lg-8 pt-0 mt-4">

          <div class="card pt-0">
          <div class="card-body pt-0">

          <h1 class="mt-4"><?php echo ucfirst($post->title_post); ?></h1>

          <!-- Author -->
          <p class="lead">
           <?php echo lang('blog_post_created_by'); ?>
        <?php echo anchor('partner/'.$post->username,$post->display_name); ?>
          </p>

          <hr>

          <!-- Date/Time -->
          <p>  <?php echo ut_date($post->created_on,'F j, Y H:i'); ?></p>

          <hr>

          <!-- Post Content -->
          <p class="lead"><?php echo html_entity_decode($post->body_post); ?></p>
        </div>
        </div>


          <!-- Comments Form -->
          <div class="card my-4">
            <h5 class="card-header">Deixe seu comentário:</h5>
            <div class="card-body">

<div id="comments-container"> </div>

            </div>
          </div>



        </div>


        <!-- Sidebar Widgets Column -->
        <div class="col-md-4">

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
      <!-- /.row -->

    </div>
    <!-- /.container -->
<script>

var uid = <?php echo $current_user->id; ?>;
var id_refer = <?php echo $post->id_post; ?>;
var author = <?php echo $post->created_by; ?>;


</script>
