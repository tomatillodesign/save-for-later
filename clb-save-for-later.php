<?php
/*
   Plugin Name: Save Articles for Later
   Version: 1.0.0
   Author: Chris Liu-Beers
   Author URI: https://twitter.com/zgordon
   Description: Save articles to read later, using AJAX (!). Relies on Genesis Theme Framework.
   Text Domain: jsforwp-jquery-ajax
   License: GPLv3
*/

defined( 'ABSPATH' ) or die( 'No direct access!' );




// Setup Save Later button in PHP only for Genesis
add_action('genesis_entry_header', 'clb_add_sfl_button', 14);
function clb_add_sfl_button() {

     if( !is_single() || !is_user_logged_in() ) { return; }

     //Is this article already saved?
     $article_ID = get_the_ID();
     $user = wp_get_current_user();
     $saved_article_ids = get_user_meta($user->ID, 'saved_articles', true);
     $saved_article_id_array = explode(",", $saved_article_ids);
     //print_r($saved_article_id_array);

     if( in_array($article_ID, $saved_article_id_array) ) {
          echo '<div class="saved-button-area"><a href="#" class="btn button clb-remove-save-for-later already-saved" title="Click to remove from your collection">Saved for Later <i class="fas fa-bookmark fa-lg"></i></a>';
          echo '<a href="#" class="btn button clb-save-for-later hide" title="Click to add to your collection">Save for Later <i class="fal fa-bookmark fa-lg"></i></a></div>';
     } else {
          echo '<div class="saved-button-area"><a href="#" class="btn button clb-remove-save-for-later already-saved hide" title="Click to remove from your collection">Saved for Later <i class="fas fa-bookmark fa-lg"></i></a>';
          echo '<a href="#" class="btn button clb-save-for-later" title="Click to add to your collection">Save for Later <i class="fal fa-bookmark fa-lg"></i></a></div>';
     }

}




// Setup Saved Article Archive in PHP only for Genesis
add_action('genesis_entry_content', 'clb_add_saved_content', 14);
function clb_add_saved_content() {

     if( !is_page(183) ) { return; } // enter your page ID here for the archive page
     if( !is_user_logged_in() ) { echo 'Please login to view your saved articles.'; return; }

     $user = wp_get_current_user();
     $saved_article_ids = get_user_meta($user->ID, 'saved_articles', true);
     $saved_article_id_array = explode(",", $saved_article_ids);
     //print_r($saved_article_id_array);

     // Sample WP Query
     $args = array(
          'post_type' => 'post', // enter your custom post type
          'orderby' => 'date',
          'order' => 'DESC',
          'post__in' => $saved_article_id_array,
          'post_status' => 'publish',
          'posts_per_page'=> -1,
     );

     // The Query
     $the_query = new WP_Query( $args );

     // The Loop
     if ( $the_query->have_posts() ) {

          echo '<ul>';

               while ( $the_query->have_posts() ) {

               $the_query->the_post();

               //vars
               $article_ID = get_the_ID();
               $title = get_the_title();
               $date = get_the_date();
               $permalink = get_the_permalink();

               echo '<li><a href="' . $permalink . '">' . $title . '</a> (' . $date . ')</li>';

          }

          echo '</ul>';

     } else {
          echo 'Nothing to see here. Go read some articles and save some for later!';
     }

     wp_reset_postdata();

}





/**
 * The field on the editing screens.
 *
 * @param $user WP_User user object
 */
