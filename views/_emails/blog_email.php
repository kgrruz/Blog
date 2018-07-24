<style type="text/css">
  @media only screen and (max-width: 480px){
       .emailImage{
           height:auto !important;
           max-width:600px !important;
           width: 100% !important;
       }
   }
</style>

   <h2><?php echo ucfirst($blog->title_post); ?></h2>

  <?php echo html_entity_decode($blog->body_post); ?>
