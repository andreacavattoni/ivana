<?php
/*
Template name: Punto vendita
Template Post Type: page
*/

get_header();

$pv_img = get_field( 'pv_immagine' );
$mappa  = get_field( 'mappa_embed', 'options' );
?>

<section class="sezione-pagina">
  <div class="container-iz">
    <div class="row gx-5 gy-4 align-items-start">
      <?php if ( $pv_img ): ?>
        <div class="col-12 col-md-5 col-lg-4">
          <div class="pv-foto">
            <img src="<?php print $pv_img['sizes']['ritratto']; ?>" alt="<?php the_title_attribute(); ?>">
          </div>
        </div>
      <?php endif; ?>

      <div class="col-12 col-md-7 col-lg-8">
        <?php
        get_template_part( 'testata-pagina', null, array(
          'sopra'  => 'Vieni a trovarmi',
          'titolo' => ivanazanetti_titolo_display( get_the_title() ),
        ) );
        ?>

        <?php if ( get_field( 'pv_testo' ) ): ?>
          <div class="pv-testo testo-corrente"><?php print get_field( 'pv_testo' ); ?></div>
        <?php endif; ?>

        <div class="pv-info row g-4">
          <?php if ( get_field( 'indirizzo', 'options' ) ): ?>
            <div class="col-12 col-lg-4 blocco-info">
              <h4>Punto Vendita:</h4>
              <p><?php print get_field( 'indirizzo', 'options' ); ?></p>
            </div>
          <?php endif; ?>

          <?php if ( get_field( 'orari_negozio', 'options' ) ): ?>
            <div class="col-12 col-sm-6 col-lg-4 blocco-info">
              <h4>Orari:</h4>
              <?php print get_field( 'orari_negozio', 'options' ); ?>
            </div>
          <?php endif; ?>

          <div class="col-12 col-sm-6 col-lg-4 blocco-info">
            <h4>Contatti:</h4>
            <?php if ( get_field( 'telefono', 'options' ) ): ?>
              <p><a href="tel:<?php print preg_replace( '/[^0-9+]/', '', get_field( 'telefono', 'options' ) ); ?>">T. <?php print get_field( 'telefono', 'options' ); ?></a></p>
            <?php endif; ?>
            <?php if ( get_field( 'email_sito', 'options' ) ): ?>
              <p><a href="mailto:<?php print get_field( 'email_sito', 'options' ); ?>"><?php print get_field( 'email_sito', 'options' ); ?></a></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php if ( $mappa ): ?>
<section class="sezione sezione-mappa">
  <div class="container-iz">
    <div class="mappa"><?php print $mappa; ?></div>
  </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