function clb_usermeta_form_field_saved_articles($user)
{
    ?>
    <h3>Saved Articles</h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="saved_articles">Saved post IDs</label>
            </th>
            <td>
                <input type="text"
                       class="regular-text ltr"
                       id="saved_articles"
                       name="saved_articles"
                       value="<?= esc_attr(get_user_meta($user->ID, 'saved_articles', true)); ?>"
                       title="Saved post IDs, separated by commas please"
                       >
                <p class="description">
                    Saved post IDs, separated by commas please
                </p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * The save action.
 *
 * @param $user_id int the ID of the current user.
 *
 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
 */
function clb_usermeta_form_field_saved_articles_update($user_id)
{
    // check that the current user have the capability to edit the $user_id
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    // create/update user meta for the $user_id
    return update_user_meta(
        $user_id,
        'saved_articles',
        $_POST['saved_articles']
    );
}

// add the field to user's own profile editing screen
add_action(
    'edit_user_profile',
    'clb_usermeta_form_field_saved_articles'
);

// add the field to user profile editing screen
add_action(
    'show_user_profile',
    'clb_usermeta_form_field_saved_articles'
);

// add the save action to user's own profile editing screen update
add_action(
    'personal_options_update',
    'clb_usermeta_form_field_saved_articles_update'
);

// add the save action to user profile editing screen update
add_action(
    'edit_user_profile_update',
    'clb_usermeta_form_field_saved_articles_update'
);



function clb_frontend_saved_for_later_scripts() {

     $user = wp_get_current_user();
     $saved_article_ids = get_user_meta($user->ID, 'saved_articles', true);

  wp_enqueue_script(
    'clb-frontend-saved-for-later',
    plugins_url( '/assets/js/frontend-main.js', __FILE__ ),
    ['jquery'],
    time(),
    true
  );

  wp_enqueue_style(
    'clb-frontend-saved-for-later-styles',
    plugins_url( '/assets/css/frontend-main.css', __FILE__ )
  );

  // Change the value of 'ajax_url' to admin_url( 'admin-ajax.php' )
  // Change the value of 'saved_articles' to get_option( 'jsforwp_likes' )
  // Change the value of 'nonce' to wp_create_nonce( 'clb_saved_articles_nonce' )
  wp_localize_script(
    'clb-frontend-saved-for-later',
    'clb_js_globals',
    [
      'ajax_url'    => admin_url( 'admin-ajax.php' ),
      'saved_articles' => $saved_article_ids,
      'nonce'       => wp_create_nonce( 'clb_saved_articles_nonce' )
    ]
  );
}
add_action( 'wp_enqueue_scripts', 'clb_frontend_saved_for_later_scripts' );


function clb_frontend_saved_for_later( ) {

  // Change the parameter of check_ajax_referer() to 'clb_saved_articles_nonce'
  check_ajax_referer( 'clb_saved_articles_nonce' );

  $user = wp_get_current_user();
  $user_id = $user->ID;
  $saved_article_ids = get_user_meta($user->ID, 'saved_articles', true);
  $url     = wp_get_referer();
  $post_id = url_to_postid( $url );

  if( $saved_article_ids == null ) { $updated_saved_articles = $post_id; }
  else { $updated_saved_articles = $saved_article_ids . ',' . $post_id; }

  $success = update_user_meta(
      $user_id,
      'saved_articles',
      $updated_saved_articles
  );

  $success = true;

  if( true == $success ) {
    $response['saved_articles'] = $saved_article_ids;
    $response['type'] = 'success';
  }

  $response = json_encode( $response );
  echo $response;
  die();

}
// Change 'wp_ajax_your_hook' to 'wp_ajax_clb_frontend_saved_for_later'
// Or change to 'wp_ajax_nopriv_your_hook' to 'wp_ajax_clb_frontend_saved_for_later'
// Change 'your_hook' to 'clb_frontend_saved_for_later'
add_action( 'wp_ajax_clb_frontend_saved_for_later', 'clb_frontend_saved_for_later' );
add_action( 'wp_ajax_nopriv_clb_frontend_saved_for_later', 'clb_frontend_saved_for_later' );







function clb_frontend_remove_saved_for_later( ) {

  // Change the parameter of check_ajax_referer() to 'clb_saved_articles_nonce'
  check_ajax_referer( 'clb_saved_articles_nonce' );

  $user = wp_get_current_user();
  $user_id = $user->ID;
  $saved_article_ids = get_user_meta($user->ID, 'saved_articles', true);
  $url     = wp_get_referer();
  $post_id = url_to_postid( $url );

  $saved_article_id_array = explode(",", $saved_article_ids);

     $key = array_search($post_id, $saved_article_id_array);
     if ($key !== false) {
         unset($saved_article_id_array[$key]);
     }

  $updated_saved_articles = implode( ",", $saved_article_id_array );

  $success = update_user_meta(
      $user_id,
      'saved_articles',
      $updated_saved_articles
  );

  $success = true;

  if( true == $success ) {
    $response['saved_articles'] = $saved_article_ids;
    $response['type'] = 'success';
  }

  $response = json_encode( $response );
  echo $response;
  die();

}

add_action( 'wp_ajax_clb_frontend_remove_saved_for_later', 'clb_frontend_remove_saved_for_later' );
add_action( 'wp_ajax_nopriv_clb_frontend_remove_saved_for_later', 'clb_frontend_remove_saved_for_later' );





//require_once( 'assets/lib/plugin-page.php' );



//* Remove the edit link
add_filter ( 'genesis_edit_post_link' , '__return_false' );
