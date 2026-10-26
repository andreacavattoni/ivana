<?php
/**
 * Scheda prodotto. Layout del tema, ma galleria, prezzo, varianti e
 * "aggiungi al carrello" passano dagli hook di WooCommerce, cosi' restano
 * attivi i plugin che ci si agganciano (variation swatches).
 */

get_header();

while ( have_posts() ): the_post();
	global $product;

	$cat = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'all' ) );
	?>

	<section class="sezione-pagina">
	  <div class="container-iz woocommerce">
	    <?php wc_print_notices(); ?>

	    <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'scheda-prodotto', $product ); ?>>
	      <div class="row gx-5 gy-4">
	        <div class="col-12 col-lg-6">
	          <?php do_action( 'woocommerce_before_single_product_summary' ); ?>
	        </div>

	        <div class="col-12 col-lg-6">
	          <?php /* "summary" sta su un div interno: se fosse sulla colonna, il
	                   width:100% che annulla il 48% di WooCommerce allargherebbe
	                   la colonna stessa e il riepilogo finirebbe sotto. */ ?>
	          <div class="summary entry-summary">
	            <?php if ( $cat && ! is_wp_error( $cat ) ): ?>
	              <p class="sopratitolo"><?php print $cat[0]->name; ?></p>
	            <?php endif; ?>
	            <?php
	            woocommerce_template_single_title();
	            woocommerce_template_single_price();
	            woocommerce_template_single_excerpt();
	            woocommerce_template_single_add_to_cart();
	            woocommerce_template_single_meta();
	            ?>
	          </div>
	        </div>
	      </div>

	      <?php
	      /* Molte descrizioni importate da Divi contengono solo la parola
	         "Descrizione:": il testo vero sta nella descrizione breve. */
	      $descrizione = ivanazanetti_abbassa_h1( apply_filters( 'the_content', ivanazanetti_pulisci_divi( $product->get_description() ) ) );
	      $solo_testo  = trim( preg_replace( '/^\s*descrizione\s*:?/iu', '', wp_strip_all_tags( $descrizione ) ) );
	      ?>
	      <?php if ( mb_strlen( $solo_testo ) > 20 ): ?>
	        <div class="scheda-descrizione">
	          <h2 class="titolo-medio">Descrizione.</h2>
	          <div class="testo-lungo">
	            <?php print $descrizione; ?>
	          </div>
	        </div>
	      <?php endif; ?>
	    </div>
	  </div>
	</section>

	<?php
	$ids = ( $cat && ! is_wp_error( $cat ) ) ? wp_list_pluck( $cat, 'term_id' ) : array();
	if ( $ids ):
		$correlati = new WP_Query( array(
			'post_type'      => 'product',
			'posts_per_page' => 4,
			'post__not_in'   => array( get_the_ID() ),
			'orderby'        => 'rand',
			'tax_query'      => array( array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $ids,
			) ),
		) );

		if ( $correlati->have_posts() ):
	?>
		<section class="sezione">
		  <div class="container-iz">
		    <?php
		    get_template_part( 'testata-pagina', null, array(
		      'sopra'  => 'Dalla stessa categoria',
		      'titolo'  => 'Ti potrebbe piacere.',
		      'livello' => 'h2',
		    ) );
		    ?>
		    <div class="griglia-card row g-4">
		      <?php while ( $correlati->have_posts() ): $correlati->the_post(); ?>
		        <div class="col-12 col-sm-6 col-lg-3"><?php wc_get_template_part( 'content', 'product' ); ?></div>
		      <?php endwhile; ?>
		    </div>
		  </div>
		</section>
	<?php
		endif;
		wp_reset_postdata();
	endif;

endwhile;

get_footer();
