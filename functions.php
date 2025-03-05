<?php
function basil_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );	
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'custom-logo' );

}
add_action( 'after_setup_theme', 'basil_setup' );


// Load front-end assets.
add_action( 'wp_enqueue_scripts', 'basil_assets' );

function basil_assets() {
	$asset = include get_theme_file_path( 'public/css/screen.asset.php' );

	wp_enqueue_style(
		'basil-style',
		get_theme_file_uri( 'public/css/screen.css' ),
		$asset['dependencies'],
		$asset['version']
	);
}

// Load editor stylesheets.
add_action( 'after_setup_theme', 'basil_editor_styles' );

function basil_editor_styles() {
	add_editor_style( [
		get_theme_file_uri( 'public/css/screen.css' )
	] );
}

// Load editor scripts.
add_action( 'enqueue_block_editor_assets', 'basil_editor_assets' );

function basil_editor_assets() {
	$script_asset = include get_theme_file_path( 'public/js/editor.asset.php'  );
	$style_asset  = include get_theme_file_path( 'public/css/editor.asset.php' );

	wp_enqueue_script(
		'basil-editor',
		get_theme_file_uri( 'public/js/editor.js' ),
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	wp_enqueue_style(
		'basil-editor',
		get_theme_file_uri( 'public/css/editor.css' ),
		$style_asset['dependencies'],
		$style_asset['version']
	);
}

function pojo_polylang_get_multilang_logo( $value ) {
	if ( function_exists( 'pll_current_language' ) ) {
		$logos = array(
			'en' => 'logo-en.svg',
			'uk' => 'logo-uk.svg',
		);
		$url = array(
			'en' => '',
			'uk' => 'uk',
		);
		$default_logo = $logos['en'];
		$current_lang = pll_current_language();
		$assets_url = get_stylesheet_directory_uri() . '/assets/images/';
		if ( isset( $logos[ $current_lang ] ) )
			$value = '<a href="/'. $url[ $current_lang ] .'"><img class="top-logo" decoding="async" src="' . $assets_url . $logos[ $current_lang ] . '"/></a>';
		else
			$value = $assets_url . $default_logo;
	}
	return $value;
}
add_filter( 'get_custom_logo', 'pojo_polylang_get_multilang_logo' );