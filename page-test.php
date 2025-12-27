<?php
/*
Template Name: Page Test
*/

get_header();
?>

<div style="padding: 20px; background: white; color: black; min-height: 50vh;">
    <h1>Page de test</h1>
    <p>Si vous voyez ce message, le template fonctionne.</p>
    <p>Date actuelle : <?php echo date('Y-m-d H:i:s'); ?></p>
    <p>URL demandée : <?php echo $_SERVER['REQUEST_URI']; ?></p>
</div>

<?php get_footer(); ?>
