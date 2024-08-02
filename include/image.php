<?php
function handle_upload( &$file, $overrides ) {
	
	// You may have had one or more 'wp_handle_upload_prefilter' functions error out the file. Handle that gracefully.
	if ( isset( $file['error'] ) && ! is_numeric( $file['error'] ) && $file['error'] ) {
		return "some error";
	}
	$mimes     = isset( $overrides['mimes'] ) ? $overrides['mimes'] : false;
	$upload_path     = isset( $overrides['mimes'] ) ? $overrides['upload_to'] : UPLOAD_PATH;
	$test_size = isset( $overrides['size'] ) ? $overrides['size'] : 0;
	$new_sizes = isset( $overrides['sizes'] ) ? $overrides['sizes'] : false;
	
	if (!file_exists($upload_path)) {
		mkdir($upload_path, 0777, true);
	}
	if (!file_exists($upload_path)) {
		return  'Specified upload location is not available.' ;
	}
	
	$upload_error_strings = array(
		false,
		'The uploaded file exceeds the upload_max_filesize directive in php.ini.' ,
		'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.' ,
		'The uploaded file was only partially uploaded.' ,
		'No file was uploaded.' ,
		'',
		'Missing a temporary folder.' ,
		'Failed to write file to disk.' ,
		'File upload stopped by extension.' ,
	);
	
	// A successful upload will pass this test. It makes no sense to override this one.
	if ( isset( $file['error'] ) && $file['error'] > 0 ) {
		return $upload_error_strings[ $file['error'] ];
	}

	// A properly uploaded file will pass this test. There should be no reason to override this one.
	$test_uploaded_file =  @is_readable( $file['tmp_name'] );
	if ( ! $test_uploaded_file ) {
		return  'Specified file failed upload test.' ;
	}

	$test_file_size = filesize( $file['tmp_name'] );
	// A non-empty file will pass this test.
	if ( ! ( $test_file_size > 0 ) ) {
		return $error_msg =  'File is empty. Please upload something more substantial. This error could also be caused by uploads being disabled in your php.ini or by post_max_size being defined as smaller than upload_max_filesize in php.ini.' ;
	}
	
	if($test_size > 0  && $test_file_size > (1048576 * $test_size))
	{
		return $error_msg =  'File is too large. Please upload file less than or equal to '.$test_size.'MB.' ;
	}

	
	$wp_filetype     = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $mimes );
	$ext             = empty( $wp_filetype['ext'] ) ? '' : $wp_filetype['ext'];
	$type            = empty( $wp_filetype['type'] ) ? '' : $wp_filetype['type'];
	$proper_filename = empty( $wp_filetype['proper_filename'] ) ? '' : $wp_filetype['proper_filename'];

	if ( $proper_filename ) {
		$file['name'] = $proper_filename;
	}
	if ( ! $ext ) {
		return  'Sorry, this file type is not permitted for security reasons.' ;
	}
	

	$filename = wp_unique_filename( $upload_path , $file['name'] );
	$new_file = $upload_path . "/$filename";

	$move_new_file = @copy( $file['tmp_name'], $new_file );
	unlink( $file['tmp_name'] );
	
	if ( false === $move_new_file ) {
		return  'The uploaded file could not be moved to %s.' ;
	}
	

	// Set correct file permissions.
	$stat  = stat( dirname( $new_file ) );
	$perms = $stat['mode'] & 0000666;
	chmod( $new_file, $perms );

	if($new_sizes != false)
	{
		$image_sizes  = create_image_sizes($new_file,$new_sizes);
		if ( is_cp_error( $image_sizes ) ) {
			return $image_sizes->get_error_message();
		}
		else
		{
		}
	}
	
	return $filename;
}




