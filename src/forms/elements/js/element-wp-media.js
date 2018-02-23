;(function($){
	$.fn.gpWpMedia = function(){
		this.each(
			function(){
				var $this     = $( this ),
				$mediaButton  = $this.find( 'button.gp-wpmedia-upload' ),
				$receiver     = $this.find( 'div.gp-wpmedia-receiver' ),
				values        = $this.data( 'wpmedia-value' ),
				item_template = _.template( $this.find( '.tmpl-wpmedia-item' ).html() ),
				args          = $.extend(
					true, {
						title: '',
						multiple: false,
						button: {
							text: ''
						}
					}, $this.data( 'wpmedia-args' )
				),
				selected      = '',
				attachments   = [],
				file_frame;

				values.forEach(
					function( item ){
						selected += item_template( { attributes: item } );
					}
				);
				$receiver.html( selected );

				// props to @mikejolley and @hugosolar for this
				$mediaButton.on(
					'click', function(event){

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
								$this.find( 'input.gp-wpmedia-value' ).each(
									function(i, obj){
										var id     = parseInt( obj.value, 10 ),
										attachment = wp.media.model.Attachment.get( id );
										selection.add( attachment );
									}
								);
							}
						);

						// When an image is selected, run a callback.
						file_frame.on(
							'select', function() {
								var selection = file_frame.state().get( 'selection' ),
								selected      = '';
								$receiver.html( '' );
								selection.forEach(
									function(item){
										selected += item_template( item );
									}
								);
								$receiver.html( selected );
							}
						);

						file_frame.open();
					}
				);
				$this.on(
					'click', 'button.attachment-close', function(){
						$( this ).closest( 'div.gp-wpmedia-item' ).fadeOut(
							'fast', function(){
								$( this ).remove();
							}
						);
					}
				);
			}
		);
	};
	$( '.gp-wp-media' ).gpWpMedia();
})( jQuery );
