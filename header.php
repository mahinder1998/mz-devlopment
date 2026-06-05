<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class('font-sans text-black bg-white'); ?>>

<?php wp_body_open(); ?>

<?php get_template_part('template-parts/header/topbar'); ?>
<?php get_template_part('template-parts/header/main-header'); ?>
<?php get_template_part('template-parts/header/mobile-menu'); ?>
<?php get_template_part('template-parts/header/mega-menu'); ?>
<?php get_template_part('template-parts/header/search-drawer'); ?>