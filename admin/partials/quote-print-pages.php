<?php
$columns = Quote_Builder_CPT_Quote::get_instance()->get_columns();

function quote_lines( $columns, $content = array(), $line_number = null ) {
	?>
    <tr class="quote-line-item">
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
?>

<div class="qb-preview">
    <page size="A4">
        <div class="qb-logo"><img src="<?php echo $variables['logo']; ?>" class="qb-logo"></div>
        <div class="qb-heading"><h1><?php echo __( 'Quotation', 'quote-builder' ) ?></h1></div>
        <div class="clear-left"></div>
        <div class="qb-details-left">
            <span>Quote number: </span><span><?php echo $variables['post']->post_title ?></span>
        </div>
        <div class="clear"></div>
        <div class="qb-content">
            <table class="qb-line-items">
                <thead>
                    <tr>
                        <?php
                        foreach ( $columns as $column_id => $column_heading ) :
                            ?>
                            <th scope="col" class="manage-column column-<?php echo esc_attr( $column_id ); ?>"><?php echo esc_html( $column_heading ); ?></th>
                        <?php
                        endforeach;
                        ?>
                    </tr>
                </thead>
                <tbody>
<?php
$quote_line_items = Quote_Builder_CPT_Quote::get_instance()->get_line_items( $variables['post']->ID );

foreach ( $quote_line_items as $quote_line_item ):
    ?>
    <tr>
        <?php
        foreach ( $columns as $column_id => $column_heading ):
            ?>
        <td><?php echo $quote_line_item[ $column_id ] ?></td>
            <?php
        endforeach;
        ?>
    </tr>
    <?php
endforeach;
?>
                </tbody>
            </table>
        </div>
    </page>
</div>
<div class="clear"></div>