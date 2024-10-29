<?php
/*
Plugin Name: BAW Gravatar Google image
Plugin URI: http://www.BoiteaWeb.fr/ggi
Description: Add a step between "no gravatar account" and "default gravatar sent back", using Google Images Search Service + 2 bonus usage (FR+EN)
Version: 1.9.3
Author: juliobox
Author URI: http://www.BoiteaWeb.fr
*/

$__uploads = wp_upload_dir();
$GLOBALS['bawggi_upload_dir'] = $__uploads['basedir'] . '/gravatars/';
$GLOBALS['bawggi_upload_url'] = $__uploads['baseurl'] . '/gravatars/';
$GLOBALS['avatar_google'] = get_option( 'avatar_google' );
$GLOBALS['avatar_url'] = get_option( 'avatar_url' );
$GLOBALS['gravatars_cache'] = get_option( 'gravatars_cache' );
unset( $__uploads );
define( 'BAWGGI_PLUGIN_URL', trailingslashit( WP_PLUGIN_URL ) . basename( dirname( __FILE__ ) ) );

function bawggi_gravatar_google_image( $avatar_defaults )
{
    $avatar_defaults[BAWGGI_PLUGIN_URL . '/tyniaf.php'] = __( 'TurnYourNameIntoAFace<em>(.com)</em> (Generated)', 'bawggi' );
	$def = admin_url( '/images/logo.gif' );
	$avatar_url = $GLOBALS['avatar_url'];
	$avatar_url = !empty( $avatar_url ) ? esc_url( $avatar_url ) : $def;
    $avatar_defaults[$avatar_url] = '</label>'. // label hack to avoid checkbox click when click on the text input. 1/2
							'<a name="ggi"></a>'. // #ggi anchor
							'<input type="text" id="avatar_url" data-default="' . $def . '" name="avatar_url" size="60" value="'. $avatar_url . '" />'."\n". 
							'<input class="button-primary hide-if-no-js" id="upload_button" type="button" value="' . __( 'Upload your own', 'bawggi' ) . '" />'.
							'<input class="button-secondary hide-if-no-js" id="reset_button" type="button" value="' . __( 'Reset' ) . '" />'.
							'<span class="description hide-if-js"><em>' . __( 'Leave blank and save to reset.', 'bawggi' ) . '</em></span>'.
							'<span class="description hidden" id="hidden_help_save"><em>' . __( 'Do not forget to save!', 'bawggi' ) . '</em></span>'.
							'<label>'; // same, 2/2
    return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'bawggi_gravatar_google_image', 999 );


function bawggi_default_avatar_select( $content )
{
	add_settings_field( 'bawggi_gravatars_first_grab_field', __( 'New behavior', 'bawggi' ), 'bawggi_gravatars_first_grab_field', 'discussion', 'avatars' );
	add_settings_field( 'bawggi_gravatars_cache_field', __( '(Gr)Avatars Caching', 'bawggi' ), 'bawggi_gravatars_cache_field', 'discussion', 'avatars' );
	add_settings_field( 'bawggi_gravatars_help_field', __( 'Read help and about', 'bawggi' ), 'bawggi_gravatars_help_field', 'discussion', 'avatars' );
	return $content;
}
add_filter( 'default_avatar_select', 'bawggi_default_avatar_select' );

