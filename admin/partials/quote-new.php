<?php
/**
 * New quote display
 *
 * This file is used to markup the new quote creation for the plugin.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/admin/partials
 */

$screen = get_current_screen();
$cptobj = get_post_type_object( 'quote' );
$quote  = get_post();

wp_enqueue_script( 'post' );

if ( wp_is_mobile() ) {
	wp_enqueue_script( 'jquery-touch-punch' );
}

// All meta boxes should be defined and added before the first do_meta_boxes() call (or potentially during the do_meta_boxes action).
require_once ABSPATH . 'wp-admin/includes/meta-boxes.php';

register_and_do_post_meta_boxes( $quote );

?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( $cptobj->labels->add_new_item ); ?></h1>
	<hr class="wp-header-end">
	<form name="quote" method="post" id="quote"
		<?php
		/**
		 * Fires inside the post editor form tag.
		 *
		 * @since 3.0.0
		 *
		 * @param WP_Post $post Post object.
		 */
		do_action( 'quote_new_form_tag', $quote );

		$referer = wp_get_referer();
		?>
>
        <input name="action" type="hidden" value="createquote">
		<?php wp_nonce_field( $nonce_action ); ?>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
                <div id="post-body-content" style="position: relative;">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row" class="titledesc"></th>
                                <td class="forminp forminp-text"><input type="text" name="customer"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div id="postbox-container-1" class="postbox-container"></div>
                <div id="postbox-container-2" class="postbox-container">
	                <?php
	                do_meta_boxes( null, 'normal', $quote );
	                ?>
                </div><!-- /postbox-container-2 -->
            </div><!-- /post-body -->
        </div><!-- /poststuff -->
        <?php submit_button( $cptobj->labels->add_new_item, 'primary', 'createquote', true, array( 'id' => 'createquotesub' ) ); ?>
	</form>
</div>
