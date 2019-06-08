<?php

// $main_menu_url = 'jsforwp-likes-3-1';
//
// function clb_admin_menu() {
//   global $main_menu_url;
// 	add_menu_page(
//     'JS for WP Likes Plugin Page',
//     'JS Likes',
//     'manage_options',
//     $main_menu_url . '.php',
//     'clb_admin_page',
//     'dashicons-heart',
//     76
//   );
// }
// add_action( 'admin_menu', 'clb_admin_menu' );
//
// function clb_admin_page(){
// 	?>
<!-- // 	<div class="wrap">
// 		<h2><?php //esc_html_e( 'JS for WP Likes', 'jsforwp-jquery-ajax' ); ?></h2>
//     <p>Total Likes: <span class="jsforwp-total-likes"><?php //echo get_option( 'clb_likes' ); ?></span></p>
//     <p><a href="#reset-likes" class="jsforwp-reset-likes">Reset Likes to 0</a></p>
// 	</div>
// 	<?php -->
// }
//
// function clb_backend_scripts( $hook ) {
//
//   global $main_menu_url;
//   if( $hook != 'toplevel_page_' . $main_menu_url ) {
//     return;
//   }
//
//   $nonce = wp_create_nonce( 'clb_likes_reset' );
//
//   wp_enqueue_script( 'jsforwp-backend-js', plugins_url( '../js/backend-main.js', __FILE__ ), [], time(), true );
//   wp_localize_script(
//     'jsforwp-backend-js',
//     'clb_globals',
//     [
//       'ajax_url'    => admin_url( 'admin-ajax.php' ),
//       'total_likes' => get_option( 'clb_likes' ),
//       'nonce'       => $nonce
//     ]
//   );
// }
// add_action( 'admin_enqueue_scripts', 'clb_backend_scripts' );
//
// function clb_reset_likes( ) {
//
//   check_ajax_referer( 'clb_likes_reset' );
//
//   update_option( 'clb_likes', 0 );
//
//   $response['total_likes'] = 0;
//   $response['type'] = 'success';
//
//   $response = json_encode($response);
//   echo $response;
//
//   die();
//
// }
// add_action( 'wp_ajax_clb_reset_likes', 'clb_reset_likes' );
