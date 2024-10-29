jQuery( document ).ready( function() {  

	jQuery( '#avatar_url' ) .attr( 'readonly', 'readonly' );
	jQuery( '#upload_button' ).click( function() {  
		tb_show( bawggi_l10n.tb_title, 'media-upload.php?referer=wp-settings&type=image&TB_iframe=true&post_id=0', false );  
		return false;  
	}); 
	jQuery( '#reset_button' ).click( function(){
		var $def = jQuery( '#avatar_url' ).attr( 'data-default' );
		jQuery( '#avatar_url' ).val( $def );  
		jQuery( '#hidden_help_save' ).hide();
		jQuery( '.option-page:last' ).attr( 'src', $def );  
	});
	window.send_to_editor = function( html ) {  
		var $image_url = jQuery( 'img', '<a href="#">'+html+'</a>' ).attr( 'src' );  
		jQuery( '#avatar_url' ).val( $image_url );  
		jQuery( '.option-page:last' ).attr( 'src', $image_url );  
		jQuery( '#hidden_help_save' ).show();
		tb_remove();  
	}  			
});