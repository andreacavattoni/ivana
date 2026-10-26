<?php
/**
 * Testata di pagina, uguale su tutte le pagine interne: sopratitolo
 * maiuscolo + titolo grande rosa, come le sezioni della home.
 *
 *   get_template_part( 'testata-pagina', null, array(
 *     'sopra'    => '…',          // riga piccola in maiuscolo
 *     'titolo'   => '…',          // titolo grande
 *     'intro'    => '…',          // HTML opzionale sotto il titolo
 *     'h1_sopra' => true,         // l'h1 e' il sopratitolo (titoli SEO lunghi)
 *     'misura'   => 'medio',      // 'grande' (default) | 'medio'
 *     'livello'  => 'h2',         // sezioni secondarie: un solo h1 per pagina
 *   ) );
 */

$sopra    = isset( $args['sopra'] ) ? $args['sopra'] : '';
$titolo   = isset( $args['titolo'] ) ? $args['titolo'] : get_the_title();
$intro    = isset( $args['intro'] ) ? $args['intro'] : '';
$h1_sopra = ! empty( $args['h1_sopra'] );
$classe   = ( isset( $args['misura'] ) && $args['misura'] === 'medio' ) ? 'titolo-medio' : 'titolo-grande';

$livello    = isset( $args['livello'] ) ? $args['livello'] : 'h1';
$tag_sopra  = $h1_sopra ? $livello : 'p';
$tag_titolo = $h1_sopra ? 'p' : $livello;
?>

<header class="testata-pagina">
  <?php if ( $sopra ): ?>
    <<?php print $tag_sopra; ?> class="sopratitolo"><?php print $sopra; ?></<?php print $tag_sopra; ?>>
  <?php endif; ?>

  <<?php print $tag_titolo; ?> class="<?php print $classe; ?>"><?php print $titolo; ?></<?php print $tag_titolo; ?>>

  <?php if ( $intro ): ?>
    <div class="testata-intro testo-corrente"><?php print $intro; ?></div>
  <?php endif; ?>
</header>
