;(function($){
	$(document).ready(function(){
		$('.queulat-gmapsearch').each(function(){
			var container = $(this),
				map = container.find('.gmapsearch__canvas'),
				input = container.find('.gmapsearch__address'),
				lat = parseFloat( container.find('.gmapsearch__lat').val() ),
				lng = parseFloat( container.find('.gmapsearch__lng').val() ),
				zoom = parseInt( container.find('.gmapsearch__zoom').val(), 10 ),
				center = ! isNaN( lat ) && ! isNaN( lng ) ? new google.maps.LatLng( lat, lng ) : new google.maps.LatLng( -33, -70 );
			var gMap = new google.maps.Map( map[0], {
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: center,
				zoom: isNaN( zoom ) ? 3 : zoom
			} );

			input.css({
				'margin': '6px 0 0',
				'line-height': '22px',
				'font-size': '16px',
				'padding': '5px'
			}).on('keydown', function(event){
				if ( event.keyCode == 13 ) {
					event.preventDefault();
					return false;
				}
			});

			gMap.controls[ google.maps.ControlPosition.TOP_LEFT ].push( input[0] );
			var autocomplete = new google.maps.places.Autocomplete( input[0] ),
				infowindow = new google.maps.InfoWindow(),
				marker = new google.maps.Marker({
					map: gMap,
					anchorPoint: new google.maps.Point( lat, lng )
				});

			marker.setAnimation( google.maps.Marker.DROP );
			marker.setDraggable( true );
			marker.setPosition( new google.maps.LatLng( lat, lng ) );
			marker.setVisible( true );
			google.maps.event.addListener( gMap, 'zoom_changed', function(){
				// copiar nivel de zoom a un input oculto
				container.find('.gmapsearch__zoom').val( gMap.getZoom() );
			} );
			google.maps.event.addListener( autocomplete, 'place_changed', function(){
				var place = autocomplete.getPlace();
				if ( ! place.geometry )
					return;
				gMap.setCenter( place.geometry.location );
				gMap.setZoom( 17 );
				marker.setDraggable(true);
				marker.setPosition( place.geometry.location );
				marker.setTitle( place.name );
				marker.setVisible( true );
				container.find('.gmapsearch__lat').val( place.geometry.location.lat() );
				container.find('.gmapsearch__lng').val( place.geometry.location.lng() );
			});

			google.maps.event.addListener( marker, 'dragend', function(){
				var markerPos = marker.getPosition();
				container.find('.gmapsearch__lat').val( markerPos.lat() );
				container.find('.gmapsearch__lng').val( markerPos.lng() );
			});
		});
	});
})(jQuery);