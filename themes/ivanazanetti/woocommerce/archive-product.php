<?php
/**
 * Archivio prodotti: pagina Shop e archivi di categoria (/product-category/<slug>/).
 * Le card sono content-product.php, le stesse dei best seller in home.
 */

get_header();

$shop   = get_permalink( wc_get_page_id( 'shop' ) );
$filtri = ivanazanetti_categorie_shop();
$attiva = is_product_category() ? get_queried_object_id() : 0;

/* Sulla categoria l'h1 e' il suo nome, sullo shop il titolo SEO della pagina. */
$sopra = $attiva ? single_term_title( '', false ) : ( get_field( 'titolo_pagina', wc_get_page_id( 'shop' ) ) ?: 'Merceria creativa on line' );
?>

<section class="sezione-pagina" id="shop">
  <div class="container-iz">
    <?php
    get_template_part( 'testata-pagina', null, array(
      'sopra'    => $sopra,
      'titolo'   => 'Shop.',
      'h1_sopra' => true,
    ) );
    ?>

    <nav class="filtro-pillole" aria-label="Filtra per categoria">
      <a class="<?php print $attiva ? '' : 'attiva'; ?>" href="<?php print esc_url( $shop ); ?>">Tutti</a>
      <?php foreach ( $filtri as $id => $nome ): ?>
        <a class="<?php print $attiva === $id ? 'attiva' : ''; ?>"
           href="<?php print esc_url( get_term_link( $id, 'product_cat' ) ); ?>"><?php print $nome; ?></a>
      <?php endforeach; ?>
    </nav>

    <?php if ( have_posts() ): ?>
      <div class="griglia-card row g-4">
        <?php while ( have_posts() ): the_post(); ?>
          <div class="col-12 col-sm-6 col-lg-3"><?php wc_get_template_part( 'content', 'product' ); ?></div>
        <?php endwhile; ?>
      </div>

      <?php
      $pagine = paginate_links( array(
        'total'     => $GLOBALS['wp_query']->max_num_pages,
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
      ) );
      if ( $pagine ):
      ?>
        <nav class="paginazione" aria-label="Pagine"><?php print $pagine; ?></nav>
      <?php endif; ?>
    <?php else: ?>
      <p class="pagina-vuota testo-corrente">Nessun prodotto in questa categoria.</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
