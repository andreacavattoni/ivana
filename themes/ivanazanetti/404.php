<?php
/**
 * Pagina 404: testata come le pagine interne, due uscite (home e shop) e le
 * categorie della home, per non lasciare nessuno in un vicolo cieco.
 */

get_header();

$home      = (int) get_option( 'page_on_front' );
$categorie = $home ? get_field( 'categorie', $home ) : array();
$shop      = get_permalink( wc_get_page_id( 'shop' ) );
?>

<section class="sezione-pagina sezione-404">
  <div class="container-iz">
    <?php
    get_template_part( 'testata-pagina', null, array(
      'sopra'  => 'Errore 404',
      'titolo' => 'Questo filo si è perso.',
      'intro'  => '<p>La pagina che cerchi non esiste più o è stata spostata. Riparti dalla home o fatti ispirare dallo shop.</p>',
    ) );
    ?>

    <div class="s-404-azioni">
      <a class="bottone" href="<?php print esc_url( home_url( '/' ) ); ?>">Torna alla home</a>
      <a class="bottone bottone-vuoto" href="<?php print esc_url( $shop ); ?>">Vai allo shop</a>
    </div>
  </div>

  <?php if ( $categorie ): ?>
    <div class="sezione-categorie s-404-categorie">
      <div class="container-iz">
        <p class="sopratitolo">Oppure scegli da dove ripartire</p>

        <div class="s-c-lista row g-1">
          <?php foreach ( $categorie as $c ): ?>
            <?php $link = $c['categoria_id'] ? get_term_link( (int) $c['categoria_id'], 'product_cat' ) : $shop; ?>
            <?php if ( is_wp_error( $link ) ) { $link = $shop; } ?>
            <div class="col-12 col-md-4">
              <a class="s-c-card" href="<?php print esc_url( $link ); ?>">
                <span class="s-c-icona"><?php print ivanazanetti_icona_img( $c['icona'], '' ); ?></span>
                <span class="s-c-riga">
                  <span class="s-c-nome"><?php print $c['titolo']; ?></span>
                  <span class="s-c-freccia"><?php print ivanazanetti_icona_img( 'arrow_down', '' ); ?></span>
                </span>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
</section>

<?php get_footer(); ?>
