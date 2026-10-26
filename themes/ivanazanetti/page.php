<?php
/**
 * Pagina di solo testo: privacy, cookie, condizioni di vendita, spedizione
 * e qualunque pagina senza template dedicato.
 * Il contenuto arriva da Divi: si tolgono i tag dei moduli tenendo l'HTML.
 *
 * Carrello, checkout e account sono form di WooCommerce: niente wpautop ne'
 * stile da testo lungo, larghezza piena e titoli in italiano (le pagine in
 * WordPress si chiamano "Cart" e "Checkout").
 */

get_header();

$negozio = function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() );

while ( have_posts() ): the_post();
	?>

	<?php if ( $negozio ): ?>
		<?php
		if ( is_cart() ) {
			$testata = array( 'sopra' => 'Shop', 'titolo' => 'Il tuo carrello.' );
		} elseif ( is_checkout() && is_wc_endpoint_url( 'order-received' ) ) {
			$testata = array( 'sopra' => 'Ordine ricevuto', 'titolo' => 'Grazie!' );
		} elseif ( is_checkout() ) {
			$testata = array( 'sopra' => 'Ultimo passo', 'titolo' => 'Completa l\'ordine.' );
		} else {
			$testata = array( 'sopra' => 'Shop', 'titolo' => 'Il mio account.' );
		}
		$testata['misura'] = 'medio';
		?>
		<section class="sezione-pagina pagina-negozio" id="page-<?php the_ID(); ?>">
		  <div class="container-iz">
		    <?php get_template_part( 'testata-pagina', null, $testata ); ?>
		    <?php the_content(); ?>
		  </div>
		</section>

	<?php else: ?>
		<?php $contenuto = ivanazanetti_abbassa_h1( do_shortcode( ivanazanetti_pulisci_divi( get_the_content() ) ) ); ?>
		<section class="sezione-pagina" id="page-<?php the_ID(); ?>">
		  <div class="container-iz">
		    <?php get_template_part( 'testata-pagina', null, array( 'misura' => 'medio' ) ); ?>
		    <div class="testo-lungo"><?php print wpautop( $contenuto ); ?></div>
		  </div>
		</section>
	<?php endif; ?>

<?php
endwhile;

get_footer();
