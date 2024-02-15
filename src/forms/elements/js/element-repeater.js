;(function($){
	$( document ).ready( function(){
		//- container
		// -- row[i]
		// --- control[i][q]
		var reindexRows = function( $container ) {
			$container.find('.js-queulat-repeater__row').each( function( i, row ){
				$( row ).data( 'row', i );
				reindexRowControls( $( row ) );
			});
		};
		var reindexRowControls = function( $row ) {
			return $row.find('.js-queulat-repeater__control').each( function( i, obj ){
				var rowIndex     = parseInt( $row.data('row'), 10 );
				var nameTemplate = $( obj ).data('name');
				var newName      = nameTemplate.replace('__i__', rowIndex );
				$( obj ).attr( 'name', newName );
				$( obj ).data( 'row', rowIndex );
				if ( $( obj ).hasClass('js-queulat-wp-media') ) {
					var $itemTemplate = $( obj ).find('.tmpl-wpmedia-item');
					var regex = /name=".+?"/
					var newItemTemplate = $itemTemplate.html().replace( regex, 'name=\"'+newName+'\"');
					$itemTemplate.html( newItemTemplate );
				}
			} );
		};
		$( 'body' ).on( 'click', '.js-queulat-repeater__add', function( event ){
			event.preventDefault();
			var $container = $( this ).closest('.js-queulat-repeater');
			var $rows      = $container.find('.js-queulat-repeater__row');
			var $lastRow   = $rows.filter(':last');
			var $clonedRow = $lastRow.clone();
			$clonedRow.data('row', $rows.length );
			$clonedRow.find('div.queulat-wpmedia-item').remove();
			$lastRow.after( $clonedRow );
			reindexRowControls( $clonedRow, true );
			$clonedRow.find('input, select').val('');
			$clonedRow.find('input, select').filter(':first').trigger('focus');
		} ).on( 'click', '.js-queulat-repeater__remove', function( event ){
			var $container = $(this).closest('.js-queulat-repeater');
			var $rows      = $container.find('.js-queulat-repeater__row');
			if ( $rows.length > 1 ) {
				$( this ).closest( '.js-queulat-repeater__row' ).remove();
				reindexRows( $container );
			}
			event.preventDefault();
		} ).on( 'click', '.js-queulat-repeater__up', function( event ){
			event.preventDefault();
			var $row = $( this ).closest( '.js-queulat-repeater__row' );
			if ( parseInt( $row.data('row'), 10 ) === 0 ) {
				return false;
			}
			var $clonedRow = $row.clone();
			var $prev = $row.prev();
			$prev.before( $clonedRow );
			$row.remove();
			reindexRows( $clonedRow.closest('.js-queulat-repeater') );
		} ).on( 'click', '.js-queulat-repeater__down', function( event ){
			event.preventDefault();
			var $row = $( this ).closest( '.js-queulat-repeater__row' );
			if ( parseInt( $row.data('row'), 10 ) === $( this ).closest( '.js-queulat-repeater').find( '.js-queulat-repeater__row').length - 1 ) {
				return false;
			}
			var $clonedRow = $row.clone();
			var $next = $row.next();
			$next.after( $clonedRow );
			$row.remove();
			reindexRows( $clonedRow.closest('.js-queulat-repeater') );
		} );
	} );
})(jQuery);
