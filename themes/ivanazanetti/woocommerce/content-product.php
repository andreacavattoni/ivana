<?php
/**
 * Card prodotto. E' la STESSA ovunque: best seller in home, shop, prodotti
 * correlati. Un solo template, cosi' non possono divergere.
 */

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$cat    = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) );
$colori = ivanazanetti_colori_prodotto( $product );
?>

<article class="prodotto-card">
  <a class="pc-img" href="<?php the_permalink(); ?>">
    <?php if ( has_post_thumbnail() ): ?>
      <?php the_post_thumbnail( 'card-2x', array( 'alt' => get_the_title(), 'sizes' => ivanazanetti_sizes_card() ) ); ?>
    <?php endif; ?>
  </a>

  <div class="pc-dati">
    <?php if ( $cat && ! is_wp_error( $cat ) ): ?>
      <p class="pc-cat"><?php print $cat[0]; ?></p>
    <?php endif; ?>
    <p class="pc-nome"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
    <p class="pc-prezzo"><?php print $product->get_price_html(); ?></p>
  </div>

  <?php if ( $colori ): ?>
    <div class="pc-colori" aria-label="Colori disponibili">
      <?php foreach ( $colori as $c ): ?>
        <span style="background:<?php print esc_attr( $c['hex'] ); ?>" title="<?php print esc_attr( $c['nome'] ); ?>"></span>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</article>