function wp_check_filetype_and_ext( $file, $filename, $mimes = null ) {
	$proper_filename = false;

	$wp_filetype =  wp_check_filetype( $filename, $mimes );
	$ext         = $wp_filetype['ext'];
	$type        = $wp_filetype['type'];

	// We can't do any further validation without a file to work with
	if ( ! file_exists( $file ) ) {
		return compact( 'ext', 'type', 'proper_filename' );
	}

	$real_mime = false;

	// Validate image types.
	if ( $type && 0 === strpos( $type, 'image/' ) ) {
	
		// Attempt to figure out what type of image it actually is
		try {
			if ( is_callable( 'exif_imagetype' ) ) {
				$imagetype = exif_imagetype( $file );
				$mime      = ( $imagetype ) ? image_type_to_mime_type( $imagetype ) : false;
			} elseif ( function_exists( 'getimagesize' ) ) {
				$imagesize = @getimagesize( $file );
				$mime      = ( isset( $imagesize['mime'] ) ) ? $imagesize['mime'] : false;
			} else {
				$mime = false;
			}
		} catch ( Exception $e ) {
			$mime = false;
		}
	
		$real_mime = $mime;

		if ( $real_mime && $real_mime != $type ) {
			$mime_to_ext = array(
					'image/jpeg' => 'jpg',
					'image/png'  => 'png',
					'image/gif'  => 'gif',
					'image/bmp'  => 'bmp',
					'image/tiff' => 'tif',
				);

			// Replace whatever is after the last period in the filename with the correct extension
			if ( ! empty( $mime_to_ext[ $real_mime ] ) ) {
				$filename_parts = explode( '.', $filename );
				array_pop( $filename_parts );
				$filename_parts[] = $mime_to_ext[ $real_mime ];
				$new_filename     = implode( '.', $filename_parts );

				if ( $new_filename != $filename ) {
					$proper_filename = $new_filename; // Mark that it changed
				}
				// Redefine the extension / MIME
				$wp_filetype = wp_check_filetype( $new_filename, $mimes );
				$ext         = $wp_filetype['ext'];
				$type        = $wp_filetype['type'];
			} else {
				// Reset $real_mime and try validating again.
				$real_mime = false;
			}
		}
	}

	// Validate files that didn't get validated during previous checks.
	if ( $type && ! $real_mime && extension_loaded( 'fileinfo' ) ) {
		$finfo     = finfo_open( FILEINFO_MIME_TYPE );
		$real_mime = finfo_file( $finfo, $file );
		finfo_close( $finfo );

		// fileinfo often misidentifies obscure files as one of these types
		$nonspecific_types = array(
			'application/octet-stream',
			'application/encrypted',
			'application/CDFV2-encrypted',
			'application/zip',
		);

		/*
		 * If $real_mime doesn't match the content type we're expecting from the file's extension,
		 * we need to do some additional vetting. Media types and those listed in $nonspecific_types are
		 * allowed some leeway, but anything else must exactly match the real content type.
		 */
		if ( in_array( $real_mime, $nonspecific_types, true ) ) {
			// File is a non-specific binary type. That's ok if it's a type that generally tends to be binary.
			if ( ! in_array( substr( $type, 0, strcspn( $type, '/' ) ), array( 'application', 'video', 'audio' ) ) ) {
				$type = false;
				$ext  = false;
			}
		} elseif ( 0 === strpos( $real_mime, 'video/' ) || 0 === strpos( $real_mime, 'audio/' ) ) {
			/*
			 * For these types, only the major type must match the real value.
			 * This means that common mismatches are forgiven: application/vnd.apple.numbers is often misidentified as application/zip,
			 * and some media files are commonly named with the wrong extension (.mov instead of .mp4)
			 */
			if ( substr( $real_mime, 0, strcspn( $real_mime, '/' ) ) !== substr( $type, 0, strcspn( $type, '/' ) ) ) {
				$type = false;
				$ext  = false;
			}
		} elseif ( 'text/plain' === $real_mime ) {
			// A few common file types are occasionally detected as text/plain; allow those.
			if ( ! in_array(
				$type,
				array(
					'text/plain',
					'text/csv',
					'text/richtext',
					'text/tsv',
					'text/vtt',
				)
			)
			) {
				$type = false;
				$ext  = false;
			}
		} elseif ( 'text/rtf' === $real_mime ) {
			// Special casing for RTF files.
			if ( ! in_array(
				$type,
				array(
					'text/rtf',
					'text/plain',
					'application/rtf',
				)
			)
			) {
				$type = false;
				$ext  = false;
			}
		} else {
			if ( $type !== $real_mime ) {
				/*
				 * Everything else including image/* and application/*:
				 * If the real content type doesn't match the file extension, assume it's dangerous.
				 */
				$type = false;
				$ext  = false;
			}
		}
	}

	// The mime type must be allowed
	if ( $type ) {
		$allowed = get_allowed_mime_types();

		if ( ! in_array( $type, $allowed ) ) {
			$type = false;
			$ext  = false;
		}
	}

	return  compact( 'ext', 'type', 'proper_filename' );
}


