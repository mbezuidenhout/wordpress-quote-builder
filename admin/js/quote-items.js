/**
 * Function definitions for the quote lines table.
 *
 * @package    Quote_Builder
 */

var quoteItems = {
	labels: {
		item: {
			singular: 'item',
			plural: 'items'
		},
	}
};

(function( $ ) {
	'use strict';

	function table_rows( table ) {
		return $( table ).find( '.quote-line-item:visible' ).length;
	}

	$(
		function() {
			$( '#quoteitemsdiv .qb-quote-items tbody' ).sortable( { handle: ".handle", opacity: 0.5 } );
			$( '#add-new-quote-entry button' ).on(
				'click',
				function() {
					$( '#quote-no-items' ).hide();
					var newRow = $( '#quote-item' ).clone();
					newRow.removeAttr( 'id' );
					newRow.appendTo( $( '#quoteitemsdiv' ).find( '.qb-quote-items' ) );
					newRow.show();
					var itemCount = table_rows( document.getElementById( 'quote-items-table' ) );
					$( this ).parents().find( '.displaying-num' ).text( itemCount + ' ' + ( 1 === itemCount ? quoteItems.labels.item.singular : quoteItems.labels.item.plural ) );
				}
			);
			$( '#qb-items-doaction' ).on(
				'click',
				function( e ) {
					e.preventDefault();
					var action = $( '#qb-items-bulk-action-selector-top' ).val();
					switch ( action ) {
						case 'delete':
							$( 'input:checkbox[name^="cb_select"]:checked' ).each(
								function() {
									$( this ).closest( 'tr' ).remove();
								}
							);
							break;
					}
					return false;
				}
			);
			$( 'form' ).on(
				'submit',
				function() {
					$( this ).find( '[contenteditable="true"]:visible' ).each(
						function() {
							var value      = this.textContent;
							var lineNumber = $.inArray( $( this ).closest( 'tr' )[0], $( this ).closest( 'table' ).find( '.quote-line-item:visible' ).toArray() );
							var field      = 'quote_line_items[' + lineNumber + '][' + $( this ).closest( 'td' )[0].dataset.colname + ']';
							$( this ).closest( 'form' ).append( '<input type="hidden" name="' + field + '" value="' + value + '" />' );
						}
					);
				}
			);
		}
	);

	$( document ).on(
		'change keydown keypress input' ,
		'p[data-placeholder]',
		function() {
			if (this.textContent) {
				this.dataset.placeholderContent = 'true';
			} else {
				delete( this.dataset.placeholderContent );
			}
		}
	);

})( jQuery );