function bawggi_gravatars_help_field()
{ 	
	$help = __(	'<h3 class="hndle" style="padding:5px;"><span>Help and informations</span></h3>'.
				'<div class="inside">'.
					'<p>How does this work, when some one send a comment on a post with a valid email:'.
						'<ol>'.
						'<li>If this email is linked to a Gravatar account, his(her) avatar will be displayed.</li>'.
						'<li>If this email is not linked, Gravatar will send back a default avatar, depending on your choice (Mystery Man, Gravatar Logo, Identicon, Wavatar, MonsterID, Retro ...)</li>'.
						'<li>Now if you choose "Use Google Image Service" and if the email is not linked, a Google Images Service request will be sent to get the first best relevance face photo associated to this email (safe mode set to high level).</li>'.
						'<li>The user will receive a mail with 3 links:</li>'.
							'<ol>'.
								'<li>If this avatar is not relevant, 1st link can be use to delete it.</li>'.
								'<li>If the user changed his mind and create a Gravatar account, the 2nd link can be used to refresh it.</li>'.
								'<li>The 3rd is a invitation to create a Gravatar account.</li>'.
							'</ol>'.
						'<li>If no picture have been found on google (bad!), the default chosen gravatar will be displayed, as usual.</li>'.
						'<li>If you choose "Only default gravatars", no real pictures will be displayed, only default (use generated !).</li>'.
						'<li>If you let Gravatar does its job, you will have the same behavior as without this plugin activated (but the cache system).</li>'.
					'</p>'.
					'<p>About cache system:'.
						'<ol>'.
							'<li>I do not recommand to disable to cache system because a Google Image request cost, so you need to cache pictures !</li>'.
							'<li>If you only cache Google Images, when a user change or add a Gravatar to its email, you and he will not need any action.</li>'.
							'<li>If you also add reals gravatars, existing good photo profile from gravatar will be cached.</li>'.
							'<li>If you add all, even default avatars will be stored as file. But this choice reduce the HTTP request on gravatar\'s servers a lot!</li>'.
							'<li>If you change any setting, behavior or cache i recommand to clear the cache (i did not want to force it).</li>'.
						'</ol>'.
					'</p>'.
					'<p>Other informations:'.
						'<ol>'.
							'<li>On plugin deletion (not just deactivation), the cache folder will be deleted if rights (chmod) are good.</li>'.
							'<li>Also you can add you own default avatar, it will be stored in the default upload folder and visible in medias menu.</li>'.
						'</ol>'.
					'</p>'.
				'</div>', 
			'bawggi' );
	$about = '<h3 class="hndle" style="padding:5px;"><span>About me</span></h3>
			<div class="inside">
				<p><img src="http://www.gravatar.com/avatar/'.md5( 'julio'.'bosk'.'@'.'gmail'.'.'.'com' ).'" style="float:left;margin-right:10px;"/>
				<strong>Julio Potier</strong><br />
				I\'m a Web Security Consultant and WordPress Expert. I create plugins every day, i clean Web sites from hackers every day. I\'m workaholic!<br />
				<a href="http://www.boiteaweb.fr" target="_blank" title="BoiteAWeb.fr - WordPress Security Blog"><img src="https://dl.dropbox.com/u/45956904/plugins/bawlogo.png" /></a><br />
				<a href="http://profiles.wordpress.org/juliobox/" title="on WordPress.org">My WordPress Profile</a></p>
			</div>';
	$plugins = '<h3 class="hndle" style="padding:5px;"><span>Check this plugins too</span></h3>
	<div class="inside">
		<ul>
			<li><a href="http://baw.li/pvc">BAW Post Views Count</a> - <em>Count views on your posts, pages, CPT. Widget, shortcode included. More than 5000 DL!</em></li>
			<li><a href="http://baw.li/mrp">BAW Manual Related Posts</a> - <em>Manually choose which posts will be linked to every posts, out auto selection, out random selection, out fake smart selection.!</em></li>
			<li><a href="http://baw.li/eic">BAW Easy Invitation Codes</a> - <em>Visitors have to enter an invitation code to register on your blog. The easy way!</em></li>
			<li><a href="http://baw.li/msl">BAW More Secure Login</a> - <em>Add a second authentication layer to your WordPress site.</em></li>
			<li><a href="http://baw.li/llm">BAW Login Logout Menu</a> - <em>Add real login and log out links into our WordPress menus.</em></li>
			<li><a href="http://baw.li/modorole">BAW Moderator Role</a> - <em>Add the missing "Comments Moderator" role into your Blog.</em></li>
			<li><a href="http://baw.li/bact">BAW Better Admin Color Themes</a> - <em>Add more themes schemes and icons schemes into your dashboard.</em></li>
			<li><a href="http://baw.li/like">BAW Like Unlike</a> - <em>Add boutons for "Like" or "Unlike" (can be set up) your posts and pages.</em></li>
			<li><a href="http://baw.li/wpsc">BAW WordPress Plugin Security Checker</a> - <em>Check if the desired plugin is secure or not.</em></li>
			<li><a href="http://autoshort.com">BAW AutoShortener</a> - <em>AutoShortener can create short links in your WordPress website for your posts and pages but also for external links.</em></li>
			<li><a href="http://baw.li/gauthor">BAW Google Author</a> - <em>Add a META tag Author for SEO reasons.</em></li>
			<li><a href="http://baw.li/papii">BAW PAPII</a> - <em>Add some shortcodes to load information about a plugin from the repository. </em></li>
		</ul>
	</div>';
?>
	<span class="hide-if-no-js"><a href="javascript:void(0);" onclick="javascript:jQuery(this).remove();jQuery('#bawggi_help').slideDown();"><b>Gravatar Google Image : <?php _e( 'Read help and about', 'bawggi' ); ?></a></b></span>
	<div id="bawggi_help" class="postbox hide-if-js" style="width:70%;"><?php echo $help; ?><br /><?php echo $about; ?><br /><?php echo $plugins; ?></div>
<?php
}

