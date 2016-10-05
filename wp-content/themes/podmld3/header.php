<!DOCTYPE html>
<?php $options = get_option('basic'); ?>
<!-- BEGIN html -->
<html class="full" <?php language_attributes(); ?>>

<!-- BEGIN head -->
<head>
    <!-- Meta Tags -->
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/dist/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/dist/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="<?php echo get_stylesheet_directory_uri() ?>/dist/js/bootstrap.min.js"></script>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" media="screen"/>

    <?php wp_head(); ?>


    <!-- END head -->
</head>

<!-- BEGIN body -->
<body <?php body_class(); ?>>

    <div class="container">
	<?php include_once("analyticstracking.php") ?>
