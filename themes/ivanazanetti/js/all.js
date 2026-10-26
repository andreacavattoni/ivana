(function ($) {

  var cookieConsentInstance;

  $(document).ready(function () {

    initializeCookieConsent();

    /* --- menu mobile ----------------------------------------------------- */
    var $ham = $('#header .ham');

    $ham.on('click', function () {
      var aperto = !$(this).hasClass('aperto');

      $(this).toggleClass('aperto', aperto).attr('aria-expanded', aperto);
      $('#fix-menu').toggleClass('aperto', aperto);
      $('body').toggleClass('menu-aperto', aperto);
    });

    /* Chiude il menu quando si clicca una voce. */
    $('#fix-menu a').on('click', function () {
      $ham.removeClass('aperto').attr('aria-expanded', false);
      $('#fix-menu').removeClass('aperto');
      $('body').removeClass('menu-aperto');
    });

    /* --- scroll morbido sulle ancore ------------------------------------- */
    $('a[href^="#"]').on('click', function (e) {
      var target = $(this).attr('href');

      if (target === '#' || target.length < 2) {
        return;
      }

      var $target = $(target);
      if (!$target.length) {
        return;
      }

      e.preventDefault();
      $('html, body').animate({ scrollTop: $target.offset().top }, 600);
    });

  });

  /* =======================================================================
     Cookie bar.
     gtag parte con consenso 'denied' in header.php: e' qui che passa a
     'granted', e solo dopo l'accettazione esplicita.
     ======================================================================= */
  function initializeCookieConsent() {
    if (typeof CookieConsent === 'undefined') {
      return;
    }

    cookieConsentInstance = CookieConsent.run({
      autorun: false,
      guiOptions: {
        consentModal: {
          layout: 'bar',
          position: 'center center',
          equalWeightButtons: true,
          flipButtons: false,
          customStyles: { consentModal: 'centered-popup' }
        },
        preferencesModal: {
          layout: 'box',
          position: 'right',
          equalWeightButtons: true,
          flipButtons: false
        }
      },
      categories: {
        necessary: {
          readOnly: true,
          enabled: true,
          services: {
            wordpress: {
              label: 'Cookie di WordPress',
              description: 'Cookie essenziali utilizzati dal sito per il funzionamento, come la gestione delle sessioni e delle preferenze utente.',
              cookies: [
                { name: /^wordpress_/, domain: window.location.hostname },
                { name: /^wp-settings-/, domain: window.location.hostname },
                { name: /^wp-postpass_/, domain: window.location.hostname },
                { name: /^cc_cookie/, domain: window.location.hostname }
              ]
            },
            woocommerce: {
              label: 'Carrello e ordini',
              description: 'Cookie necessari per mantenere il contenuto del carrello e completare gli acquisti.',
              cookies: [
                { name: /^woocommerce_/, domain: window.location.hostname },
                { name: /^wp_woocommerce_session_/, domain: window.location.hostname }
              ]
            },
            gmaps: {
              label: 'Google Maps API',
              description: 'Necessario per visualizzare la mappa del punto vendita.',
              cookies: [
                { name: /^NID/, domain: '.google.com' },
                { name: /^CONSENT/, domain: '.google.com' }
              ]
            }
          }
        },
        analytics: {
          services: {
            ga: {
              label: 'Google Analytics',
              description: 'Cookie utilizzati per raccogliere informazioni sull’utilizzo del sito a fini statistici.',
              /* Solo analytics_storage: ad_storage lo governa la categoria
                 marketing, altrimenti le due si sovrascrivono a vicenda. */
              onAccept: function () {
                if (typeof gtag === 'function') {
                  gtag('consent', 'update', { analytics_storage: 'granted' });
                }
              },
              onReject: function () {
                if (typeof gtag === 'function') {
                  gtag('consent', 'update', { analytics_storage: 'denied' });
                }
              },
              cookies: [
                { name: /^_ga/, domain: '.google.com' },
                { name: /^_gid/, domain: '.google.com' }
              ]
            }
          }
        },
        marketing: {
          services: {
            metapixel: {
              label: 'Meta Pixel (Facebook e Instagram)',
              description: 'Cookie usati per misurare l’efficacia delle inserzioni e mostrare annunci pertinenti su Facebook e Instagram.',
              onAccept: function () {
                if (typeof fbq === 'function') {
                  fbq('consent', 'grant');
                }
                if (typeof gtag === 'function') {
                  gtag('consent', 'update', { ad_storage: 'granted' });
                }
              },
              onReject: function () {
                if (typeof fbq === 'function') {
                  fbq('consent', 'revoke');
                }
                if (typeof gtag === 'function') {
                  gtag('consent', 'update', { ad_storage: 'denied' });
                }
              },
              cookies: [
                { name: /^_fbp/, domain: window.location.hostname },
                { name: /^_fbc/, domain: window.location.hostname },
                { name: /^fr/, domain: '.facebook.com' }
              ]
            }
          }
        }
      },
      language: {
        default: 'it',
        translations: {
          it: {
            consentModal: {
              title: 'Utilizziamo i cookie',
              description: 'Questo sito utilizza cookie essenziali per garantire il corretto funzionamento del sito e cookie analitici per monitorarne l\'utilizzo. I cookie essenziali sono sempre attivi.',
              acceptAllBtn: 'Accetta tutto',
              acceptNecessaryBtn: 'Accetta solo necessari',
              showPreferencesBtn: 'Gestisci preferenze',
              footer: '<a href="/privacy-policy/">Informativa sulla privacy</a> | <a href="/cookie-policy/">Cookie policy</a>'
            },
            preferencesModal: {
              title: 'Centro preferenze per il consenso',
              acceptAllBtn: 'Accetta tutto',
              acceptNecessaryBtn: 'Accetta solo necessari',
              savePreferencesBtn: 'Salva le preferenze',
              closeIconLabel: 'Chiudi la finestra',
              serviceCounterLabel: 'Servizi',
              sections: [
                {
                  title: 'Utilizzo dei Cookie',
                  description: 'Questo sito utilizza cookie essenziali per garantire il corretto funzionamento e cookie analitici per migliorare l’esperienza utente. I cookie essenziali non possono essere disattivati.'
                },
                {
                  title: 'Cookie Strettamente Necessari <span class="pm__badge">Sempre Attivi</span>',
                  description: 'Questi cookie sono indispensabili per il corretto funzionamento del sito web.',
                  linkedCategory: 'necessary'
                },
                {
                  title: 'Cookie Analitici',
                  description: 'Questi cookie raccolgono dati sull\'uso del sito per migliorare i nostri servizi.',
                  linkedCategory: 'analytics'
                },
                {
                  title: 'Cookie di Marketing',
                  description: 'Questi cookie, come il Meta Pixel di Facebook e Instagram, servono a misurare l’efficacia delle inserzioni e a mostrarti annunci pertinenti.',
                  linkedCategory: 'marketing'
                }
              ]
            }
          }
        }
      }
    });
  }

})(jQuery);