function bawggi_gravatars_first_grab_field()
{
?>
	<label><input type="radio" value="on" name="avatar_google" <?php checked( $GLOBALS['avatar_google'], 'on' );?>/> <?php _e( 'Use Google Image Service to get a photo when users do not have one. <em>(This plugin default)</em>', 'bawggi' ); ?></label><br />
	<label><input type="radio" value="own" name="avatar_google" <?php checked( $GLOBALS['avatar_google'], 'own' ); ?>/> <?php _e( 'Use only default Gravatars (choose above), do not use Google Image or Real Gravatars pictures.', 'bawggi' ); ?></label><br />
	<label><input type="radio" value="none" name="avatar_google" <?php checked( $GLOBALS['avatar_google'], 'none' ); ?>/> <?php _e( 'Just let Gravatar do his job ... <em>(WordPress default)</em>', 'bawggi' ); ?></label><br />
<?php
}

function bawggi_gravatars_cache_field()
{ 
	$nb_files = max( count( glob( $GLOBALS['bawggi_upload_dir'] . '*.*' ) )-1, 0 );
	?>
	<label><input type="radio" value="off" name="gravatars_cache" <?php checked( $GLOBALS['gravatars_cache'], 'off' ); ?> /> <?php _e( 'Do not use cache folder system (Bad performance, not recommanded).', 'bawggi' ); ?></label><br />
	<label><input type="radio" value="google" name="gravatars_cache" <?php checked( $GLOBALS['gravatars_cache'], 'google' ); ?> /> <?php _e( 'Add only Google Images into the cache folder.', 'bawggi' ); ?></label><br />
	<label><input type="radio" value="no_def" name="gravatars_cache" <?php checked( $GLOBALS['gravatars_cache'], 'no_def' ); ?> /> <?php _e( 'Add Real gravatars and Google Images into the cache folder (Recommanded).', 'bawggi' ); ?></label><br />
	<label><input type="radio" value="on" name="gravatars_cache" <?php checked( $GLOBALS['gravatars_cache'], 'on' ); ?> /> <?php _e( 'Add Real Gravatars, Default gravatars and Google Images (all!) into the cache folder (Best HTTP performance).', 'bawggi' ); ?></label><br />
	<em><?php printf( __( 'Cache folder path: <code>%s</code>', 'bawggi' ), $GLOBALS['bawggi_upload_dir'] ); ?></em>
	<p><a href="<?php echo admin_url( wp_nonce_url( 'options-discussion.php?act=empty_cache', 'empty_cache' ) ); ?>" class="button"><?php printf( __( 'Empty cache (%d files)', 'bawggi' ), $nb_files ); ?></a></p>
<?php
}

function bawggi_esc_url( $url )
{
	$def = admin_url( '/images/logo.gif' );
	$url = empty( $url ) ? $def : esc_url( $url );
	if( strstr( get_option( 'avatar_default' ), 'http://' )!='' && get_option( 'avatar_default' )!=BAWGGI_PLUGIN_URL . '/tyniaf.php' )
		update_option( 'avatar_default', $url );
	return $url;
}

