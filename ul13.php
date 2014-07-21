<?php

define( 'L8INC', ABSPATH . 'inc/' );

include_once (L8INC . 'lib/func.php');

/**
 * @package Tacnix
 * @subpackage Uploader 1.0.
 * @author Mukunda Panchal <mukunda@tacnix.com>
 * 
 * @param extension( string )
 * 
 */
function extension( $string ) {
	$i = strrpos( $string,"." );
	if ( !$i ) { return ""; } 
	$length = strlen( $string ) - $i;
	$extension = substr( $string, $i+1, $length );
	return $extension;
}

function _upload_( $folder, $width_new ) {

	if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
		
		// Defining maximum size of file.
		define( 'MAX_SIZE','5120' );
		
		$errors = 0;
		
		// Button click event is post, then.
		$image = $_FILES[ '_upload' ][ 'name' ];
		
		$uploaded_file = $_FILES[ '_upload' ][ 'tmp_name' ];
		
		if ( empty( $image ) && empty( $uploaded_file ) ) {
			
			$errors = $_FILES[ '_upload' ][ 'error' ];
			
			_report_( 'error','Uploading Error!' );
			
		} else {
		
			$filename = stripslashes( $_FILES[ '_upload' ][ 'name' ] );
			
			$extension = extension( $filename );
			$extension = strtolower( $extension );
			
			
			if ( ( $extension != 'jpg' ) && ( $extension != 'jpeg' ) && ( $extension != 'png' ) && ( $extension != 'gif' ) ) {
			  
			  _report_( 'error','Unknown Image extension!' );
			  
				$errors = $_FILES[ '_upload' ][ 'error' ];
				$errors = 1;
				
			} else {
			
				$size = filesize( $_FILES[ '_upload' ][ 'tmp_name' ] );
				
  			if ( $size > MAX_SIZE*5120 ) {
  			  
  			  _report_( 'error','You have exceeded the size limit!' );
  			  
  				$errors = $_FILES[ '_upload' ][ 'error' ];
  				$errors = 1;
  			}
			
  			if( $extension == 'jpg' || $extension == 'jpeg' ) {
  			
  				$uploadedfile = $_FILES[ '_upload' ][ 'tmp_name' ];
  				$source = imagecreatefromjpeg( $uploaded_file );
  			
  			} else if ($extension == 'png') {
  				
  				$uploadedfile = $_FILES[ '_upload' ][ 'tmp_name' ];
  				$source = imagecreatefrompng( $uploaded_file );
  			
  			} else if( $extension == 'gif' ) {
  				
  				$uploadedfile = $_FILES[ '_upload' ][ 'tmp_name' ];
  				$source = imagecreatefromgif( $uploaded_file );
  			
  			}
			
  			list( $width, $height ) = getimagesize( $uploaded_file );
  			
  			$height_new = ( $height / $width ) * $width_new;
  			$image_new = imagecreatetruecolor( $width_new, $height_new );
  			
  			imagecopyresampled( $image_new, $source, 0, 0, 0, 0, $width_new, $height_new, $width, $height );
  			
  			$path = 'images/'.$folder;
  			$media = $path.$_FILES[ '_upload' ][ 'name' ];
  			$replace = preg_replace( "/[&']/", "_", $media );
  			
  			imagejpeg( $image_new, $replace, 100 );
  			
  			imagedestroy( $source );
  			imagedestroy( $image_new );
		  }
	  }
  }
}
