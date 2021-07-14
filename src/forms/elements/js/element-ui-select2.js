;(function($){
	$( document ).ready(
		function(){
			$( '.select-2' ).each(
				function(){
					/**
					 * By default, select2 reorders the selected options.
					 * keepSelectionsOrder:true makes that each new selection is appended
					 */
					var args = $.extend( true, {
						keepSelectionsOrder: true
					}, $( this ).data( 'select2-args' ) );
					$( this ).select2( args );
					if ( args.keepSelectionsOrder ) {
						$( this ).on( 'select2:select', function( e ){
							var id = e.params.data.id;
							var option = $(e.target).children('[value="'+ id +'"]');
							option.detach();
							$( e.target ).append( option ).change();
						} );
					}
				}
			);
		}
	);
})( jQuery );