function bawggi_add_option_whitelist()
{
	register_setting( 'discussion', 'avatar_google' );
	register_setting( 'discussion', 'avatar_url', 'bawggi_esc_url' );
	register_setting( 'discussion', 'gravatars_cache' );
	load_plugin_textdomain( 'bawggi', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	global $pagenow;
	if( $pagenow == 'media-upload.php' || $pagenow == 'async-upload.php' )
        add_filter( 'gettext', 'replace_thickbox_text', 1, 2 ); 
	if( isset( $_GET['_wpnonce'], $_GET['act'] ) && wp_verify_nonce( $_GET['_wpnonce'], $_GET['act'] ) )
		bawggi_clear_cache();
	if( isset( $_GET['act'] ) && $_GET['act']=='cache_emptied' )
		add_action( 'admin_notices', create_function( '', 'echo "<div class=\"updated\"><p>' . __( 'Cache emptied', 'bawggi' ) . '</p></div>";' ) ); 
}
add_action( 'admin_init', 'bawggi_add_option_whitelist' );

function bawggi_get_avatar( $avatar )
{
	$avatar = str_replace( 'height=', 'data-height=', $avatar );
	return $avatar;
}
if( get_option( 'avatar_default' )==BAWGGI_PLUGIN_URL . '/tyniaf.php')
	add_filter( 'get_avatar', 'bawggi_get_avatar', 10, 1 );

function bawggi_admin_enqueue_scripts()
{
	if( $GLOBALS['pagenow'] == 'options-discussion.php' ):
		wp_enqueue_script( 'jquery' );  
		wp_enqueue_script( 'thickbox' );  
		wp_enqueue_script( 'media-upload' ); 
		wp_enqueue_script( 'bawggi', BAWGGI_PLUGIN_URL . '/js/bawggi.js', null, '1.9' );
		wp_localize_script('bawggi', 'bawggi_l10n', array( 'tb_title' => __( 'Upload your default avatar', 'bawggi' ) ) );
	endif;
}  
add_action( 'admin_footer', 'bawggi_admin_enqueue_scripts' );

function bawggi_admin_enqueue_styles()
{
	if( $GLOBALS['pagenow'] == 'options-discussion.php' )
		wp_enqueue_style( 'thickbox' ); 
}  
add_action( 'admin_print_styles', 'bawggi_admin_enqueue_styles' );

function replace_thickbox_text( $translated_text, $text )
{
    if( $text == 'Insert into Post' && strpos( wp_get_referer(), 'wp-settings' ) != '' )
		return __( 'I want this to be my default avatar!', 'bawggi' );  
    return $translated_text;  
}

function bawggi_comment_row_actions( $actions, $comment )
{
	if( current_user_can( 'administrator' ) || current_user_can( 'moderator' ) ): //moderator = http://baw.li/modorole
		global $bawggi_upload_dir;
		$files = glob( $bawggi_upload_dir . md5( strtolower( $comment->comment_author_email ) ) . '.*' );
		$new_actions = array(	'refresh_avatar' => __( 'Refresh avatar', 'bawggi' ),
								'delete_avatar' => __( 'Delete avatar', 'bawggi' ),
								'mailto_avatar' => __( 'Email avatar warning', 'bawggi' )
							);
		if( count( $files ) == 1 && file_exists( $files[0] ) )
			foreach( $new_actions as $action=>$text ):
				$nonce = substr( md5( $comment->comment_author_email . NONCE_SALT . $action ), 1, 8 );
				$href = admin_url( sprintf( 'edit-comments.php?comment_author_email=%s&nonce=%s&act=%s', urlencode( $comment->comment_author_email ), $nonce, $action ) );
				$actions[sanitize_key( $action )] = sprintf( '<a href="%s">%s</a>', $href, $text );
			endforeach;
	endif;
	return $actions;
}
add_filter( 'comment_row_actions', 'bawggi_comment_row_actions', 10, 2 );

if ( !function_exists( 'get_avatar' ) ) : 
function get_avatar( $id_or_email, $size = '96', $default = '', $alt = false, $send_type = false )
{
	if ( ! get_option('show_avatars') )
		return false;

	if ( false === $alt)
		$safe_alt = '';
	else
		$safe_alt = esc_attr( $alt );

	if ( !is_numeric($size) )
		$size = '96';

	$email = '';
	if ( is_numeric($id_or_email) ) {
		$id = (int) $id_or_email;
		$user = get_userdata($id);
		if ( $user )
			$email = $user->user_email;
	} elseif ( is_object($id_or_email) ) {
		// No avatar for pingbacks or trackbacks
		$allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
		if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) )
			return false;

		if ( !empty($id_or_email->user_id) ) {
			$id = (int) $id_or_email->user_id;
			$user = get_userdata($id);
			if ( $user)
				$email = $user->user_email;
		} elseif ( !empty($id_or_email->comment_author_email) ) {
			$email = $id_or_email->comment_author_email;
		}
	} else {
		$email = $id_or_email;
	}
	
	$avatar_response = array( 'status' => 'waiting', 'type' => '', 'cache' => false, 'href' => '', 'html' => '' );
	if( $GLOBALS['gravatars_cache']!='off' && $GLOBALS['pagenow']!='options-discussion.php'):
		global $bawggi_upload_url, $bawggi_upload_dir;
		$files = glob( $bawggi_upload_dir . md5( strtolower( $email ) ) . '.*' );
		if( count( $files ) == 1 && file_exists( $files[0] ) ):
			$ext = '.' . strtolower( substr( strrchr( $files[0], '.' ), 1 ) );
			$img = $bawggi_upload_url . md5( strtolower( $email ) ) . $ext;
			$infos = @getimagesize( $files[0] );
			if( $infos ):
				$avatar_response['type'] = 'google';
				$avatar_response['status'] = 'present';
				$avatar_response['href'] = $img;
			else:
				$avatar_response['status'] = 'deleted';
			endif;
		endif;
		do_action( 'gravatar_from_cache', $avatar_response );
	endif;
	
	if( $avatar_response['status'] != 'present' ):
		if( empty( $default ) ) {
			$avatar_default = get_option( 'avatar_default' );
			if( empty( $avatar_default ) )
				$default = 'mystery';
			else
				$default = $avatar_default;
		}
		$email_hash = '';
		if( !empty($email) )
			$email_hash = md5( strtolower( trim( $email ) ) );

		if( is_ssl() ) {
			$host = 'https://secure.gravatar.com/avatar/';
		} else {
			if( !empty( $email ) )
				$host = sprintf( "http://%d.gravatar.com/avatar/", ( hexdec( $email_hash[0] ) % 2 ) );
			else
				$host = 'http://0.gravatar.com/avatar/';
		}
		
		if( $GLOBALS['pagenow']=='options-discussion.php' )
			$email = 'forcedefault';
		if ( 'mystery' == $default )
			$default = "{$host}ad516503a11cd5ca435acc9bb6523536?s={$size}"; // ad516503a11cd5ca435acc9bb6523536 == md5('unknown@gravatar.com')
		elseif ( 'blank' == $default )
			$default = includes_url('images/blank.gif');
		elseif ( !empty($email) && 'gravatar_default' == $default )
			$default = '';
		elseif ( 'gravatar_default' == $default )
			$default = "{$host}?s={$size}";
		elseif ( empty($email) )
			$default = "{$host}?d={$default}&amp;s={$size}";
		elseif ( strpos($default, 'http://') === 0 ){
			$default = add_query_arg( 's', $size, $default );
			$default = add_query_arg( 'email', $email, $default );
		}

		if( $GLOBALS['avatar_google']=='own' )
			$email_hash = md5($email.NONCE_SALT);
		$out = $host;
		$out .= $email_hash;
		$out .= '?s='.$size;
		$default = str_replace( '%25email%25', $email, $default );
		$out .= '&d=' . urlencode( $default );
		$test_gravatar = $host . $email_hash . '?d=404';
		$rating = get_option('avatar_rating');
		if ( !empty( $rating ) )
			$out .= "&amp;r={$rating}";
		$avatar_response['href'] = $out;
		$avatar_response['cache'] = $GLOBALS['gravatars_cache']=='on';
		$avatar_response['type'] = 'gravatar-default';

		if( $avatar_response['status']!='deleted' && $GLOBALS['avatar_google']=='on' && $GLOBALS['pagenow']!='options-discussion.php' ):
			$test_response = @get_headers( $test_gravatar );
			if ( preg_match( '|404|', $test_response[0] ) ):
				$s = is_ssl() ? 's' : '';
				$service_url = sprintf( 'http%s://ajax.googleapis.com/ajax/services/search/images?v=1.0&imgtype=face&safe=active&hl=fr&q=%s', $s, urlencode( $email ) );
				$json = @file_get_contents( $service_url );
				$json = json_decode( $json );		
				$photo = isset( $json->responseData->results ) && count( $json->responseData->results ) > 0 ? $json->responseData->results[0]->unescapedUrl : '';
				if( !empty( $photo ) ):
					$avatar_response['type'] = 'google';
					$avatar_response['cache'] = $GLOBALS['gravatars_cache']!='off';
					$avatar_response['href'] = $photo;
				else:
					$avatar_response['type'] = 'gravatar';
					$avatar_response['cache'] = $GLOBALS['gravatars_cache']=='on';
				endif;
			elseif( $GLOBALS['gravatars_cache']=='on' || $GLOBALS['gravatars_cache']=='no_def' ):
				$avatar_response['href'] = $test_gravatar;
				$avatar_response['cache'] = $GLOBALS['gravatars_cache']=='on' || $GLOBALS['gravatars_cache']=='no_def';
			endif;
			do_action( 'gravatar_from_google', $avatar_response );
		endif;
		
	endif;
	
	if( $avatar_response['cache'] && $avatar_response['status']=='waiting' && $GLOBALS['pagenow']!='options-discussion.php' )
		$avatar_response['href'] = baw_google_download_img( $email, $avatar_response['href'], admin_url( '/images/logo.gif' ) );
		
	if( $send_type )
		return $avatar_response['type'];
		
	$js_selector = $GLOBALS['pagenow']=='options-discussion.php' ? 'option-page ' : '';
	$avatar = "<img alt='{$safe_alt}' src='{$avatar_response['href']}' class='{$js_selector}avatar from-{$avatar_response['type']} avatar-{$size} photo' height='{$size}' width='{$size}' />";
	$avatar = apply_filters( 'get_avatar', $avatar, $id_or_email, $size, $default, $alt );
	$avatar = apply_filters( 'ggi_get_avatar', $avatar, $email, $size, $default, $alt, $img );
		
	return $avatar;
}
endif;

