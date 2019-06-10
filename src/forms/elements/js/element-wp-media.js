;(function($){
	$( document ).on(
		'click', 'button.queulat-wpmedia-upload', function( event ){
			var $container = $( this ).closest( '.js-queulat-wp-media' ),
			$mediaButton   = $container.find( 'button.queulat-wpmedia-upload' ),
			$receiver      = $container.find( 'div.queulat-wpmedia-receiver' ),
			values         = $container.data( 'wpmedia-value' ),
			item_template  = _.template( $container.find( '.tmpl-wpmedia-item' ).html() ),
			args           = $.extend(
				true, {
					title: '',
					multiple: false,
					button: {
						text: ''
					}
				}, $container.data( 'wpmedia-args' )
			),
			selected       = '',
			attachments    = [],
			file_frame;

			// props to @mikejolley and @hugosolar for this
			// @see https://mikejolley.com/2012/12/21/using-the-new-wordpress-3-5-media-uploader-in-plugins/
			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			// Create the media frame.
			file_frame = wp.media( args );

			// When opening the media library, check and pre-select existing values
			file_frame.on(
				'open', function(){
					var selection = file_frame.state().get( 'selection' );
					$container.find( 'input.queulat-wpmedia-value' ).each(
						function(i, obj){
							var id     = parseInt( obj.value, 10 ),
							attachment = wp.media.model.Attachment.get( id );
							selection.add( attachment );
						}
					);
				}
			);

			var renderItems = function( items ) {
				var selected = '';
				items.forEach(
					function(item){
						selected += item_template( item );
					}
				);
				return selected;
			}

			// When an image is selected, run a callback.
			file_frame.on(
				'select', function() {
					var selection = file_frame.state().get( 'selection' );
					$receiver.html( renderItems( selection ) );
				}
			);

			file_frame.open();
		}
	).on( 'click', 'button.queulat-wpmedia-item-remove', function( event ){
		event.preventDefault();
		$( this ).closest( 'div.queulat-wpmedia-item' ).fadeOut(
			'fast', function(){
				$(this).remove();
			}
		);
	} ).ready(
		function(){
				$( '.js-queulat-wp-media' ).each(
					function(){
						var values    = $( this ).data( 'wpmedia-value' ),
						item_template = _.template( $( this ).find( '.tmpl-wpmedia-item' ).html() ),
						$receiver     = $( this ).find( 'div.queulat-wpmedia-receiver' ),
						selected      = '';
						if ( ! _.isEmpty( values ) ) {
							values.forEach(
								function( item ){
									if ( item ) {
										selected += item_template( { attributes: item } );
									}
								}
							);
						}
						$receiver.html( selected );
						$( this ).find('.queulat-wpmedia-sortable').sortable({
							containment: 'parent',
							items: '.queulat-wpmedia-sortable-item',
							forcePlaceholerSize: true
						});
					}
				);
		}
	);
})( jQuery );
