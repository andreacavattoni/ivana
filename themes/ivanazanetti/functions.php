<?php
add_action( 'after_setup_theme', 'ivanazanetti_setup' );
function ivanazanetti_setup() {
load_theme_textdomain( 'ivanazanetti', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'responsive-embeds' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', array( 'search-form', 'navigation-widgets' ) );
global $content_width;
if ( !isset( $content_width ) ) { $content_width = 1920; }
register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'ivanazanetti' ) ) );
}
add_action( 'wp_enqueue_scripts', 'ivanazanetti_enqueue' );
function ivanazanetti_enqueue() {
wp_enqueue_style( 'ivanazanetti-style', get_stylesheet_uri(), array(), filemtime( get_template_directory() . '/style.css' ) );
wp_enqueue_script( 'jquery' );
}
add_action( 'wp_footer', 'ivanazanetti_footer' );
function ivanazanetti_footer() {
?>
<script>
jQuery(document).ready(function($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
$("html").addClass("mobile");
}
if (deviceAgent.match(/(Android)/)) {
$("html").addClass("android");
$("html").addClass("mobile");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
});
</script>
<?php
}
add_filter( 'document_title_separator', 'ivanazanetti_document_title_separator' );
function ivanazanetti_document_title_separator( $sep ) {
$sep = esc_html( '|' );
return $sep;
}
add_filter( 'the_title', 'ivanazanetti_title' );
function ivanazanetti_title( $title ) {
if ( $title == '' ) {
return esc_html( '...' );
} else {
return wp_kses_post( $title );
}
}
function ivanazanetti_schema_type() {
$schema = 'https://schema.org/';
if ( is_single() ) {
$type = "Article";
} elseif ( is_author() ) {
$type = 'ProfilePage';
} elseif ( is_search() ) {
$type = 'SearchResultsPage';
} else {
$type = 'WebPage';
}
echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}
add_filter( 'nav_menu_link_attributes', 'ivanazanetti_schema_url', 10 );
function ivanazanetti_schema_url( $atts ) {
$atts['itemprop'] = 'url';
return $atts;
}
if ( !function_exists( 'ivanazanetti_wp_body_open' ) ) {
function ivanazanetti_wp_body_open() {
do_action( 'wp_body_open' );
}
}
add_action( 'wp_body_open', 'ivanazanetti_skip_link', 5 );
function ivanazanetti_skip_link() {
echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'ivanazanetti' ) . '</a>';
}
add_filter( 'the_content_more_link', 'ivanazanetti_read_more_link' );
function ivanazanetti_read_more_link() {
if ( !is_admin() ) {
return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf( __( '...%s', 'ivanazanetti' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
add_filter( 'excerpt_more', 'ivanazanetti_excerpt_read_more_link' );
function ivanazanetti_excerpt_read_more_link( $more ) {
if ( !is_admin() ) {
global $post;
return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">' . sprintf( __( '...%s', 'ivanazanetti' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
}
}
/* Il plugin OD Uploads On-Demand cancella i file derivati e li ricrea al volo
   con una regola .htaccess. Su Apache (produzione) funziona; su nginx (DDEV)
   no, e WordPress continua ad annunciare nel srcset size i cui file non
   esistono piu': il browser ne sceglie una, prende 404 e l'immagine resta
   vuota. Qui si tolgono dal srcset le voci senza file. */
add_filter( 'wp_calculate_image_srcset', 'ivanazanetti_srcset_solo_esistenti', 10, 5 );
function ivanazanetti_srcset_solo_esistenti( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
	/* In produzione il plugin crea il file alla prima richiesta: li' un file
	   che ancora non esiste e' normale e non va tolto dal srcset. */
	if ( empty( $sources ) || wp_get_environment_type() === 'production' ) {
		return $sources;
	}

	$upload = wp_get_upload_dir();
	$base   = trailingslashit( $upload['basedir'] );
	$dir    = isset( $image_meta['file'] ) ? trailingslashit( dirname( $image_meta['file'] ) ) : '';

	foreach ( $sources as $w => $sorgente ) {
		$nome = basename( parse_url( $sorgente['url'], PHP_URL_PATH ) );

		if ( ! file_exists( $base . $dir . $nome ) ) {
			unset( $sources[ $w ] );
		}
	}

	return $sources;
}

add_filter( 'big_image_size_threshold', '__return_false' );
add_filter( 'intermediate_image_sizes_advanced', 'ivanazanetti_image_insert_override' );
function ivanazanetti_image_insert_override( $sizes ) {
unset( $sizes['medium_large'] );
unset( $sizes['1536x1536'] );
unset( $sizes['2048x2048'] );
/* Size del plugin PayPal per logo e testata del checkout: il tema non le usa. */
unset( $sizes['eh_header_image_size'] );
unset( $sizes['eh_logo_image_size'] );
return $sizes;
}
add_action( 'widgets_init', 'ivanazanetti_widgets_init' );
function ivanazanetti_widgets_init() {
register_sidebar( array(
'name' => esc_html__( 'Sidebar Widget Area', 'ivanazanetti' ),
'id' => 'primary-widget-area',
'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
'after_widget' => '</li>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
}
add_action( 'wp_head', 'ivanazanetti_pingback_header' );
function ivanazanetti_pingback_header() {
if ( is_singular() && pings_open() ) {
printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
}
}
add_action( 'comment_form_before', 'ivanazanetti_enqueue_comment_reply_script' );
function ivanazanetti_enqueue_comment_reply_script() {
if ( get_option( 'thread_comments' ) ) {
wp_enqueue_script( 'comment-reply' );
}
}
function ivanazanetti_custom_pings( $comment ) {
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo esc_url( comment_author_link() ); ?></li>
<?php
}
add_filter( 'get_comments_number', 'ivanazanetti_comment_count', 0 );
function ivanazanetti_comment_count( $count ) {
if ( !is_admin() ) {
global $id;
$get_comments = get_comments( 'status=approve&post_id=' . $id );
$comments_by_type = separate_comments( $get_comments );
return count( $comments_by_type['comment'] );
} else {
return $count;
}
}




add_action( 'after_setup_theme', 'ivanazanetti_image_setup' );
/* Una size per ogni box della grafica, tagliata con le sue proporzioni
   (le stesse degli aspect-ratio in style.css). Dove serve c'e' anche la
   versione doppia: stesse proporzioni, quindi finisce nello stesso srcset.
     hero        box hero home                  1826x910  (2:1)
     card/-2x    card prodotto e post           417x433   (420/436)
     progetto    carosello progetti             627x590
     fascia/-xl  tutorial-corsi 858x380, cover del post 1740x770 (859/380)
     ritratto    chi sono e punto vendita       548x604   (493/543) */
function ivanazanetti_image_setup() {
	add_image_size( 'hero', 1920, 960, true );
	add_image_size( 'card', 420, 436, true );
	add_image_size( 'card-2x', 840, 872, true );
	add_image_size( 'progetto', 640, 602, true );
	add_image_size( 'fascia', 960, 425, true );
	add_image_size( 'fascia-xl', 1920, 850, true );
	add_image_size( 'ritratto', 620, 683, true );

	add_filter( 'jpeg_quality', function( $arg ) { return 95; } );
}

/* Le card sono 417px su desktop, mezza riga su tablet, piena su telefono. */
function ivanazanetti_sizes_card() {
	return '(max-width: 575px) calc(100vw - 40px), (max-width: 991px) 50vw, 420px';
}

add_action( 'after_setup_theme', 'ivanazanetti_woo_setup' );
function ivanazanetti_woo_setup() {
	/* Galleria scheda prodotto: 846px di box (foto 3:4, senza taglio);
	   miniature 161px, doppie per gli schermi retina. */
	add_theme_support( 'woocommerce', array(
		'single_image_width'            => 900,
		'thumbnail_image_width'         => 420,
		'gallery_thumbnail_image_width' => 320,
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

function ivanazanetti_scripts() {
	$uri = get_template_directory_uri();
	$dir = get_template_directory();

	wp_enqueue_script( 'gsap', 'https://unpkg.co/gsap@3/dist/gsap.min.js', array('jquery'), null, true );
	wp_enqueue_script( 'scrollbar-trigger', 'https://unpkg.com/gsap@3/dist/ScrollTrigger.min.js', array('jquery'), null, true );

	wp_enqueue_script( 'cookie', $uri . '/js/cookie.js', array('jquery'), filemtime( $dir . '/js/cookie.js' ), true );
	wp_enqueue_style( 'cookie', $uri . '/css/cookie.css', array(), filemtime( $dir . '/css/cookie.css' ) );

	wp_enqueue_script( 'flickity', $uri . '/js/flickity.js', array('jquery'), filemtime( $dir . '/js/flickity.js' ), true );
	wp_enqueue_style( 'flickity', $uri . '/css/flickity.css', array(), filemtime( $dir . '/css/flickity.css' ) );

	wp_enqueue_script( 'spotlight', $uri . '/js/spotlight.js', array('jquery'), filemtime( $dir . '/js/spotlight.js' ), true );
	wp_enqueue_style( 'spotlight', $uri . '/css/spotlight.css', array(), filemtime( $dir . '/css/spotlight.css' ) );

	wp_enqueue_script( 'confirm', $uri . '/js/jquery-confirm.js', array('jquery'), filemtime( $dir . '/js/jquery-confirm.js' ), true );
	wp_enqueue_style( 'confirm', $uri . '/css/jquery-confirm.css', array(), filemtime( $dir . '/css/jquery-confirm.css' ) );

	wp_enqueue_script( 'all', $uri . '/js/all.js', array('jquery'), filemtime( $dir . '/js/all.js' ), true );
	wp_enqueue_style( 'mobile', $uri . '/css/mobile.css', array(), filemtime( $dir . '/css/mobile.css' ) );
}
add_action( 'wp_enqueue_scripts', 'ivanazanetti_scripts' );

if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( array(
		'page_title' => 'Opzioni sito',
		'menu_title' => 'Opzioni sito',
		'menu_slug'  => 'opzioni-sito',
		'capability' => 'edit_posts',
		'redirect'   => false,
	) );
}

/* Stampa un SVG di images/icone/ inline, cosi' si colora col CSS invece di
   essere un <img> immutabile. I file letti restano in memoria per la
   richiesta: la stessa icona nella pagina non rilegge il disco. */
function ivanazanetti_icona( $nome ) {
	static $cache = array();

	$nome = preg_replace( '/[^a-z0-9_-]/', '', (string) $nome );
	if ( $nome === '' ) {
		return '';
	}

	if ( isset( $cache[ $nome ] ) ) {
		return $cache[ $nome ];
	}

	$file = get_template_directory() . '/images/icone/' . $nome . '.svg';
	if ( ! file_exists( $file ) ) {
		$cache[ $nome ] = '';
		return '';
	}

	$svg = file_get_contents( $file );
	$svg = preg_replace( '/<\?xml.*?\?>/', '', $svg );
	$svg = preg_replace( '/<!--.*?-->/s', '', $svg );

	/* width/height vanno tolti SOLO dal tag <svg> radice, cosi' li decide il
	   CSS. Toglierli alla cieca colpiva i <rect> dentro i <clipPath> e
	   l'icona usciva tagliata. */
	$svg = preg_replace_callback( '/<svg[^>]*>/', function ( $m ) {
		return preg_replace( '/\s(width|height)="[^"]*"/', '', $m[0] );
	}, $svg, 1 );

	/* Le icone condividono gli stessi id interni (clip-path, gradienti...).
	   Inline nella stessa pagina sarebbero id duplicati e il browser
	   risolverebbe url(#id) sulla prima icona, ritagliando male le altre.
	   Qui si prefissano con il nome dell'icona. */
	$svg = preg_replace( '/id="([^"]+)"/', 'id="' . $nome . '-$1"', $svg );
	$svg = preg_replace( '/url\(#([^)]+)\)/', 'url(#' . $nome . '-$1)', $svg );
	$svg = preg_replace( '/(xlink:href|href)="#([^"]+)"/', '$1="#' . $nome . '-$2"', $svg );

	$cache[ $nome ] = trim( $svg );

	return $cache[ $nome ];
}

/* I due SVG decorativi del design: la sottolineatura a pennello sotto
   "da Ivana." nell'hero e l'arco dietro al carosello dei progetti. Sono
   disegni inline perche' devono stare dietro al testo con z-index. */
function ivanazanetti_swoosh() {
	return '<svg class="s-h-swoosh" viewBox="-2 -34 480 67" preserveAspectRatio="none" aria-hidden="true">'
		. '<path fill="var(--swoosh)" fill-rule="nonzero" d="M 3.697 7.137 L 2.484 -17.833 L 2.483 -17.833 L 3.697 7.137 Z M 0 7.32 C 1.272 32.287 1.272 32.287 1.272 32.287 C 1.275 32.287 1.302 32.286 1.478 32.277 C 2.14 32.244 3.653 32.169 4.911 32.108 L 3.697 7.137 L 2.483 -17.833 C 1.208 -17.771 0.274 -17.725 -0.343 -17.695 C -1.035 -17.66 -1.272 -17.648 -1.272 -17.648 C -1.272 -17.648 -1.272 -17.648 0 7.32 Z M 3.697 7.137 L 4.911 32.108 C 40.135 30.396 331.956 16.955 471.697 32.173 L 474.404 7.32 L 477.11 -17.534 C 332.561 -33.275 36.191 -19.472 2.484 -17.833 L 3.697 7.137 Z"></path>'
		. '</svg>';
}

function ivanazanetti_arco() {
	return '<svg class="s-pg-arco" viewBox="0 0 2331.246 1211.256" preserveAspectRatio="none" aria-hidden="true">'
		. '<path fill="var(--rosa-chiaro)" d="M 142.232 903.695 C 251.652 961.374 365.813 1007.756 486.398 1035.797 C 809.965 1111.063 1132.38 1109.158 1451.472 1011.831 C 1567.272 976.48 1674.256 920.751 1774.241 852.086 C 1795.948 837.157 1804.853 820.5 1803.036 791.262 C 1795.904 674.399 1813.801 559.883 1843.349 446.786 C 1874.891 325.891 1946.789 226.393 2015.1 125.788 C 2044.604 82.374 2079.335 41.175 2130.767 16.677 C 2193.097 -13.048 2278.507 -3.302 2304.511 48.042 C 2324.003 86.583 2329.098 123.795 2330.693 165.525 C 2335.654 295.014 2307.613 417.06 2231.992 524.089 C 2143.526 649.413 2051.205 771.991 1932.259 871.002 C 1911.482 888.323 1907.628 905.068 1912.28 930.807 C 1928.095 1017.723 1987.058 1071.725 2054.527 1116.911 C 2086.334 1138.219 2125.983 1150.091 2162.265 1117.841 C 2169.353 1111.55 2174.491 1105.215 2170.327 1095.78 C 2166.783 1087.761 2159.828 1088.337 2152.12 1089.843 C 2112.604 1097.463 2095.637 1071.149 2088.727 1040.582 C 2080.354 1003.459 2099.846 977.632 2134.311 962.437 C 2167.58 947.773 2190.306 964.43 2208.425 988.485 C 2227.429 1013.78 2242.226 1041.689 2245.46 1074.383 C 2256.978 1191.024 2122.217 1232.711 2040.262 1200.992 C 1953.789 1167.501 1896.553 1104.994 1860.316 1020.735 C 1832.052 955.083 1831.432 956.235 1768.216 989.415 C 1652.771 1050.062 1539.141 1116.335 1410.627 1145.351 C 1173.623 1198.777 934.314 1212.908 692.348 1181.101 C 455.122 1149.87 232.293 1075.889 20.185 966.955 C 4.636 958.981 5.832 945.868 2.067 931.781 C -4.844 905.821 7.826 838.929 7.427 829.404 L 7.117 829.493 L 142.232 903.695 Z M 2223.62 324.916 C 2237.486 275.168 2245.858 224.311 2247.63 172.303 C 2248.649 141.559 2239.701 118.656 2210.064 107.005 C 2181.49 95.797 2157.613 104.657 2135.419 124.282 C 2055.413 195.162 1999.684 282.787 1957.466 379.848 C 1904.394 501.717 1884.548 629.656 1888.092 761.847 C 1888.225 767.65 1886.409 773.498 1885.523 779.345 C 2043.541 654.198 2170.372 515.805 2223.62 324.916 Z"></path>'
		. '</svg>';
}

/* Gli SVG grandi (loghi multicolore come il badge e PayPal) NON vanno inline:
   dottorcucito.svg da solo pesa 582 KB e finirebbe nell'HTML di ogni pagina.
   Sono marchi, non hanno bisogno di essere ricolorati dal CSS, quindi come
   <img> il browser li scarica una volta e li tiene in cache. */
function ivanazanetti_icona_img( $nome, $alt = '', $classe = '' ) {
	$nome = preg_replace( '/[^a-z0-9_-]/', '', (string) $nome );
	$file = get_template_directory() . '/images/icone/' . $nome . '.svg';

	if ( $nome === '' || ! file_exists( $file ) ) {
		return '';
	}

	return sprintf(
		'<img src="%s" alt="%s"%s loading="lazy" decoding="async">',
		esc_url( get_template_directory_uri() . '/images/icone/' . $nome . '.svg' ),
		esc_attr( $alt ),
		$classe ? ' class="' . esc_attr( $classe ) . '"' : ''
	);
}

/* L'icona del carrello non era fra gli SVG del design: disegnata a mano,
   stesso peso di tratto delle altre. */
function ivanazanetti_icona_carrello() {
	return '<svg class="ico-carrello" viewBox="0 0 24 24" fill="none" aria-hidden="true">'
		. '<path d="M4 7h16l-1.4 11.2a2 2 0 0 1-2 1.8H7.4a2 2 0 0 1-2-1.8L4 7Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>'
		. '<path d="M8.5 9.5V6a3.5 3.5 0 0 1 7 0v3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>'
		. '</svg>';
}

/* Prodotti della sezione best seller: quelli scelti a mano, altrimenti i piu'
   venduti secondo total_sales di WooCommerce. */
function ivanazanetti_query_best_seller( $ids = array(), $quanti = 4 ) {
	if ( ! empty( $ids ) ) {
		return new WP_Query( array(
			'post_type'      => 'product',
			'post__in'       => (array) $ids,
			'orderby'        => 'post__in',
			'posts_per_page' => $quanti,
			'post_status'    => 'publish',
		) );
	}

	return new WP_Query( array(
		'post_type'      => 'product',
		'posts_per_page' => $quanti,
		'post_status'    => 'publish',
		'meta_key'       => 'total_sales',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
	) );
}

/* Colori veri del prodotto, dall'attributo pa_colore (tipo "color" di
   Variation Swatches, che salva l'esadecimale in product_attribute_color).
   Nel design ogni card ha i pallini colore: qui sono quelli reali, e se il
   prodotto non ha colori non se ne mostra nessuno. */
function ivanazanetti_colori_prodotto( $prodotto, $max = 5 ) {
	if ( ! $prodotto ) {
		return array();
	}

	$termini = wc_get_product_terms( $prodotto->get_id(), 'pa_colore', array( 'fields' => 'all' ) );
	if ( is_wp_error( $termini ) ) {
		return array();
	}

	$colori = array();
	foreach ( $termini as $t ) {
		$hex = get_term_meta( $t->term_id, 'product_attribute_color', true );
		if ( $hex ) {
			$colori[] = array( 'hex' => $hex, 'nome' => $t->name );
		}
		if ( count( $colori ) >= $max ) {
			break;
		}
	}

	return $colori;
}

/* Titoli grandi nello stile della home: iniziale maiuscola e punto finale
   ("Categorie.", "I più amati."). */
function ivanazanetti_titolo_display( $testo ) {
	$testo = trim( wp_strip_all_tags( $testo ) );
	$testo = mb_strtoupper( mb_substr( $testo, 0, 1 ) ) . mb_substr( $testo, 1 );

	return preg_match( '/[.!?]$/u', $testo ) ? $testo : $testo . '.';
}

/* Categorie del filtro shop, lette dal catalogo invece che scritte a mano:
   la home linka anche "Attrezzatura" (37), che nel filtro fisso non c'era.
   Ci sono due "Libri e schemi": si tiene quella con piu' prodotti. */
function ivanazanetti_categorie_shop() {
	$termini = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
		'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		'orderby'    => 'count',
		'order'      => 'DESC',
	) );
	if ( is_wp_error( $termini ) ) {
		return array();
	}

	$categorie = array();
	$visti     = array();
	foreach ( $termini as $t ) {
		$chiave = mb_strtolower( $t->name );
		if ( isset( $visti[ $chiave ] ) ) {
			continue;
		}
		$visti[ $chiave ]         = true;
		$categorie[ $t->term_id ] = $t->name;
	}

	asort( $categorie );

	return $categorie;
}

/* I testi importati da Divi hanno spesso un loro <h1>: la pagina ne
   avrebbe due. Il titolo della pagina resta l'h1, quelli del contenuto
   scendono a h2. */
function ivanazanetti_abbassa_h1( $html ) {
	return preg_replace( '#<(/?)h1\b#i', '<$1h2', $html );
}

function hexToRgba( $hex, $alpha = 0.17 ) {
	$hex = str_replace( '#', '', $hex );

	if ( strlen( $hex ) == 3 ) {
		$r = hexdec( str_repeat( substr( $hex, 0, 1 ), 2 ) );
		$g = hexdec( str_repeat( substr( $hex, 1, 1 ), 2 ) );
		$b = hexdec( str_repeat( substr( $hex, 2, 1 ), 2 ) );
	} else {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	}

	return "rgba($r, $g, $b, $alpha)";
}

/* Le voci di menu che puntano a contenuti non pubblicati sono 404 per il
   visitatore: si nascondono. */
add_filter( 'wp_get_nav_menu_items', 'ivanazanetti_hide_unpublished_items', 10, 3 );
function ivanazanetti_hide_unpublished_items( $items, $menu, $args ) {
	if ( is_admin() ) {
		return $items;
	}

	foreach ( $items as $key => $item ) {
		if ( $item->type === 'post_type' && get_post_status( $item->object_id ) !== 'publish' ) {
			unset( $items[ $key ] );
		}
	}

	return $items;
}

/* Nel design la voce Shop e il carrello stanno nella stessa pillola, ma
   sono due link: "SHOP" porta al negozio, l'icona al carrello. Il carrello
   si aggiunge dopo il link della voce, dentro lo stesso <li>, cosi' compare
   sia nel menu dell'header sia in quello mobile. */
add_filter( 'walker_nav_menu_start_el', 'ivanazanetti_carrello_nel_menu', 10, 4 );
function ivanazanetti_carrello_nel_menu( $output, $item, $depth, $args ) {
	if ( $item->object_id != 471 || is_admin() || ! function_exists( 'wc_get_cart_url' ) ) {
		return $output;
	}

	return $output
		. '<a class="h-carrello" href="' . esc_url( wc_get_cart_url() ) . '" aria-label="Vai al carrello">'
		. '<span class="h-c-icona" aria-hidden="true"></span>'
		. ivanazanetti_conta_carrello()
		. '</a>';
}

/* Il numero di articoli nel carrello. C'e' sempre, vuoto quando il carrello
   e' vuoto: WooCommerce lo sostituisce via AJAX (frammenti) quando si
   aggiunge un prodotto, e serve un elemento da sostituire. */
function ivanazanetti_conta_carrello() {
	$conta = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;

	return '<span class="h-c-conta' . ( $conta ? '' : ' vuoto' ) . '">' . ( $conta ? $conta : '' ) . '</span>';
}

/* Nel checkout WooCommerce include il testo delle condizioni di vendita:
   viene da Divi, con i suoi shortcode e un <h1> (la pagina ne avrebbe due). */
add_filter( 'woocommerce_format_content', 'ivanazanetti_pulisci_condizioni' );
function ivanazanetti_pulisci_condizioni( $html ) {
	return ivanazanetti_abbassa_h1( ivanazanetti_pulisci_divi( $html ) );
}

add_filter( 'woocommerce_add_to_cart_fragments', 'ivanazanetti_frammento_carrello' );
function ivanazanetti_frammento_carrello( $frammenti ) {
	$frammenti['span.h-c-conta'] = ivanazanetti_conta_carrello();

	return $frammenti;
}

/* WooCommerce carica lo script dei frammenti solo se c'e' il widget
   mini-carrello: senza, il contatore non si aggiornerebbe. */
add_action( 'wp_enqueue_scripts', 'ivanazanetti_frammenti_carrello' );
function ivanazanetti_frammenti_carrello() {
	if ( function_exists( 'WC' ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
	}
}

/* La voce Shop nel menu e' resa come bottone: le si aggancia una classe
   invece di dipendere dall'ID della voce, che cambia se il menu viene rifatto. */
add_filter( 'nav_menu_css_class', 'ivanazanetti_classe_voce_shop', 10, 2 );
function ivanazanetti_classe_voce_shop( $classes, $item ) {
	if ( $item->object_id == 471 ) {
		$classes[] = 'voce-shop';
	}

	return $classes;
}

/* =========================================================================
   Il tema si riprende gli shortcode del plugin SDW.
   I post dei corsi sono layout Divi su misura e contengono ancora questi
   shortcode: invece di riscrivere 21 post a mano, si sostituisce l'output.
   Cosi' il contenuto resta valido ma il markup e le immagini sono del tema.
   ========================================================================= */
add_action( 'init', 'ivanazanetti_sostituisci_shortcode_sdw', 99 );
function ivanazanetti_sostituisci_shortcode_sdw() {
	/* Shortcode che rendono ancora qualcosa: il tema li ridisegna. */
	$nostri = array(
		'sdw_area_iscriversi'  => 'ivanazanetti_sc_iscriversi',
		'sdw_tag_di_post'      => 'ivanazanetti_sc_tag_post',
		'sdw_tag_di_prodotto'  => 'ivanazanetti_sc_tag_prodotto',
		'sdw_icona_corsi_1'    => 'ivanazanetti_sc_icona_persone',
		'sdw_icona_corsi_2'    => 'ivanazanetti_sc_icona_date',
		'sdw_icona_corsi_3'    => 'ivanazanetti_sc_icona_orario',
	);

	/* Shortcode resi superflui dai template del tema: la sezione che
	   producevano ora la disegna il template, quindi qui non devono
	   stampare niente. Restano registrati apposta: dopo la rimozione del
	   plugin, se restassero sconosciuti WordPress li stamperebbe come
	   testo dentro i contenuti. */
	$vuoti = array(
		'sdw_area_home_header',
		'sdw_area_banner_categorie',
		'sdw_area_home_intro',
		'sdw_area_corsiconsigli',
		'sdw_area_puntovendita',
		'sdw_area_mappa',
		'sdw_area_lista_corsi',
		'sdw_area_lista_consigli',
		'sdw_area_lista_prodotti',
		'sdw_area_lista_prodotti_stessa_categoria',
		'sdw_shop_titolo',
		'sdw_lista_prodotti_senza_area',
		'sdw_lista_prodotti_filtro',
		'sdw_area_piede',
	);

	foreach ( $nostri as $tag => $callback ) {
		remove_shortcode( $tag );
		add_shortcode( $tag, $callback );
	}

	foreach ( $vuoti as $tag ) {
		remove_shortcode( $tag );
		add_shortcode( $tag, '__return_empty_string' );
	}
}

function ivanazanetti_sc_tag_prodotto() {
	$tags = get_the_terms( get_the_ID(), 'product_tag' );
	if ( ! $tags || is_wp_error( $tags ) ) {
		return '';
	}

	$nomi = array();
	foreach ( $tags as $tag ) {
		$nomi[] = $tag->name;
	}

	return '<h3 class="p-tag">' . implode( ', ', $nomi ) . '</h3>';
}

function ivanazanetti_sc_iscriversi() {
	ob_start(); ?>
	<section class="sezione-iscriversi" id="iscriversi">
	  <h2 class="s-i-titolo">Come iscriversi.</h2>
	  <div class="row g-4">
	    <div class="col-12 col-md-4 s-i-passo">
	      <h3>Richiesta info</h3>
	      <p>Vai al modulo &ldquo;Richiesta info&rdquo; qua sotto.</p>
	      <a class="s-i-freccia" href="#footer" aria-label="Vai al modulo"><?php print ivanazanetti_icona_img( 'arrow_down', '' ); ?></a>
	    </div>
	    <div class="col-12 col-md-4 s-i-passo">
	      <h3>Compila il form</h3>
	      <p>Inserisci nome e cognome, indirizzo mail e, nel messaggio, il nome e la data del corso.</p>
	    </div>
	    <div class="col-12 col-md-4 s-i-passo">
	      <h3>Invia il messaggio</h3>
	      <p>Dopo aver compilato tutti i campi premi il pulsante INVIA.</p>
	    </div>
	  </div>
	</section>
	<?php
	return ob_get_clean();
}

function ivanazanetti_sc_tag_post() {
	$tags = get_the_tags();
	if ( ! $tags ) {
		return '';
	}

	$nomi = array();
	foreach ( $tags as $tag ) {
		$nomi[] = $tag->name;
	}

	return '<h3 class="p-tag">' . implode( ', ', $nomi ) . '</h3>';
}

/* Le icone delle caratteristiche del corso, come SVG inline: si colorano
   col CSS e non dipendono da file caricati in uploads. */
function ivanazanetti_sc_icona_persone() {
	return '<svg class="p-icona" viewBox="0 0 24 24" aria-hidden="true"><path d="M16 11a4 4 0 10-4-4 4 4 0 004 4zm-8 1a3 3 0 10-3-3 3 3 0 003 3zm8 1c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zM8 14c-.29 0-.62.02-.97.05C5.29 14.55 2 15.84 2 18v3h4v-3c0-1.4.6-2.52 1.5-3.35A9.9 9.9 0 018 14z"/></svg>';
}

function ivanazanetti_sc_icona_date() {
	return '<svg class="p-icona" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2zm0 16H5V10h14zm0-12H5V6h14z"/></svg>';
}

function ivanazanetti_sc_icona_orario() {
	return '<svg class="p-icona" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 1010 10A10 10 0 0012 2zm0 18a8 8 0 118-8 8 8 0 01-8 8zm.5-13H11v6l5.2 3.1.8-1.3-4.5-2.7z"/></svg>';
}

/* Toglie i tag dei moduli Divi tenendo il contenuto che c'e' dentro.
   strip_shortcodes() non va bene: cancellerebbe anche il testo. */
function ivanazanetti_pulisci_divi( $html ) {
	$html = ivanazanetti_divi_immagini( $html );

	return preg_replace( '/\[\/?et_pb_[a-z_]*[^\]]*\]/i', '', $html );
}

/* Gallerie e immagini di Divi hanno le foto negli attributi dello shortcode,
   non nel contenuto: togliendo lo shortcode sparirebbero. Prima di ripulire
   si convertono in HTML del tema.
   - [et_pb_gallery gallery_ids="1,2,3"] -> slider Flickity con frecce
     (data-flickity: Flickity, gia' caricato dal tema, lo avvia da solo)
   - [et_pb_image src="..."]            -> immagine nel testo */
function ivanazanetti_divi_immagini( $html ) {
	if ( strpos( $html, '[et_pb_' ) === false ) {
		return $html;
	}

	$html = preg_replace_callback( '/\[et_pb_gallery\b([^\]]*)\]/i', function ( $m ) {
		$attr = shortcode_parse_atts( $m[1] );
		$ids  = isset( $attr['gallery_ids'] ) ? array_filter( array_map( 'absint', explode( ',', $attr['gallery_ids'] ) ) ) : array();
		$foto = '';
		/* Le foto il cui file non c'e' piu' sul server darebbero una slide vuota. */
		$ids = array_values( array_filter( $ids, function ( $id ) {
			$file = get_attached_file( $id );
			return $file && file_exists( $file );
		} ) );
		foreach ( $ids as $id ) {
			$img = wp_get_attachment_image( $id, 'large', false, array( 'loading' => 'lazy', 'sizes' => '(max-width: 991px) 100vw, 900px' ) );
			if ( $img ) {
				$foto .= '<figure class="pg-foto">' . $img . '</figure>';
			}
		}
		if ( ! $foto ) {
			return '';
		}

		/* Una sola foto: niente slider. */
		if ( count( $ids ) === 1 ) {
			return "\n" . '<div class="post-galleria singola">' . $foto . '</div>' . "\n";
		}

		$opzioni = wp_json_encode( array(
			'wrapAround'     => true,
			'imagesLoaded'   => true,
			'adaptiveHeight' => true,
			'pageDots'       => true,
		) );

		return "\n" . '<div class="post-galleria" data-flickity="' . esc_attr( $opzioni ) . '">' . $foto . '</div>' . "\n";
	}, $html );

	$html = preg_replace_callback( '/\[et_pb_image\b([^\]]*)\]/i', function ( $m ) {
		$attr = shortcode_parse_atts( $m[1] );
		if ( empty( $attr['src'] ) ) {
			return '';
		}
		$alt = isset( $attr['alt'] ) ? $attr['alt'] : ( isset( $attr['title_text'] ) ? $attr['title_text'] : '' );
		$id  = attachment_url_to_postid( $attr['src'] );
		$img = $id
			? wp_get_attachment_image( $id, 'large', false, array( 'loading' => 'lazy', 'alt' => $alt ) )
			: '<img src="' . esc_url( $attr['src'] ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';

		return "\n" . '<figure class="post-immagine">' . $img . '</figure>' . "\n";
	}, $html );

	return $html;
}

/* Il vecchio plugin SDW filtrava lo shop con ?sdw_cat1=<id>&sdw_cat2=<id>:
   quei link sono indicizzati e sparsi nei contenuti. Ora ogni categoria ha il
   suo archivio WooCommerce, quindi i vecchi indirizzi vanno li' con un 301. */
add_action( 'template_redirect', 'ivanazanetti_redirect_sdw_cat' );
function ivanazanetti_redirect_sdw_cat() {
	$id = 0;
	if ( ! empty( $_GET['sdw_cat1'] ) ) {
		$id = absint( $_GET['sdw_cat1'] );
	} elseif ( ! empty( $_GET['sdw_cat2'] ) ) {
		$id = absint( $_GET['sdw_cat2'] );
	}

	if ( ! $id ) {
		return;
	}

	$link = get_term_link( $id, 'product_cat' );
	if ( is_wp_error( $link ) ) {
		$link = get_permalink( wc_get_page_id( 'shop' ) );
	}

	wp_safe_redirect( $link, 301 );
	exit;
}

/* All in One SEO genera la meta description dal contenuto grezzo, che qui
   contiene ancora shortcode Divi: finivano dentro il tag <meta>, visibili
   a Google. Si ripuliscono prima che vengano stampati. */
add_filter( 'aioseo_description', 'ivanazanetti_pulisci_meta' );
add_filter( 'aioseo_og_description', 'ivanazanetti_pulisci_meta' );
add_filter( 'aioseo_twitter_description', 'ivanazanetti_pulisci_meta' );
function ivanazanetti_pulisci_meta( $testo ) {
	if ( ! is_string( $testo ) || $testo === '' ) {
		return $testo;
	}

	$testo = ivanazanetti_pulisci_divi( $testo );
	$testo = wp_strip_all_tags( $testo );

	return trim( preg_replace( '/\s+/', ' ', $testo ) );
}

/* Anche le descrizioni brevi dei prodotti arrivano da Divi. */
add_filter( 'woocommerce_short_description', 'ivanazanetti_pulisci_divi', 5 );

/* =========================================================================
   Meta Pixel — migrato dal plugin SDW dentro il tema.
   Differenza sostanziale rispetto all'originale: parte con il consenso
   REVOCATO. fbq accoda gli eventi e non invia nulla finche' all.js non
   chiama fbq('consent','grant') dopo l'accettazione della categoria
   marketing nella cookie bar.
   ========================================================================= */

define( 'IVANAZANETTI_PIXEL_ID', '1598613741913749' );

/* Finche' il plugin SDW e' installato, i suoi hook del Pixel vanno staccati:
   altrimenti PageView e Purchase partono due volte e le conversioni si
   contano doppie. */
add_action( 'init', 'ivanazanetti_stacca_pixel_sdw', 5 );
function ivanazanetti_stacca_pixel_sdw() {
	remove_action( 'wp_head', 'sdw_meta_pixel_header', 1 );
	remove_action( 'wp_footer', 'sdw_meta_pixel_view_content' );
	remove_action( 'wp_footer', 'sdw_meta_pixel_add_to_cart' );
	remove_action( 'wp_footer', 'sdw_meta_pixel_initiate_checkout' );
	remove_action( 'woocommerce_thankyou', 'sdw_meta_pixel_purchase' );
}

add_action( 'wp_head', 'ivanazanetti_pixel_init', 1 );
function ivanazanetti_pixel_init() {
	$id = IVANAZANETTI_PIXEL_ID;
	?>
	<!-- Meta Pixel -->
	<script>
	!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
	n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
	document,'script','https://connect.facebook.net/en_US/fbevents.js');

	fbq('consent', 'revoke');
	fbq('init', '<?php print esc_js( $id ); ?>');
	fbq('track', 'PageView');
	</script>
	<?php
}

/* ViewContent sulla scheda prodotto. */
add_action( 'wp_footer', 'ivanazanetti_pixel_view_content' );
function ivanazanetti_pixel_view_content() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	$prodotto = wc_get_product( get_the_ID() );
	if ( ! $prodotto ) {
		return;
	}

	$categorie = wp_get_post_terms( $prodotto->get_id(), 'product_cat', array( 'fields' => 'names' ) );
	?>
	<script>
	fbq('track', 'ViewContent', {
		content_name: '<?php print esc_js( $prodotto->get_name() ); ?>',
		content_ids: ['<?php print esc_js( $prodotto->get_id() ); ?>'],
		content_type: 'product',
		content_category: '<?php print esc_js( is_wp_error( $categorie ) ? '' : implode( ', ', $categorie ) ); ?>',
		value: <?php print $prodotto->get_price() ? (float) $prodotto->get_price() : 0; ?>,
		currency: '<?php print esc_js( get_woocommerce_currency() ); ?>'
	});
	</script>
	<?php
}

/* AddToCart: si aggancia all'evento JS di WooCommerce, cosi' funziona anche
   con l'aggiunta via ajax dalla lista prodotti. */
add_action( 'wp_footer', 'ivanazanetti_pixel_add_to_cart' );
function ivanazanetti_pixel_add_to_cart() {
	if ( ! function_exists( 'is_woocommerce' ) ) {
		return;
	}
	?>
	<script>
	(function ($) {
		$(document.body).on('added_to_cart', function (e, fragments, hash, $button) {
			if (typeof fbq !== 'function' || !$button) { return; }

			fbq('track', 'AddToCart', {
				content_ids: [$button.data('product_id')],
				content_type: 'product',
				currency: '<?php print esc_js( get_woocommerce_currency() ); ?>'
			});
		});
	})(jQuery);
	</script>
	<?php
}

/* InitiateCheckout. */
add_action( 'wp_footer', 'ivanazanetti_pixel_checkout' );
function ivanazanetti_pixel_checkout() {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() || is_order_received_page() ) {
		return;
	}

	$carrello = WC()->cart;
	if ( ! $carrello || $carrello->is_empty() ) {
		return;
	}

	$ids = array();
	foreach ( $carrello->get_cart() as $riga ) {
		$ids[] = (string) $riga['product_id'];
	}
	?>
	<script>
	fbq('track', 'InitiateCheckout', {
		content_ids: <?php print wp_json_encode( $ids ); ?>,
		content_type: 'product',
		num_items: <?php print (int) $carrello->get_cart_contents_count(); ?>,
		value: <?php print (float) $carrello->get_total( 'edit' ); ?>,
		currency: '<?php print esc_js( get_woocommerce_currency() ); ?>'
	});
	</script>
	<?php
}

/* Purchase, una sola volta per ordine: un refresh della pagina di conferma
   non deve contare una seconda conversione. */
add_action( 'woocommerce_thankyou', 'ivanazanetti_pixel_purchase' );
function ivanazanetti_pixel_purchase( $order_id ) {
	if ( ! $order_id ) {
		return;
	}

	$ordine = wc_get_order( $order_id );
	if ( ! $ordine || $ordine->get_meta( '_ivanazanetti_pixel_tracked' ) ) {
		return;
	}

	$ids = array();
	foreach ( $ordine->get_items() as $riga ) {
		$ids[] = (string) $riga->get_product_id();
	}
	?>
	<script>
	fbq('track', 'Purchase', {
		content_ids: <?php print wp_json_encode( $ids ); ?>,
		content_type: 'product',
		num_items: <?php print (int) $ordine->get_item_count(); ?>,
		value: <?php print (float) $ordine->get_total(); ?>,
		currency: '<?php print esc_js( $ordine->get_currency() ); ?>'
	});
	</script>
	<?php

	$ordine->update_meta_data( '_ivanazanetti_pixel_tracked', '1' );
	$ordine->save();
}

add_filter( 'auto_theme_update_send_email', '__return_false' );
add_filter( 'auto_plugin_update_send_email', '__return_false' );

/* =========================================================================
   RECENSIONI GOOGLE
   Una volta a settimana il cron di WordPress legge la scheda Google del
   negozio (Places API New) e salva voto, totale e recensioni nell'opzione
   ivanazanetti_recensioni_google. La home legge solo quell'opzione: nessuna
   chiamata a Google mentre il visitatore carica la pagina.
   La chiave sta nel wp-config: define( 'IVANA_GOOGLE_API_KEY', '...' ).
   Google restituisce al massimo 5 recensioni, le "piu' pertinenti".
   Per lanciarla subito: wp eval 'ivanazanetti_sync_recensioni();'
   ========================================================================= */
define( 'IVANAZANETTI_PLACE_ID', 'ChIJ0zfh3p9rgkcRjygSKNzv0gU' );

add_action( 'init', 'ivanazanetti_pianifica_recensioni' );
function ivanazanetti_pianifica_recensioni() {
	if ( ! wp_next_scheduled( 'ivanazanetti_sync_recensioni' ) ) {
		wp_schedule_event( time() + 10 * MINUTE_IN_SECONDS, 'weekly', 'ivanazanetti_sync_recensioni' );
	}
}

/* Cambiando tema il cron non deve restare appeso. */
add_action( 'switch_theme', 'ivanazanetti_togli_cron_recensioni' );
function ivanazanetti_togli_cron_recensioni() {
	wp_clear_scheduled_hook( 'ivanazanetti_sync_recensioni' );
}

add_action( 'ivanazanetti_sync_recensioni', 'ivanazanetti_sync_recensioni' );
function ivanazanetti_sync_recensioni() {
	if ( ! defined( 'IVANA_GOOGLE_API_KEY' ) || ! IVANA_GOOGLE_API_KEY ) {
		return new WP_Error( 'recensioni', 'Manca IVANA_GOOGLE_API_KEY nel wp-config.' );
	}

	$risposta = wp_remote_get( 'https://places.googleapis.com/v1/places/' . IVANAZANETTI_PLACE_ID . '?languageCode=it', array(
		'timeout' => 15,
		'headers' => array(
			'X-Goog-Api-Key'   => IVANA_GOOGLE_API_KEY,
			'X-Goog-FieldMask' => 'rating,userRatingCount,googleMapsUri,reviews',
		),
	) );

	$dati = json_decode( wp_remote_retrieve_body( $risposta ), true );

	/* Se Google non risponde si tengono i dati della settimana prima. */
	if ( is_wp_error( $risposta ) || wp_remote_retrieve_response_code( $risposta ) !== 200 || ! is_array( $dati ) ) {
		$errore = is_wp_error( $risposta ) ? $risposta->get_error_message() : ( isset( $dati['error']['message'] ) ? $dati['error']['message'] : 'risposta non valida' );
		error_log( 'Recensioni Google: ' . $errore );
		return new WP_Error( 'recensioni', $errore );
	}

	$recensioni = array();
	foreach ( (array) ( isset( $dati['reviews'] ) ? $dati['reviews'] : array() ) as $r ) {
		/* Il testo originale, non la traduzione automatica di Google. */
		$testo = isset( $r['originalText']['text'] ) ? $r['originalText']['text'] : ( isset( $r['text']['text'] ) ? $r['text']['text'] : '' );
		$testo = trim( wp_strip_all_tags( $testo ) );

		if ( (int) $r['rating'] < 4 || $testo === '' ) {
			continue;
		}

		$recensioni[] = array(
			'nome'   => isset( $r['authorAttribution']['displayName'] ) ? $r['authorAttribution']['displayName'] : '',
			'link'   => isset( $r['authorAttribution']['uri'] ) ? $r['authorAttribution']['uri'] : '',
			'stelle' => (int) $r['rating'],
			'testo'  => $testo,
		);
	}

	update_option( 'ivanazanetti_recensioni_google', array(
		'voto'       => isset( $dati['rating'] ) ? (float) $dati['rating'] : 0,
		'totale'     => isset( $dati['userRatingCount'] ) ? (int) $dati['userRatingCount'] : 0,
		'url'        => isset( $dati['googleMapsUri'] ) ? $dati['googleMapsUri'] : '',
		'recensioni' => $recensioni,
		'aggiornato' => time(),
	), false );

	return count( $recensioni );
}
