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

/**
 * flbb_share_this()
 */
function flbb_share_this() { ?>
	
	<span class='st_facebook' st_title='{TITLE}' st_url='{URL}' ></span><span class='st_twitter' st_title='{TITLE}' st_url='{URL}' ></span><span class='st_email' st_title='{TITLE}' st_url='{URL}' ></span><span class='st_sharethis' st_title='{TITLE}' st_url='{URL}' ></span>

<?php } // END flbb_share_this()

?>