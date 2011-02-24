<?php

/**
 * flbb_enqueue_scripts()
 * Enqueue any Javascript we need
 *
 */
function flbb_enqueue_scripts() {
	if ( !is_admin() ) {
		wp_enqueue_script( 'jquery' );
	}
} // END flbb_enqueue_scripts()

add_action( 'init', 'flbb_enqueue_scripts' );

?>