<?php
/*
Template name: Consigli
Template Post Type: page
*/

get_header();

$consigli_query = new WP_Query( array(
	'post_type'      => 'post',
	'cat'            => 23,
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'DESC',
) );
?>

<section class="sezione-pagina">
  <div class="container-iz">
    <?php
    /* Il titolo lungo (buono per la SEO) resta l'h1, in piccolo; quello
       grande e' il nome della pagina, come le sezioni della home. */
    get_template_part( 'testata-pagina', null, array(
      'sopra'    => get_field( 'titolo_pagina' ) ?: 'Tutorial',
      'titolo'   => ivanazanetti_titolo_display( get_the_title() ),
      'intro'    => get_field( 'intro_pagina' ),
      'h1_sopra' => true,
    ) );
    ?>

    <?php if ( $consigli_query->have_posts() ): ?>
      <div class="griglia-card row g-4">
        <?php while ( $consigli_query->have_posts() ): $consigli_query->the_post(); ?>
          <div class="col-12 col-sm-6 col-lg-3"><?php get_template_part( 'card-post' ); ?></div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="pagina-vuota testo-corrente">Nessun consiglio pubblicato al momento.</p>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
  </div>
</section>

<?php get_footer(); ?>