function baw_google_download_img( $email, $photo, $default )
{
	$size = apply_filters( 'default_size', 96 );
	global $bawggi_upload_url, $bawggi_upload_dir;
	do_action( 'baw_google_download_img', $email, $photo, $default, $size );
	if ( !is_dir( $bawggi_upload_dir ) )
		bawggi_installer();
	$infos = @getimagesize( $photo );
	if( !$infos )
		return $default;
	$ext = $infos['mime'];
	if( strstr( $ext, 'image/') != '' ):
		$ext = explode( '/', $ext );
		$ext = '.' . $ext[1];
	else:
		return $default;
	endif;
	if( strtolower( $ext ) == '.php' )
		wp_die( __( 'Cheatin&#8217; uh?' ) );
	$new_file = $bawggi_upload_dir . md5( strtolower( $email ) ) . $ext;
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$tmp = wp_remote_get( $photo, array( 'timeout' => 300, 'stream' => true, 'filename' => $new_file ) ); 
	if( is_wp_error( $tmp ) )
		return $default;
	$img = image_resize( $tmp['filename'], $size, $size, true, 'bawggi', dirname( $tmp['filename'] ) );
	if( is_wp_error( $img ) && isset( $img->errors['error_getting_dimensions'][0] ) && $img->errors['error_getting_dimensions'][0]!= __( 'Could not calculate resized image dimensions' ) )
		return $default;
	@rename( $img, $new_file );
	$new_file = str_replace( $bawggi_upload_dir, $bawggi_upload_url, $new_file );
	@unlink( $tmp );
	return $new_file;
}

