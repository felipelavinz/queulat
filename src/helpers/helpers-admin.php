<?php

function queulat_url( $path ) : string {
	return plugins_url( $path, dirname( dirname( __FILE__ ) ) );
}