function get_allowed_mime_types() {

	return array(
			// Image formats.
			'jpg|jpeg|jpe'                 => 'image/jpeg',
			'gif'                          => 'image/gif',
			'png'                          => 'image/png',
			'bmp'                          => 'image/bmp',
			'tiff|tif'                     => 'image/tiff',
			'ico'                          => 'image/x-icon',
			// Video formats.
			'asf|asx'                      => 'video/x-ms-asf',
			'wmv'                          => 'video/x-ms-wmv',
			'wmx'                          => 'video/x-ms-wmx',
			'wm'                           => 'video/x-ms-wm',
			'avi'                          => 'video/avi',
			'divx'                         => 'video/divx',
			'flv'                          => 'video/x-flv',
			'mov|qt'                       => 'video/quicktime',
			'mpeg|mpg|mpe'                 => 'video/mpeg',
			'mp4|m4v'                      => 'video/mp4',
			'ogv'                          => 'video/ogg',
			'webm'                         => 'video/webm',
			'mkv'                          => 'video/x-matroska',
			'3gp|3gpp'                     => 'video/3gpp', // Can also be audio
			'3g2|3gp2'                     => 'video/3gpp2', // Can also be audio
			// Text formats.
			'txt|asc|c|cc|h|srt'           => 'text/plain',
			'csv'                          => 'text/csv',
			'tsv'                          => 'text/tab-separated-values',
			'ics'                          => 'text/calendar',
			'rtx'                          => 'text/richtext',
			'css'                          => 'text/css',
			//'htm|html'                     => 'text/html',
			'vtt'                          => 'text/vtt',
			'dfxp'                         => 'application/ttaf+xml',
			// Audio formats.
			'mp3|m4a|m4b'                  => 'audio/mpeg',
			'aac'                          => 'audio/aac',
			'ra|ram'                       => 'audio/x-realaudio',
			'wav'                          => 'audio/wav',
			'ogg|oga'                      => 'audio/ogg',
			'flac'                         => 'audio/flac',
			'mid|midi'                     => 'audio/midi',
			'wma'                          => 'audio/x-ms-wma',
			'wax'                          => 'audio/x-ms-wax',
			'mka'                          => 'audio/x-matroska',
			// Misc application formats.
			'rtf'                          => 'application/rtf',
			//'js'                           => 'application/javascript',
			'pdf'                          => 'application/pdf',
			//'swf'                          => 'application/x-shockwave-flash',
			'class'                        => 'application/java',
			'tar'                          => 'application/x-tar',
			'zip'                          => 'application/zip',
			'gz|gzip'                      => 'application/x-gzip',
			'rar'                          => 'application/rar',
			'7z'                           => 'application/x-7z-compressed',
			//'exe'                          => 'application/x-msdownload',
			'psd'                          => 'application/octet-stream',
			'xcf'                          => 'application/octet-stream',
			// MS Office formats.
			'doc'                          => 'application/msword',
			'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
			'wri'                          => 'application/vnd.ms-write',
			'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
			'mdb'                          => 'application/vnd.ms-access',
			'mpp'                          => 'application/vnd.ms-project',
			'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
			'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
			'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
			'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
			'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
			'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
			'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
			'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
			'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
			'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
			'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
			'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
			'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
			'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
			'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
			'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
			'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
			'oxps'                         => 'application/oxps',
			'xps'                          => 'application/vnd.ms-xpsdocument',
			// OpenOffice formats.
			'odt'                          => 'application/vnd.oasis.opendocument.text',
			'odp'                          => 'application/vnd.oasis.opendocument.presentation',
			'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
			'odg'                          => 'application/vnd.oasis.opendocument.graphics',
			'odc'                          => 'application/vnd.oasis.opendocument.chart',
			'odb'                          => 'application/vnd.oasis.opendocument.database',
			'odf'                          => 'application/vnd.oasis.opendocument.formula',
			// WordPerfect formats.
			'wp|wpd'                       => 'application/wordperfect',
			// iWork formats.
			'key'                          => 'application/vnd.apple.keynote',
			'numbers'                      => 'application/vnd.apple.numbers',
			'pages'                        => 'application/vnd.apple.pages',
		);
}