function bawggi_insert_comment( $id, $comment )
{
	$default_size = apply_filters( 'default_size', 96 );
	if( get_avatar( $comment, $default_size, '', false, true ) == 'google' ):
		global $bawggi_upload_url, $bawggi_upload_dir;
		$files = glob( $bawggi_upload_dir . md5( strtolower( $email ) ) . '.*' );
		$avatar = '';
		if( count( $files ) == 1 && file_exists( $files[0] ) ):
			$ext = '.'.strtolower( substr( strrchr( $files[0], '.' ), 1 ) );
			$img = $bawggi_upload_url . md5( strtolower( $email ) ) . $ext;
			$infos = @getimagesize( $files[0] );
			if( $infos )
				$avatar = "(This one <img alt='{$safe_alt}' src='{$img}' class='avatar from-google avatar-{$size} photo' height='{$size}' width='{$size}' />) ";
		endif;
		$nonce_refresh = substr( md5( $comment->comment_author_email . NONCE_SALT . 'refresh_avatar' ), 1, 8 );
		$nonce_delete = substr( md5( $comment->comment_author_email . NONCE_SALT . 'delete_avatar' ), 1, 8 );
		$comment_link = get_comment_link( $comment );
		$comment_wait = $comment->comment_approved == 1 ? '' : __( ' (Your comment is awaiting moderation, maybe you will not see it.)', 'bawggi' );
		$to = $comment->comment_author_email;
		$suject = sprintf( __( '[%s] New avatar', 'bawggi' ), get_bloginfo( 'name' ) );
		$headers = 'From: ' . get_bloginfo( 'name' ) . ' <'.get_option( 'admin_email' ).'>' . "\r\n" . 'content-type: text/html';
		$message = make_clickable(
					sprintf( __( '<p>A new automatic avatar %s from http://images.google.com have been associated to your email on your last comment.</p>'.
								'<p>You can view it here: %s.%s</p>'.
								'<p>If you do not like it, you can delete it forever by clicking here: %s (link  always valid).</p>'.
								'<p>You can also ask for a refresh by clicking here: %s (link always valid).</p>'.
								'<p>We invite you to create a free account on http://www.gravatar.com.</p>', 'bawggi' ), 
								'<br /><p>%s</p>', 
								$avatar, $comment_link, $comment_wait,
								get_permalink( $comment->comment_post_ID ) . sprintf( '?comment_author_email=%s&nonce=%s&act=delete_avatar', urlencode( $to ), $nonce_delete ),
								get_permalink( $comment->comment_post_ID ) . sprintf( '?comment_author_email=%s&nonce=%s&act=refresh_avatar', urlencode( $to ), $nonce_refresh ),
								apply_filters( 'mail_avatar_signature', sprintf( __( 'Best regards - %s.', 'bawggi' ), get_bloginfo( 'name' ) ) ) // hook to change the email signature
							)
					);
		wp_mail( $to, $suject, $message, $headers );
	endif;
}
add_action( 'wp_insert_comment', 'bawggi_insert_comment', 10, 3 );

