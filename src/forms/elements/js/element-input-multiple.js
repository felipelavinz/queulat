;( function($){
	$( document ).ready(
		function(){
			$( 'body' ).on(
				'click', '.queulat-input-multiple__item-delete', function( event ){
					var $container = $( this ).closest( '.js-queulat-input-multiple' );
					if ( $container.find( '.queulat-input-multiple__item' ).length > 1 ) {
						$( this ).closest( '.queulat-input-multiple__item' ).fadeOut(
							'fast', function(){
								$( this ).remove();
							}
						);
					}
					event.preventDefault();
				}
			).on(
				'click', '.queulat-input-multiple__add-new', function( event ){
					var $container = $( this ).closest( '.js-queulat-input-multiple' );
					var $lastItem  = $container.find( '.queulat-input-multiple__item:last' );
					var $clone     = $lastItem.clone();
					$lastItem.after( $clone );
					$clone.find( 'input' ).val( '' ).focus();
					event.preventDefault();
				}
			);
			$( '.js-queulat-input-multiple' ).sortable(
				{
					placeholder: 'queulat-input-multiple__placeholder',
					forcePlaceholderSize: true,
					items: '> .queulat-input-multiple__item'
				}
			);
		}
	);
})( jQuery );
