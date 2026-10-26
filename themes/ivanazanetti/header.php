<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php ivanazanetti_schema_type(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="true">
<meta name="theme-color" content="#BE8991">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Nunito:wght@700&display=swap">
<?php wp_head(); ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-WPN8MFLBSF"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-WPN8MFLBSF');
  gtag('consent', 'default', {
   ad_storage: 'denied',
   analytics_storage: 'denied',
 })
</script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="wrapper" class="hfeed">

<header id="header" role="banner">
  <div class="h-interno">
    <a class="logo" href="<?php print home_url( '/' ); ?>">
      <img src="<?php print get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo( 'name' ); ?>">
    </a>

    <nav id="menu" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
      <?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'link_before' => '<span itemprop="name">', 'link_after' => '</span>' ) ); ?>
    </nav>

    <button class="ham" type="button" aria-label="Menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<nav id="fix-menu" role="navigation" aria-label="Menu mobile">
  <?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'container' => false ) ); ?>
</nav>

<div id="container">
<main id="content" role="main">
