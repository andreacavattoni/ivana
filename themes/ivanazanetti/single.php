<?php get_header(); ?>

<?php while ( have_posts() ): the_post(); ?>

  <?php
  $e_corso   = in_category( 24 );
  $contenuto = ivanazanetti_abbassa_h1( do_shortcode( ivanazanetti_pulisci_divi( get_the_content() ) ) );

  $tags = get_the_tags();
  $nomi = array();
  if ( $tags ) {
    foreach ( $tags as $t ) { $nomi[] = $t->name; }
  }
  ?>

  <article class="sezione-pagina" id="post-<?php the_ID(); ?>">
    <div class="container-iz">
      <?php
      get_template_part( 'testata-pagina', null, array(
        'sopra'  => $nomi ? implode( ', ', $nomi ) : ( $e_corso ? 'Corsi' : 'Tutorial' ),
        'titolo' => get_the_title(),
        'misura' => 'medio',
      ) );
      ?>

      <?php if ( has_post_thumbnail() ): ?>
        <div class="post-cover"><?php the_post_thumbnail( 'fascia-xl', array( 'alt' => get_the_title(), 'sizes' => '(max-width: 991px) 100vw, 1740px' ) ); ?></div>
      <?php endif; ?>

      <div class="testo-lungo"><?php print wpautop( $contenuto ); ?></div>

      <div class="post-piede">
        <a href="<?php print get_permalink( $e_corso ? 74 : 79 ); ?>" class="bottone">
          <?php print $e_corso ? 'Tutti i corsi' : 'Tutti i consigli'; ?>
        </a>
      </div>
    </div>
  </article>

<?php endwhile; ?>

<?php get_footer(); ?>
