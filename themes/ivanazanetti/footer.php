</main>
</div>

<footer id="footer" role="contentinfo">
  <div class="f-interno">
    <div class="row">

      <!-- ------------------------- dati ------------------------- -->
      <div class="col-lg-6 f-dati">
        <div class="f-marchi">
          <a class="f-logo" href="<?php print home_url( '/' ); ?>">
            <img src="<?php print get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo( 'name' ); ?>">
          </a>
          <span class="f-badge"><?php print ivanazanetti_icona_img( 'dottorcucito', 'Dottor Cucito' ); ?></span>
        </div>

        <div class="row g-4 f-blocchi">
          <?php if ( get_field( 'indirizzo', 'options' ) ): ?>
            <div class="col-12 f-blocco">
              <h4>Punto Vendita:</h4>
              <p><?php print get_field( 'indirizzo', 'options' ); ?></p>
            </div>
          <?php endif; ?>

          <?php if ( get_field( 'orari_negozio', 'options' ) ): ?>
            <div class="col-12 col-sm-4 f-blocco">
              <h4>Orari:</h4>
              <?php print get_field( 'orari_negozio', 'options' ); ?>
            </div>
          <?php endif; ?>

          <div class="col-12 col-sm-4 f-blocco">
            <h4>Contatti:</h4>
            <?php if ( get_field( 'telefono', 'options' ) ): ?>
              <p><a href="tel:<?php print preg_replace( '/[^0-9+]/', '', get_field( 'telefono', 'options' ) ); ?>">T. <?php print get_field( 'telefono', 'options' ); ?></a></p>
            <?php endif; ?>
            <?php if ( get_field( 'email_sito', 'options' ) ): ?>
              <p><a href="mailto:<?php print get_field( 'email_sito', 'options' ); ?>"><?php print get_field( 'email_sito', 'options' ); ?></a></p>
            <?php endif; ?>
          </div>

          <div class="col-12 col-sm-4 f-blocco f-social">
            <h4>Social:</h4>
            <p class="f-s-handle">@ ivanazanetti_ilfilodiarianna</p>
            <div class="f-social-icone">
            <?php if ( get_field( 'facebook_url', 'options' ) ): ?>
              <a class="f-s-link" target="_blank" rel="noopener" href="<?php print esc_url( get_field( 'facebook_url', 'options' ) ); ?>" aria-label="Facebook">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5h1.65V3.6c-.29-.04-1.27-.12-2.41-.12-2.38 0-4.01 1.45-4.01 4.12v2.3H7.6V13h2.68v8h3.22z"/></svg>
              </a>
            <?php endif; ?>
            <?php if ( get_field( 'instagram_url', 'options' ) ): ?>
              <a class="f-s-link" target="_blank" rel="noopener" href="<?php print esc_url( get_field( 'instagram_url', 'options' ) ); ?>" aria-label="Instagram">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.2c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9-.42-.42-.68-.82-.9-1.38-.16-.42-.36-1.06-.41-2.23C2.21 15.58 2.2 15.2 2.2 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.21 8.8 2.2 12 2.2zm0 5.8a4 4 0 100 8 4 4 0 000-8zm0 1.8a2.2 2.2 0 110 4.4 2.2 2.2 0 010-4.4zm4.2-3.1a.93.93 0 100 1.86.93.93 0 000-1.86z"/></svg>
              </a>
            <?php endif; ?>
            </div>
          </div>
        </div>

        <span class="f-paypal"><?php print ivanazanetti_icona_img( 'paypal', 'PayPal' ); ?></span>
        <nav class="f-b-menu" aria-label="Link di servizio">
          <?php
          $voci = array(
            wc_get_page_id( 'shop' ) => 'Shop',
            74  => 'Corsi',
            79  => 'Consigli',
            77  => 'Punto Vendita',
            251 => 'Condizioni di vendita',
            247 => 'Spedizione',
          );
          foreach ( $voci as $id => $etichetta ):
            if ( $id > 0 && get_post_status( $id ) === 'publish' ):
          ?>
            <a href="<?php print esc_url( get_permalink( $id ) ); ?>"><?php print $etichetta; ?></a>
          <?php endif; endforeach; ?>
        </nav>
      </div>

      <!-- ------------------------- form ------------------------- -->
      <?php if ( get_field( 'cf7_form_id', 'options' ) ): ?>
        <div class="col-lg-6 f-form">
          <h2 class="f-f-titolo">Richiesta info</h2>
          <?php print do_shortcode( '[contact-form-7 id="' . get_field( 'cf7_form_id', 'options' ) . '"]' ); ?>
        </div>
      <?php endif; ?>

    </div>
  </div>

</footer>

<div class="f-legale">
  <div class="f-interno">
    <span class="f-l-link">
      <?php
      $leg = array( 260 => 'COOKIES', 256 => 'PRIVACY' );
      foreach ( $leg as $id => $etichetta ):
        if ( get_post_status( $id ) === 'publish' ):
      ?>
        <a href="<?php print esc_url( get_permalink( $id ) ); ?>"><?php print $etichetta; ?></a>
      <?php endif; endforeach; ?>
    </span>
    <span>Creato da <a href="https://www.ledolab.it" target="_blank" rel="noopener">LeDO studio</a></span>
  </div>
</div>


</div>
<?php wp_footer(); ?>
</body>
</html>
