<?php
function handle_upload( &$file, $overrides ) {
	
	
	// You may have had one or more 'wp_handle_upload_prefilter' functions error out the file. Handle that gracefully.
	if ( isset( $file['error'] ) && ! is_numeric( $file['error'] ) && $file['error'] ) {
		return "some error";
	}
	$mimes     = isset( $overrides['mimes'] ) ? $overrides['mimes'] : false;

	$upload_error_strings = array(
		false,
		__( 'The uploaded file exceeds the upload_max_filesize directive in php.ini.' ),
		__( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.' ),
		__( 'The uploaded file was only partially uploaded.' ),
		__( 'No file was uploaded.' ),
		'',
		__( 'Missing a temporary folder.' ),
		__( 'Failed to write file to disk.' ),
		__( 'File upload stopped by extension.' ),
	);
	
	// A successful upload will pass this test. It makes no sense to override this one.
	if ( isset( $file['error'] ) && $file['error'] > 0 ) {
		return $upload_error_strings[ $file['error'] ];
	}

	// A properly uploaded file will pass this test. There should be no reason to override this one.
	$test_uploaded_file =  @is_readable( $file['tmp_name'] );
	if ( ! $test_uploaded_file ) {
		return __( 'Specified file failed upload test.' );
	}

	$test_file_size = filesize( $file['tmp_name'] );
	// A non-empty file will pass this test.
	if ( ! ( $test_file_size > 0 ) ) {
		return $error_msg = __( 'File is empty. Please upload something more substantial. This error could also be caused by uploads being disabled in your php.ini or by post_max_size being defined as smaller than upload_max_filesize in php.ini.' );
	}

	
	$wp_filetype     = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $mimes );
	$ext             = empty( $wp_filetype['ext'] ) ? '' : $wp_filetype['ext'];
	$type            = empty( $wp_filetype['type'] ) ? '' : $wp_filetype['type'];
	$proper_filename = empty( $wp_filetype['proper_filename'] ) ? '' : $wp_filetype['proper_filename'];

	if ( $proper_filename ) {
		$file['name'] = $proper_filename;
	}
	if ( ! $ext ) {
		return __( 'Sorry, this file type is not permitted for security reasons.' ) ;
	}
	

	$filename = wp_unique_filename( $uploads['path'], $file['name'], $unique_filename_callback );
	$new_file = $uploads['path'] . "/$filename";

	// use copy and unlink because rename breaks streams.
	// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
	$move_new_file = @copy( $file['tmp_name'], $new_file );
	unlink( $file['tmp_name'] );
	
	if ( false === $move_new_file ) {
		return __( 'The uploaded file could not be moved to %s.' );
	}
	

	// Set correct file permissions.
	$stat  = stat( dirname( $new_file ) );
	$perms = $stat['mode'] & 0000666;
	chmod( $new_file, $perms );

	// return $new_file;
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

function create_image_sizes( $file) {
	$imagesize = @getimagesize( $file );

	if ( empty( $imagesize ) ) {
		// File is not an image.
		return array();
	}

	// Default image meta
	$image_meta = array(
		'width'  => $imagesize[0],
		'height' => $imagesize[1],
		'file'   => _wp_relative_upload_path( $file ),
		'sizes'  => array(),
	);

	
	$new_sizes = array(
		'large'=> array(
			'width'  => 1000,
			'height' => 1000,
			'crop'   => false,
		),
		'medium'=> array(
			'width'  => 600,
			'height' => 600,
			'crop'   => false,
		),
		'small'=> array(
			'width'  => 200,
			'height' => 200,
			'crop'   => false,
		),
		'thumbnail'=> array(
			'width'  => 100,
			'height' => 100,
			'crop'   => true,
		)
	);


	$editor = wp_get_image_editor( $file );

	if ( is_cp_error( $editor ) ) {
		// The image cannot be edited.
		return new CP_Error( 'image_not_edited', __( 'The image cannot be edited.' ) );
	}

	if ( method_exists( $editor, 'make_subsize' ) ) {
		foreach ( $new_sizes as $new_size_name => $new_size_data ) {
			$new_size_meta = $editor->make_subsize( $new_size_data );

			if ( is_cp_error( $new_size_meta ) ) {
				
			} 
		}
	} else {
		$created_sizes = $editor->multi_resize( $new_sizes );

		if ( ! empty( $created_sizes ) ) {
		}
	}
	return true;
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

	return new CP_Error( 'image_no_editor', __( 'No editor could be selected.' ) );
}

function _wp_image_editor_choose( $args = array() ) {
	require_once 'inc/class-wp-image-editor.php';
	require_once 'inc/class-wp-image-editor-gd.php';
	require_once 'inc/class-wp-image-editor-imagick.php';
	$implementations = array( 'WP_Image_Editor_Imagick', 'WP_Image_Editor_GD' );

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