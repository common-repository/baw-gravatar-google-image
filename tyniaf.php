<?php
$d = isset( $_GET['email'] ) ? urldecode( $_GET['email'] ) : 'forcedefault';
$s = isset( $_GET['s'] ) ? (int)$_GET['s'] : 96;

if( $d == 'forcedefault' ):
	$L1=$L2=$L3=$L4="01";
else:
	$L1 = str_pad( strlen( $d ) % 18 , 2, "0", STR_PAD_LEFT);
	$L1 = (int)$L1>0 ? $L1 : "06";
	$L2 = str_pad( strlen( preg_replace( '[^aeiuoy]', '', $d ) ) % 18 , 2, "0", STR_PAD_LEFT );
	$L2 = (int)$L2>0 ? $L2 : "09";
	$L3 = str_pad( strlen( preg_replace( '/[^0-9\.\-\_\@]/', '', $d ) ) % 18 , 2, "0", STR_PAD_LEFT );
	$L3 = (int)$L3>0 ? $L3 : "12";
	$L4 = str_pad( ( ord( $d[0] ) + ord( $d[5] ) ) % 18 , 2, "0", STR_PAD_LEFT );
	$L4 = (int)$L4>0 ? $L4 : "15";
endif;
	
header( "Content-type: image/png" );
readfile( "http://turnyournameintoaface.com/face/$L1$L2$L3$L4.png" );