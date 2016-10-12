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

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/dist/styles.min.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400&subset=latin-ext" rel="stylesheet">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

    <?php wp_head(); ?>

    <!-- END head -->
</head>

<!-- BEGIN body -->
<body <?php body_class(); ?>>

    <div class="container">
	<?php include_once( "analyticstracking.php" ) ?>
