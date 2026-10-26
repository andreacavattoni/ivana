<?php
/**
 * Card di un post (corsi, consigli). Stessa famiglia della card prodotto:
 * immagine sopra, tag al posto della categoria, estratto al posto del prezzo.
 */

$tags = get_the_tags();
$tag  = $tags ? $tags[0]->name : '';

/* Gli estratti possono contenere shortcode Divi, anche quelli scritti a mano. */
$estratto = has_excerpt() ? get_the_excerpt() : get_the_content();
$estratto = wp_strip_all_tags( ivanazanetti_pulisci_divi( strip_shortcodes( $estratto ) ) );
$estratto = trim( preg_replace( '/\s+/', ' ', $estratto ) );
if ( mb_strlen( $estratto ) > 90 ) {
	$estratto = mb_substr( $estratto, 0, 90 );
	$taglio   = mb_strrpos( $estratto, ' ' );
	$estratto = ( $taglio ? mb_substr( $estratto, 0, $taglio ) : $estratto ) . '…';
}
?>

<article class="prodotto-card post-card">
  <a class="pc-img" href="<?php the_permalink(); ?>">
    <?php if ( has_post_thumbnail() ): ?>
      <?php the_post_thumbnail( 'card-2x', array( 'alt' => get_the_title(), 'sizes' => ivanazanetti_sizes_card() ) ); ?>
    <?php endif; ?>
  </a>

  <div class="pc-dati">
    <?php if ( $tag ): ?>
      <p class="pc-cat"><?php print $tag; ?></p>
    <?php endif; ?>
    <p class="pc-nome"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
  </div>

  <?php if ( $estratto ): ?>
    <p class="pc-estratto"><?php print $estratto; ?></p>
  <?php endif; ?>
</article>
