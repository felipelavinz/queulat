;(function($){
	$( document ).ready(
		function(){
			$( '.select-2' ).each(
				function(){
					var args = $.extend( true, { }, $( this ).data( 'select2-args' ) );
					$( this ).select2( args );
				}
			);
		}
	);
})( jQuery );
