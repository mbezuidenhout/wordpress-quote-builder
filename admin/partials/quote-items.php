<?php
/**
 * New quote items display
 *
 * This file is used to markup the quote line items.
 *
 * @link	   https://profiles.wordpress.org/mbezuidenhout/
 * @since	  1.0.0
 *
 * @package	Quote_Builder
 * @subpackage Quote_Builder/admin/partials
 *
 * @var array $columns Defines the table columns to display.
 */

/**
 * Return html of our the table header or footer table rows.
 *
 * @param array $columns An associative array of columns.
 * @param int   $count   Call count.
 */
function quote_lines_header_and_footer( $columns, $count = 0 ) {
	?>
	<tr>
		<td id="cb" class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all-<?php echo esc_attr( $count ); ?>"><?php esc_html_e( 'Select All' ); ?></label>
			<input id="cb-select-all-<?php echo esc_attr( $count ); ?>" type="checkbox">
		</td>
		<?php
		foreach ( $columns as $column_id => $column_heading ) :
			?>
			<th scope="col" class="manage-column column-<?php echo esc_attr( $column_id ); ?>"><?php echo esc_html( $column_heading ); ?></th>
			<?php
		endforeach;
		?>
		<th scope="col" class="manage-column column-handle"></th>
	</tr>
	<?php
}

function quote_lines( $columns, $content = array(), $line_number = null ) {
?>
    <tr id="quote-item<?php esc_attr( null !== $line_number ? ( '-' . $line_number ) : '' ); ?>" class="quote-line-item" style="<?php echo empty( $content ) ? 'display:none;' : ''; ?>">
        <th scope="row" class="check-column">
            <label class="screen-reader-text" for="cb-select<?php esc_attr( null !== $line_number ? ( '-' . $line_number ) : '' ); ?>"><?php esc_html_e( 'Select entry' ); ?></label>
            <input id="cb-select<?php esc_attr( null !== $line_number ? ( '-' . $line_number ) : '' ); ?>" type="checkbox" name="cb_select[]" value="0">
        </th>
		<?php
		foreach ( $columns as $column_id => $column_heading ) :
			?>
            <td class="column-<?php echo esc_attr( $column_id ); ?>" data-colname="<?php echo esc_attr( $column_id ); ?>">
                <p contenteditable="true" data-placeholder="<?php echo esc_attr( $column_heading ); ?>"<?php echo ! empty( $content[ $column_id ] ) ? ' data-placeholder-content="true"': ''; ?>><?php echo esc_html( $content[ $column_id ] ); ?></p>
            </td>
		<?php
		endforeach;
		?>
        <td class="handle column-handle ui-sortable-handle" style="display: table-cell; border-bottom-width: 1px;"><input type="hidden" name="quote_item_id" value="0"></td>
    </tr>
	<?php
}

$quote_lines = get_post_meta( $GLOBALS['post_ID'], 'quote_line_items', true );
?>

<?php wp_nonce_field( 'quote-items', '_wpnonce_quote_items' ); ?>
<div class="tablenav top">
	<div class="alignleft actions bulkactions">
		<label for="bulk-action-selector-top" class="screen-reader-text"><?php esc_html_e( 'Select bulk action' ); ?></label><select name="cb-action" id="qb-items-bulk-action-selector-top">
			<option value="-1"><?php esc_html_e( 'Bulk Actions' ); ?></option>
			<option value="delete"><?php esc_html_e( 'Delete Item', 'quote-builder' ); ?></option>
		</select>
		<input type="submit" id="qb-items-doaction" class="button action" value="<?php esc_attr_e( 'Apply' ); ?>">
	</div>
	<div class="tablenav-pages one-page"><span class="displaying-num">0 items</span></div>
	<br class="clear">
</div>
<table id="quote-items-table" class="widefat fixed striped qb-quote-items" style="border-spacing: 0;">
	<thead>
<?php quote_lines_header_and_footer( $columns, 0 ); ?>
	</thead>
	<tbody>
		<tr id="quote-no-items" class="no-items" style="<?php echo ! empty( $quote_lines ) ? 'display:none;' : ''; ?>">
			<td class="colspanchange" colspan="<?php echo count( $columns ) + 2; ?>"><?php esc_html_e( 'No quote entries found.', 'quote-builder' ); ?></td>
		</tr>
<?php quote_lines( $columns ); ?>
<?php
$number = 0;
foreach ( $quote_lines as $quote_line )
    quote_lines( $columns, $quote_line, $number++ );
?>
	</tbody>
	<tfoot>
<?php quote_lines_header_and_footer( $columns, 1 ); ?>
	</tfoot>
</table>

<p class="hide-if-no-js" id="add-new-quote-entry">
	<button type="button" class="button"><?php esc_html_e( 'Add line', 'quote-builder' ); ?></button>
</p>