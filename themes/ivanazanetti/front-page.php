<?php get_header(); ?>

<?php
$hero_bg    = get_field( 'hero_bg' );
$categorie  = get_field( 'categorie' );
$garanzie   = get_field( 'garanzie' );
$progetti   = get_field( 'pg_immagini' );
$blocchi    = get_field( 'corsiconsigli' );
$chi_foto   = get_field( 'chi_foto' );
$recensioni = get_field( 'recensioni_img' );

/* Recensioni sincronizzate da Google ogni settimana (vedi functions.php);
   finche' non ci sono si usano quelle inserite a mano in ACF. */
$google           = get_option( 'ivanazanetti_recensioni_google' );
$lista_recensioni = ( $google && ! empty( $google['recensioni'] ) ) ? array_slice( $google['recensioni'], 0, 4 ) : get_field( 'recensioni' );
$shop       = get_permalink( wc_get_page_id( 'shop' ) );
?>

<!-- ============================== HERO ============================== -->
<section class="sezione-hero" id="hero">
  <div class="s-h-quadro">
    <?php if ( $hero_bg ): ?>
      <img class="s-h-bg" src="<?php print $hero_bg['sizes']['hero']; ?>" alt="">
    <?php endif; ?>
    <span class="s-h-velo"></span>

    <div class="s-h-testo container-fluid">
      <div class="row">
        <div class="col-12 col-md-8 col-lg-5">
          <?php if ( get_field( 'hero_titolo' ) ): ?>
            <h1 class="s-h-titolo">
              <?php
              $righe  = array_values( array_filter( array_map( 'trim', explode( "\n", get_field( 'hero_titolo' ) ) ) ) );
              $ultima = count( $righe ) - 1;
              foreach ( $righe as $i => $riga ):
                if ( $i === $ultima ):
              ?>
                <span class="s-h-ultima"><?php print $riga; ?><?php print ivanazanetti_swoosh(); ?></span>
              <?php else: ?>
                <?php print $riga; ?><br>
              <?php endif; endforeach; ?>
            </h1>
          <?php endif; ?>

          <?php if ( get_field( 'hero_sottotitolo' ) ): ?>
            <p class="s-h-sub"><?php print get_field( 'hero_sottotitolo' ); ?></p>
          <?php endif; ?>

          <?php if ( get_field( 'hero_bottone' ) ): ?>
            <a href="<?php print esc_url( get_field( 'hero_link' ) ?: $shop ); ?>" class="bottone">
              <?php print get_field( 'hero_bottone' ); ?>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================ CATEGORIE =========================== -->
<?php if ( $categorie ): ?>
<section class="sezione-categorie" id="categorie">
  <div class="container-iz">
    <p class="sopratitolo"><?php print get_field( 'cat_sopratitolo' ); ?></p>
    <h2 class="titolo-grande"><?php print get_field( 'cat_titolo' ); ?></h2>

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
</section>
<?php endif; ?>

<!-- =========================== BEST SELLER ========================== -->
<?php
$bs_query = ivanazanetti_query_best_seller( get_field( 'bs_prodotti' ) );
if ( $bs_query->have_posts() ):
?>
<section class="sezione-bestseller" id="bestseller">
  <div class="container-iz">
    <p class="sopratitolo"><?php print get_field( 'bs_sopratitolo' ); ?></p>
    <h2 class="titolo-grande"><?php print get_field( 'bs_titolo' ); ?></h2>

    <div class="s-bs-lista row g-4">
      <?php while ( $bs_query->have_posts() ): $bs_query->the_post(); ?>
        <div class="col-12 col-sm-6 col-lg-3"><?php wc_get_template_part( 'content', 'product' ); ?></div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <div class="s-bs-barra"><span></span></div>
  </div>
</section>
<?php endif; ?>

<!-- ============================ SERVIZI ============================= -->
<?php if ( $garanzie ): ?>
<section class="sezione-servizi">
  <div class="container-iz">
    <div class="row g-0">
      <?php foreach ( $garanzie as $g ): ?>
        <div class="col-6 col-lg-3">
          <?php $tag = $g['link'] ? 'a' : 'div'; ?>
          <<?php print $tag; ?> class="s-sv-voce"<?php print $g['link'] ? ' href="' . esc_url( $g['link'] ) . '"' : ''; ?>>
            <?php if ( $g['icona'] ): ?>
              <span class="s-sv-icona"><?php print ivanazanetti_icona_img( $g['icona'], '' ); ?></span>
            <?php endif; ?>
            <span>
              <span class="s-sv-titolo"><?php print $g['titolo']; ?></span>
              <span class="s-sv-testo"><?php print $g['testo']; ?></span>
            </span>
          </<?php print $tag; ?>>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============================ PROGETTI ============================ -->