function wp_check_filetype( $filename, $mimes = null ) {
	if ( empty( $mimes ) ) {
		$mimes = get_allowed_mime_types();
	}
	$type = false;
	$ext  = false;

	foreach ( $mimes as $ext_preg => $mime_match ) {
		$ext_preg = '!\.(' . $ext_preg . ')$!i';
		if ( preg_match( $ext_preg, $filename, $ext_matches ) ) {
			$type = $mime_match;
			$ext  = $ext_matches[1];
			break;
		}
	}

	return compact( 'ext', 'type' );
}

function create_image_sizes( $file, $new_sizes) {
	$imagesize = @getimagesize( $file );
	$image_meta  = array();
	if ( empty( $imagesize ) ) {
		// File is not an image.
		return array();
	}
	

	$editor = wp_get_image_editor( $file );

	if ( is_cp_error( $editor ) ) {
		// The image cannot be edited.
		return new CP_Error( 'image_not_edited',  'The image cannot be edited.' );
	}

	if ( method_exists( $editor, 'make_subsize' ) ) {
		foreach ( $new_sizes as $new_size_name => $new_size_data ) {
			$new_size_meta = $editor->make_subsize( $new_size_data , $new_size_name );
			if ( is_cp_error( $new_size_meta ) ) {
				
			}
			else
			{
				$image_meta[ $new_size_name ] = $new_size_meta;
			}
		}
	} else {
		$created_sizes = $editor->multi_resize( $new_sizes );
		$image_meta = $created_sizes;
	}
	return $image_meta;
}




function wp_get_image_editor( $path, $args = array() ) {
	$args['path'] = $path;

	if ( ! isset( $args['mime_type'] ) ) {
		$file_info = wp_check_filetype( $args['path'] );

		// If $file_info['type'] is false, then we let the editor attempt to
		// figure out the file type, rather than forcing a failure based on extension.
		if ( isset( $file_info ) && $file_info['type'] ) {
			$args['mime_type'] = $file_info['type'];
		}
	}

	$implementation = _wp_image_editor_choose( $args );

	if ( $implementation ) {
		$editor = new $implementation( $path );
		$loaded = $editor->load();

		if ( is_cp_error( $loaded ) ) {
			return $loaded;
		}

		return $editor;
	}

	return new CP_Error( 'image_no_editor',  'No editor could be selected.' ) ;
}

function _wp_image_editor_choose( $args = array() ) {
	require_once 'inc/class-wp-image-editor.php';
	require_once 'inc/class-wp-image-editor-gd.php';
	/* require_once 'inc/class-wp-image-editor-imagick.php'; 
	$implementations = array( 'WP_Image_Editor_Imagick', 'WP_Image_Editor_GD' );
	*/
	$implementations = array( 'WP_Image_Editor_GD' );

	foreach ( $implementations as $implementation ) {
		if ( ! call_user_func( array( $implementation, 'test' ), $args ) ) {
			continue;
		}

		if ( isset( $args['mime_type'] ) &&
			! call_user_func(
				array( $implementation, 'supports_mime_type' ),
				$args['mime_type']
			) ) {
			continue;
		}

		if ( isset( $args['methods'] ) &&
			array_diff( $args['methods'], get_class_methods( $implementation ) ) ) {

			continue;
		}

		return $implementation;
	}

	return false;
}

