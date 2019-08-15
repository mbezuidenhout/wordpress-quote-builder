/**
 * Function definitions for the quote lines table.
 *
 * @package    Quote_Builder
 */

(function( $ ) {
	'use strict';

	$(
		function() {
			$( '#quoteitemsdiv .qb-quote-items tbody' ).sortable({ handle: ".handle" });
			$( '#add-new-quote-entry button' ).on(
				'click',
				function() {
					$( '#quote-no-items' ).hide();
					var newRow = $( '#quote-item-0' ).clone();
					newRow.appendTo( $( '#quoteitemsdiv' ).find( '.qb-quote-items' ) );
					newRow.show();
				}
			);
		}
	);


})( jQuery );
