<?php

if ( ! defined( 'WP_ADMIN' ) ) {
	define( 'WP_ADMIN', true );
}

if ( ! defined( 'WP_NETWORK_ADMIN' ) ) {
	define( 'WP_NETWORK_ADMIN', false );
}

if ( ! defined( 'WP_USER_ADMIN' ) ) {
	define( 'WP_USER_ADMIN', false );
}

if ( ! WP_NETWORK_ADMIN && ! WP_USER_ADMIN ) {
	define( 'WP_BLOG_ADMIN', true );
}

if ( isset( $_GET['import'] ) && ! defined( 'WP_LOAD_IMPORTERS' ) ) {
	define( 'WP_LOAD_IMPORTERS', true );
}

/** WordPress Administration Bootstrap */
require_once dirname( __FILE__, 5 ) . '/wp-load.php';

/** Load WordPress Administration APIs */
require_once ABSPATH . 'wp-admin/includes/admin.php';

send_nosniff_header();
nocache_headers();

/** This action is documented in wp-admin/admin.php */
do_action( 'admin_init' );

$post = get_post( $_REQUEST['post'] );

global $wp_actions;

wp_enqueue_style( 'colors' );
wp_enqueue_style( 'ie' );
wp_enqueue_script( 'utils' );
wp_enqueue_script( 'svg-painter' );

do_action( 'quote_builder_print', $post );