function wp_unique_filename( $dir, $filename) {
	// Sanitize the file name before we begin processing.
	$filename = sanitize_file_name( $filename );
	$ext2     = null;

	// Separate the filename into a name and extension.
	$ext  = pathinfo( $filename, PATHINFO_EXTENSION );
	$name = pathinfo( $filename, PATHINFO_BASENAME );

	if ( $ext ) {
		$ext = '.' . $ext;
	}

	// Edge case: if file is named '.ext', treat as an empty name.
	if ( $name === $ext ) {
		$name = '';
	}

	/*
	 * Increment the file number until we have a unique file to save in $dir.
	 * Use callback if supplied.
	 */
	
		$number = '';
		$fname  = pathinfo( $filename, PATHINFO_FILENAME );

		// Always append a number to file names that can potentially match image sub-size file names.
		if ( $fname && preg_match( '/-(?:\d+x\d+|scaled|rotated)$/', $fname ) ) {
			$number = 1;

			// At this point the file name may not be unique. This is tested below and the $number is incremented.
			$filename = str_replace( "{$fname}{$ext}", "{$fname}-{$number}{$ext}", $filename );
		}

		// Change '.ext' to lower case.
		if ( $ext && strtolower( $ext ) != $ext ) {
			$ext2      = strtolower( $ext );
			$filename2 = preg_replace( '|' . preg_quote( $ext ) . '$|', $ext2, $filename );

			// Check for both lower and upper case extension or image sub-sizes may be overwritten.
			while ( file_exists( $dir . "/{$filename}" ) || file_exists( $dir . "/{$filename2}" ) ) {
				$new_number = (int) $number + 1;
				$filename   = str_replace( array( "-{$number}{$ext}", "{$number}{$ext}" ), "-{$new_number}{$ext}", $filename );
				$filename2  = str_replace( array( "-{$number}{$ext2}", "{$number}{$ext2}" ), "-{$new_number}{$ext2}", $filename2 );
				$number     = $new_number;
			}

			$filename = $filename2;
		} else {
			while ( file_exists( $dir . "/{$filename}" ) ) {
				$new_number = (int) $number + 1;

				if ( '' === "{$number}{$ext}" ) {
					$filename = "{$filename}-{$new_number}";
				} else {
					$filename = str_replace( array( "-{$number}{$ext}", "{$number}{$ext}" ), "-{$new_number}{$ext}", $filename );
				}

				$number = $new_number;
			}
		}
	
	return $filename;
}

function sanitize_file_name( $filename ) {
	$filename_raw  = $filename;
	$filename_ext = "";
	if(strpos( $filename, '.' ) > -1)
	{
		$filename_parts = explode( '.', $filename );
		$filename_ext = $filename_parts[count($filename_parts)-1];
		array_pop($filename_parts);
		$filename = implode( '.', $filename_parts );
	}			
				
	$special_chars = array( '.', '?', '[', ']', '/', '\\', '=', '<', '>', ':', ';', ',', "'", '"', '&', '$', '#', '*', '(', ')', '|', '~', '`', '!', '{', '}', '%', '+', chr( 0 ) );

	$filename      = preg_replace( "#\x{00a0}#siu", ' ', $filename );
	$filename      = str_replace( $special_chars, '', $filename );
	$filename      = str_replace( array( '%20', '+' ), '-', $filename );
	$filename      = preg_replace( '/[\r\n\t -]+/', '-', $filename );
	$filename      = trim( $filename, '.-_' );
	if($filename_ext != "")
	{
		$filename = $filename.'.'.$filename_ext;
	}
	
	return $filename;
}


