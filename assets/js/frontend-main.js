( function( $ ){

  // Change the html() value to jsforwp_globals.total_likes
  //$( '.jsforwp-count' ).html( clb_js_globals.saved_articles );

  $('.clb-save-for-later').click( function(){

    event.preventDefault();
    //console.log(clb_js_globals);

    // Change url to jsforwp_globals.ajax_url
    // Change data.action to 'jsforwp_add_like'
    // Change data._ajax_nonce to jsforwp_globals.nonce
    $.ajax({
      type : 'post',
      dataType : 'json',
      url : clb_js_globals.ajax_url,
      data : {
        action: 'clb_frontend_saved_for_later',
        _ajax_nonce: clb_js_globals.nonce
      },
      success: function( response ) {

         if( 'success' == response.type ) {

           // Change the html() value to response.total_likes
           $(".clb-save-for-later").toggleClass("hide");
           $(".clb-remove-save-for-later").toggleClass("hide");
           //console.log( response.saved_articles );

         }
         else {
            alert( 'Something went wrong!' );
         }

      }
    })

  } );

} )( jQuery );




// Remove Saved for Later
( function( $ ){

  // Change the html() value to jsforwp_globals.total_likes
  //$( '.jsforwp-count' ).html( clb_js_globals.saved_articles );

  $('.clb-remove-save-for-later').click( function(){

    event.preventDefault();
    console.log('Remove article NONCE: ' + clb_js_globals.nonce);

    // Change url to jsforwp_globals.ajax_url
    // Change data.action to 'jsforwp_add_like'
    // Change data._ajax_nonce to jsforwp_globals.nonce
    $.ajax({
      type : 'post',
      dataType : 'json',
      url : clb_js_globals.ajax_url,
      data : {
        action: 'clb_frontend_remove_saved_for_later',
        _ajax_nonce: clb_js_globals.nonce
      },
      success: function( response ) {

         if( 'success' == response.type ) {

           // Change the html() value to response.total_likes
           $(".clb-save-for-later").toggleClass("hide");
           $(".clb-remove-save-for-later").toggleClass("hide");
           console.log( response.saved_articles );

         }
         else {
            alert( 'Something went wrong!' );
         }

      }
    })

  } );

} )( jQuery );
