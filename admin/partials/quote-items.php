<?php
/**
 * New quote items display
 *
 * This file is used to markup the quote line items.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Quote_Builder
 * @subpackage Quote_Builder/admin/partials
 *
 * @var array $columns Defines the table columns to display.
 */

/**
 * Return html of our the table header or footer table rows.
 *
 * @param array $columns An associative array of columns.
 *
 * @return string
 */
function quote_lines_header_and_footer( $columns ) {
	ob_start();
	?>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all-1"><?php esc_html_e( 'Select All' ); ?></label>
			<input id="cb-select-all-1" type="checkbox">
		</td>
		<?php
		foreach ( $columns as $column_id => $column_heading ) :
			?>
			<th scope="col" class="manage-column column-<?php echo esc_attr( $column_id ); ?>"><?php echo esc_html( $column_heading ); ?></th>
			<?php
		endforeach;
		?>
		<th scope="col" id="handle" class="manage-column column-handle"></th>
	</tr>
	<?php
	return ob_get_clean();
}

?>

<?php wp_nonce_field( 'quote-items', '_wpnonce_quote_items' ); ?>

<table class="widefat fixed striped qb-quote-items" style="border-spacing: 0;">
	<thead>
<?php echo quote_lines_header_and_footer( $columns ); ?>
	</thead>
	<tbody>
		<tr id="quote-no-items" class="no-items">
			<td class="colspanchange" colspan="<?php echo count( $columns ) + 2; ?>"><?php esc_html_e( 'No quote entries found.', 'quote-builder' ); ?></td>
		</tr>
        <tr id="quote-item-0" class="type-quote-entry" style="display:none;">
            <th scope="row" class="check-column">
                <label class="screen-reader-text" for="cb-select-0"><?php esc_html_e( 'Select entry' ); ?></label>
                <input id="cb-select-0" type="checkbox" name="delete_entries[]" value="0">
            </th>
<?php
foreach ( $columns as $column_id => $column_heading ) :
    ?>
    <td class="column-<?php esc_attr_e( $column_id ); ?>" data-colname="<?php esc_html( $column_heading ); ?>"><p contenteditable="true"><?php esc_html_e( $column_heading ); ?></p></p></td>
    <?php
endforeach;
?>
            <td class="handle column-handle ui-sortable-handle" style="display: table-cell; border-bottom-width: 1px;"><input type="hidden" name="quote_item_id" value="0"></td>
        </tr>
	</tbody>
	<tfoot>
<?php echo quote_lines_header_and_footer( $columns ); ?>
	</tfoot>
</table>

<p class="hide-if-no-js" id="add-new-quote-entry">
	<button type="button" class="button"><?php esc_html_e( 'Add line', 'quote-builder' ); ?></button>
</p>