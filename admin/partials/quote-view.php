<?php
/**
 * @var WP_Post $post Instance of the custom post type, quote.
 * @var string  $logo URL location of the logo.
 */


?>
<a role="button" class="btn btn-primary" href="<?php echo plugin_dir_url( __DIR__ ) . 'print.php?post=' . $variables['post']->ID ?>"><?php echo __( 'Print', 'quote-builder' ); ?></a>

<?php
include 'quote-print-pages.php';