function bawggi_refresh_avatar()
{
	if( isset( $_GET['comment_author_email'], $_GET['nonce'], $_GET['act'] ) &&
		( current_user_can( 'administrator' ) || current_user_can( 'moderator' ) ) ): //moderator = http://baw.li/modorole
		extract( $_GET );
		$nonce_action = substr( md5( $comment_author_email . NONCE_SALT . $act ), 1, 8 );
		if( $nonce!==$nonce_action )
			wp_die( 'Security check failed.' );
		global $bawggi_upload_dir;
		$files = glob( $bawggi_upload_dir . md5( strtolower( $comment_author_email ) ) . '.*' );
		if( count( $files ) == 1 && file_exists( $files[0] ) ):
			switch( $act ):
				case 'delete_avatar': 
					@file_put_contents( $files[0], null ); // Nullify the file
					if( is_admin() )
						add_action( 'admin_notices', create_function( '', 'echo "<div class=\"updated\"><p>' . __( 'Avatar deleted', 'bawggi' ) . ' : ' . esc_html( $comment_author_email ) . '</p></div>";' ) ); 
					else
						add_action( 'wp_footer', create_function( '', 'echo "<script>alert(\"' . __( 'Avatar deleted', 'bawggi' ) . ' : ' . esc_js( $comment_author_email ) . '\");</script>";' ) ); 
					break; 
				case 'refresh_avatar': 
					@unlink( $files[0] );// Delete the file to get a new one
					if( is_admin() )
						add_action( 'admin_notices', create_function( '', 'echo "<div class=\"updated\"><p>' . __( 'Avatar refreshed', 'bawggi' ) . ' : ' . esc_html( $comment_author_email ) . '</p></div>";' ) );
					else
						add_action( 'wp_footer', create_function( '', 'echo "<script>alert(\"' . __( 'Avatar refreshed', 'bawggi' ) . ' : ' . esc_js( $comment_author_email ) . '\");</script>";' ) ); 
					break; 
				case 'mailto_avatar':
					bawggi_mailto_warn( $comment_author_email ); // email the commentator
					if( is_admin() )
						add_action( 'admin_notices', create_function( '', 'echo "<div class=\"updated\"><p>' . __( 'User mailed', 'bawggi' ) . ' : ' . esc_html( $comment_author_email ) . '</p></div>";' ) );
					else
						add_action( 'wp_footer', create_function( '', 'echo "<script>alert(\"' . __( 'User mailed', 'bawggi' ) . ' : ' . esc_js( $comment_author_email ) . '\");</script>";' ) );
					break; 
			endswitch;
		endif;
	endif;
}
add_action( 'template_redirect', 'bawggi_refresh_avatar' );
add_action( 'admin_init', 'bawggi_refresh_avatar' );