<?php if ( $progetti ): ?>
<section class="sezione-progetti" id="progetti">
  <div class="container-iz s-pg-testa">
    <p class="sopratitolo"><?php print get_field( 'pg_sopratitolo' ); ?></p>
    <h2 class="titolo-grande"><?php print get_field( 'pg_titolo' ); ?></h2>
  </div>

  <div class="s-pg-fascia">
    <div class="s-pg-griglia">
      <?php foreach ( $progetti as $img ): ?>
        <figure class="s-pg-foto">
          <img src="<?php print $img['sizes']['progetto']; ?>" alt="<?php print esc_attr( $img['alt'] ); ?>">
        </figure>
      <?php endforeach; ?>
    </div>
    <?php print ivanazanetti_arco(); ?>
  </div>
</section>
<?php endif; ?>

<!-- ======================= TUTORIAL E CORSI ========================= -->
<?php if ( $blocchi ): ?>
<section class="sezione-corsiconsigli" id="corsiconsigli">
  <div class="container-iz">
    <div class="row gx-4 gy-5">
      <?php foreach ( $blocchi as $b ): ?>
        <div class="col-12 col-lg-6">
          <article class="s-cc-blocco">
            <?php if ( $b['sopratitolo'] ): ?>
              <p class="sopratitolo"><?php print $b['sopratitolo']; ?></p>
            <?php endif; ?>
            <h2 class="titolo-medio"><?php print $b['titolo']; ?></h2>

            <div class="s-cc-img">
              <?php if ( $b['immagine'] ): ?>
                <img src="<?php print $b['immagine']['sizes']['fascia']; ?>" alt="<?php print esc_attr( $b['titolo'] ); ?>">
              <?php endif; ?>
            </div>

            <p class="s-cc-testo testo-corrente"><?php print $b['testo']; ?></p>

            <?php if ( $b['link'] ): ?>
              <a href="<?php print esc_url( $b['link'] ); ?>" class="bottone"><?php print $b['bottone']; ?></a>
            <?php endif; ?>
          </article>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============================ CHI SONO ============================ -->
<?php if ( get_field( 'chi_testo' ) ): ?>
<section class="sezione-chisono" id="chisono">
  <div class="container-iz">
    <div class="row gx-5 gy-4 align-items-start">
      <?php if ( $chi_foto ): ?>
        <div class="col-12 col-md-5 col-lg-4 col-xl-3">
          <div class="s-ch-foto">
            <img src="<?php print $chi_foto['sizes']['ritratto']; ?>" alt="<?php print esc_attr( get_field( 'chi_titolo' ) ); ?>">
          </div>
        </div>
      <?php endif; ?>
      <div class="col-12 col-md-7 col-lg-8 col-xl-9">
        <h2 class="titolo-piccolo"><?php print get_field( 'chi_titolo' ); ?></h2>
        <div class="s-ch-testo"><?php print get_field( 'chi_testo' ); ?></div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- =========================== RECENSIONI =========================== -->
<?php if ( $recensioni ): ?>
  <?php /* Come nel design: immagine unica. */ ?>
  <section class="sezione-recensioni" id="recensioni">
    <img src="<?php print $recensioni['url']; ?>" alt="<?php print esc_attr( $recensioni['alt'] ?: 'Recensioni Google' ); ?>">
  </section>
<?php elseif ( $lista_recensioni ): ?>
  <?php /* Senza immagine: card vere, selezionabili e indicizzabili. */ ?>
  <section class="sezione-recensioni sezione-recensioni-card" id="recensioni">
    <div class="container-iz">
      <div class="row g-4">
        <?php foreach ( $lista_recensioni as $r ): ?>
          <div class="col-12 col-sm-6 col-lg-3">
            <article class="s-r-card">
              <header class="s-r-testa">
                <?php if ( ! empty( $r['link'] ) ): ?>
                  <a class="s-r-nome" href="<?php print esc_url( $r['link'] ); ?>" target="_blank" rel="noopener nofollow"><?php print esc_html( $r['nome'] ); ?></a>
                <?php else: ?>
                  <span class="s-r-nome"><?php print esc_html( $r['nome'] ); ?></span>
                <?php endif; ?>
                <span class="s-r-google" aria-hidden="true">G</span>
              </header>
              <p class="s-r-stelle" aria-label="<?php print (int) $r['stelle']; ?> stelle su 5">
                <?php print str_repeat( '★', max( 1, min( 5, (int) $r['stelle'] ) ) ); ?>
              </p>
              <p class="s-r-testo"><?php print esc_html( $r['testo'] ); ?></p>
            </article>
          </div>
        <?php endforeach; ?>
      </div>

      <?php if ( $google && $google['totale'] ): ?>
        <p class="s-r-sintesi">
          <span class="s-r-stelle" aria-hidden="true">★</span>
          <strong><?php print number_format_i18n( $google['voto'], 1 ); ?></strong> su Google
          · <?php print (int) $google['totale']; ?> recensioni
          <?php if ( $google['url'] ): ?>
            · <a href="<?php print esc_url( $google['url'] ); ?>" target="_blank" rel="noopener">Leggile tutte</a>
          <?php endif; ?>
        </p>
      <?php endif; ?>
    </div>
  </section>
<?php endif; ?>

<?php get_footer(); ?>