function image_resize_dimensions( $orig_w, $orig_h, $dest_w, $dest_h, $crop = false ) {

	if ( $orig_w <= 0 || $orig_h <= 0 ) {
		return false;
	}
	// at least one of dest_w or dest_h must be specific
	if ( $dest_w <= 0 && $dest_h <= 0 ) {
		return false;
	}

	// Stop if the destination size is larger than the original image dimensions.
	if ( empty( $dest_h )  && false ) {
		if ( $orig_w < $dest_w ) {
			return false;
		}
	} elseif ( empty( $dest_w )  && false ) {
		if ( $orig_h < $dest_h ) {
			return false;
		}
	} else {
		if ( $orig_w < $dest_w && $orig_h < $dest_h  && false ) {
			return false;
		}
	}
		
	if ( $crop ) {
		$aspect_ratio = $orig_w / $orig_h;
		$new_w        = min( $dest_w, $orig_w );
		$new_h        = min( $dest_h, $orig_h );

		if ( ! $new_w ) {
			$new_w = (int) round( $new_h * $aspect_ratio );
		}

		if ( ! $new_h ) {
			$new_h = (int) round( $new_w / $aspect_ratio );
		}

		$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

		$crop_w = round( $new_w / $size_ratio );
		$crop_h = round( $new_h / $size_ratio );

		if ( ! is_array( $crop ) || count( $crop ) !== 2 ) {
			$crop = array( 'center', 'center' );
		}

		list( $x, $y ) = $crop;

		if ( 'left' === $x ) {
			$s_x = 0;
		} elseif ( 'right' === $x ) {
			$s_x = $orig_w - $crop_w;
		} else {
			$s_x = floor( ( $orig_w - $crop_w ) / 2 );
		}

		if ( 'top' === $y ) {
			$s_y = 0;
		} elseif ( 'bottom' === $y ) {
			$s_y = $orig_h - $crop_h;
		} else {
			$s_y = floor( ( $orig_h - $crop_h ) / 2 );
		}
	} else {
		// Resize using $dest_w x $dest_h as a maximum bounding box.
		$crop_w = $orig_w;
		$crop_h = $orig_h;

		$s_x = 0;
		$s_y = 0;

		list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
	}

	if ( wp_fuzzy_number_match( $new_w, $orig_w ) && wp_fuzzy_number_match( $new_h, $orig_h ) ) {
		
		$proceed = false;

		if ( ! $proceed  &&  false ) {
			return false;
		}
	}

	// The return array matches the parameters to imagecopyresampled().
	// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}


function wp_constrain_dimensions( $current_width, $current_height, $max_width = 0, $max_height = 0 ) {
	if ( ! $max_width && ! $max_height ) {
		return array( $current_width, $current_height );
	}

	$width_ratio  = 1.0;
	$height_ratio = 1.0;
	$did_width    = false;
	$did_height   = false;

	if ( $max_width > 0 && $current_width > 0 && $current_width > $max_width ) {
		$width_ratio = $max_width / $current_width;
		$did_width   = true;
	}

	if ( $max_height > 0 && $current_height > 0 && $current_height > $max_height ) {
		$height_ratio = $max_height / $current_height;
		$did_height   = true;
	}

	// Calculate the larger/smaller ratios
	$smaller_ratio = min( $width_ratio, $height_ratio );
	$larger_ratio  = max( $width_ratio, $height_ratio );

	if ( (int) round( $current_width * $larger_ratio ) > $max_width || (int) round( $current_height * $larger_ratio ) > $max_height ) {
		// The larger ratio is too big. It would result in an overflow.
		$ratio = $smaller_ratio;
	} else {
		// The larger ratio fits, and is likely to be a more "snug" fit.
		$ratio = $larger_ratio;
	}

	// Very small dimensions may result in 0, 1 should be the minimum.
	$w = max( 1, (int) round( $current_width * $ratio ) );
	$h = max( 1, (int) round( $current_height * $ratio ) );

	// Sometimes, due to rounding, we'll end up with a result like this: 465x700 in a 177x177 box is 117x176... a pixel short
	// We also have issues with recursive calls resulting in an ever-changing result. Constraining to the result of a constraint should yield the original result.
	// Thus we look for dimensions that are one pixel shy of the max value and bump them up

	// Note: $did_width means it is possible $smaller_ratio == $width_ratio.
	if ( $did_width && $w === $max_width - 1 ) {
		$w = $max_width; // Round it up
	}

	// Note: $did_height means it is possible $smaller_ratio == $height_ratio.
	if ( $did_height && $h === $max_height - 1 ) {
		$h = $max_height; // Round it up
	}

	
	return array( $w, $h );
}


function wp_fuzzy_number_match( $expected, $actual, $precision = 1 ) {
	return abs( (float) $expected - (float) $actual ) <= $precision;
}

function wp_imagecreatetruecolor( $width, $height ) {
	$img = imagecreatetruecolor( $width, $height );
	if ( is_resource( $img ) && function_exists( 'imagealphablending' ) && function_exists( 'imagesavealpha' ) ) {
		imagealphablending( $img, false );
		imagesavealpha( $img, true );
	}
	return $img;
}


function wp_basename( $path, $suffix = '' ) {
	return urldecode( basename( str_replace( array( '%2F', '%5C' ), '/', urlencode( $path ) ), $suffix ) );
}
function trailingslashit( $string ) {
	return untrailingslashit( $string ) . '/';
}
function untrailingslashit( $string ) {
	return rtrim( $string, '/\\' );
}


function wp_is_stream( $path ) {
	$scheme_separator = strpos( $path, '://' );

	if ( false === $scheme_separator ) {
		// $path isn't a stream
		return false;
	}

	$stream = substr( $path, 0, $scheme_separator );

	return in_array( $stream, stream_get_wrappers(), true );
}


function wp_mkdir_p( $target ) {
	$wrapper = null;

	// Strip the protocol.
	if ( wp_is_stream( $target ) ) {
		list( $wrapper, $target ) = explode( '://', $target, 2 );
	}

	// From php.net/mkdir user contributed notes.
	$target = str_replace( '//', '/', $target );

	// Put the wrapper back on the target.
	if ( $wrapper !== null ) {
		$target = $wrapper . '://' . $target;
	}

	/*
	 * Safe mode fails with a trailing slash under certain PHP versions.
	 * Use rtrim() instead of untrailingslashit to avoid formatting.php dependency.
	 */
	$target = rtrim( $target, '/' );
	if ( empty( $target ) ) {
		$target = '/';
	}

	if ( file_exists( $target ) ) {
		return @is_dir( $target );
	}

	// Do not allow path traversals.
	if ( false !== strpos( $target, '../' ) || false !== strpos( $target, '..' . DIRECTORY_SEPARATOR ) ) {
		return false;
	}

	// We need to find the permissions of the parent folder that exists and inherit that.
	$target_parent = dirname( $target );
	while ( '.' != $target_parent && ! is_dir( $target_parent ) && dirname( $target_parent ) !== $target_parent ) {
		$target_parent = dirname( $target_parent );
	}

	// Get the permission bits.
	$stat = @stat( $target_parent );
	if ( $stat ) {
		$dir_perms = $stat['mode'] & 0007777;
	} else {
		$dir_perms = 0777;
	}

	if ( @mkdir( $target, $dir_perms, true ) ) {

		/*
		 * If a umask is set that modifies $dir_perms, we'll have to re-set
		 * the $dir_perms correctly with chmod()
		 */
		if ( $dir_perms != ( $dir_perms & ~umask() ) ) {
			$folder_parts = explode( '/', substr( $target, strlen( $target_parent ) + 1 ) );
			for ( $i = 1, $c = count( $folder_parts ); $i <= $c; $i++ ) {
				chmod( $target_parent . '/' . implode( '/', array_slice( $folder_parts, 0, $i ) ), $dir_perms );
			}
		}

		return true;
	}
	return false;
}