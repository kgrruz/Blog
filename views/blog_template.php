<?php echo theme_view('header'); ?>

<?php echo theme_view('_sitenav'); ?>

<?php echo theme_view('sidebar'); ?>

<div class="container pt-3">

              <?php echo Template::message(); ?>

  <?php echo isset($content) ? $content : Template::content(); ?>


</div>

<?php
    echo theme_view('footer');
    ?>
