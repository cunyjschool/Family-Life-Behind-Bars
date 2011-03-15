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

function catch_that_image() {
	 global $post, $posts;
	 $first_img = '';
	 ob_start();
	 ob_end_clean();
	 $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	 $first_img = $matches [1] [0];

	 if(empty($first_img)){ //Defines a default image
	   $first_img = "/images/default.jpg";
	 }
	 return $first_img;
}

?>