function bawggi_mailto_warn( $email )
{
	$avatar = get_avatar( $email, apply_filters( 'default_size', 96 ) );
	$nonce_refresh = substr( md5( $email . NONCE_SALT . 'refresh_avatar' ), 1, 8 );
	$nonce_delete = substr( md5( $email . NONCE_SALT . 'delete_avatar' ), 1, 8 );
	$to = $email;
	$suject = sprintf( __( '[%s] New avatar', 'bawggi' ), get_bloginfo( 'name' ) );
	$headers = 'From: ' . get_bloginfo( 'name' ) . ' <'.get_option( 'admin_email' ).'>' . "\r\n" . 'content-type: text/html';
	$message = make_clickable(
				sprintf( __( '<p>A new automatic avatar %s from http://images.google.com have been associated to your email on your last comment.</p>'.
							'<p>If you do not like it, you can delete it forever by clicking here: %s (link  always valid).</p>'.
							'<p>You can also ask for a refresh by clicking here: %s (link always valid).</p>'.
							'<p>We invite you to create a free account on http://www.gravatar.com.</p>', 'bawggi'.
							'<br /><p>%s</p>' ), 
							$avatar,
							home_url( sprintf( '?comment_author_email=%s&nonce=%s&act=delete_avatar', urlencode( $to ), $nonce_delete ) ),
							home_url( sprintf( '?comment_author_email=%s&nonce=%s&act=refresh_avatar', urlencode( $to ), $nonce_refresh ) ),
							apply_filters( 'mail_avatar_signature', sprintf( __( 'Best regards - %s.', 'bawggi' ), get_bloginfo( 'name' ) ) ) // hook to change the email signature
						)
				);
	wp_mail( $to, $suject, $message, $headers );
}

function bawggi_settings_action_links( $links )
{
	$settings_link = '<a href="' . admin_url( 'options-discussion.php#ggi' ) . '">' . __( 'Settings' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bawggi_settings_action_links' );

function bawggi_clear_cache()
{
	bawggi_uninstaller( false );
	wp_safe_redirect( add_query_arg( array( 'act'=>'cache_emptied' ), wp_get_referer() ) ); 
}

function bawggi_uninstaller( $all = true )
{
	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );
	$wp_fs_d = new WP_Filesystem_Direct( new StdClass() );
	$wp_fs_d->delete( $GLOBALS['bawggi_upload_dir'], true );
	if( $all ):
		delete_option( 'avatar_google' );
		delete_option( 'avatar_url' );
		delete_option( 'gravatars_cache' );
		update_option( 'avatar_default', get_option( 'avatar_default_save' ) );
		delete_option( 'avatar_default_save' );
	endif;
}
register_uninstall_hook( __FILE__, 'bawggi_uninstaller' );

function bawggi_installer()
{
	global $bawggi_upload_dir;
	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );
	$wp_fs_d = new WP_Filesystem_Direct( new StdClass() );
	if ( !$wp_fs_d->is_dir( $bawggi_upload_dir ) )
		if( !$wp_fs_d->mkdir( $bawggi_upload_dir, 0705 ) ):
			deactivate_plugins( __FILE__ );
			wp_die( sprintf( __( 'Impossible to create %s directory, plugin is not activated.', 'bawggi' ), $bawggi_upload_dir ) );
		endif;
	$wp_fs_d->put_contents( $GLOBALS['bawggi_upload_dir'] . 'index.php', '<?php // silence is golden', 0604 );
	
	if( !get_option( 'avatar_google' ) )
		add_option( 'avatar_google', 'on' );
	if( !get_option( 'gravatars_cache' ) )
		add_option( 'gravatars_cache', 'google' );
	add_option( 'avatar_default_save', get_option( 'avatar_default' ) );
}		
register_activation_hook( __FILE__, 'bawggi_installer' );