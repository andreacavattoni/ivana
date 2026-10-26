# ivanazanetti.it — wp-content

Codice su misura del sito [ivanazanetti.it](https://ivanazanetti.it) (WordPress + WooCommerce).

- `themes/ivanazanetti/` — tema del sito, senza page builder: template PHP, campi ACF, Bootstrap 5 (solo griglia).
- `plugins/on-demand-sizes/` — genera le misure delle immagini alla prima richiesta (regole in `.htaccess`, solo Apache).

Plugin richiesti, non inclusi: Advanced Custom Fields PRO, WooCommerce, Contact Form 7, All in One SEO, Variation Swatches for WooCommerce.

La chiave Google per le recensioni va nel `wp-config.php`: `define( 'IVANA_GOOGLE_API_KEY', '...' );`
