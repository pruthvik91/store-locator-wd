<?php
/**
 * Sends an email, similar to PHP's mail function.
 *
 * @global PHPMailer $phpmailer
 *
 * @param string|array $to          Array or comma-separated list of email addresses to send message.
 * @param string       $subject     Email subject
 * @param string       $message     Message contents
 * @param string|array $headers     Optional. Additional headers.
 * @param string|array $attachments Optional. Files to attach.
 * @return bool Whether the email contents were sent successfully.
 */
function send_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
	
	$atts = compact( 'to', 'subject', 'message', 'headers', 'attachments' );
	if ( isset( $atts['to'] ) ) {
		$to = $atts['to'];
	}

	if ( ! is_array( $to ) ) {
		$to = explode( ',', $to );
	}

	if ( isset( $atts['subject'] ) ) {
		$subject = $atts['subject'];
	}

	if ( isset( $atts['message'] ) ) {
		$message = $atts['message'];
	}

	if ( isset( $atts['headers'] ) ) {
		$headers = $atts['headers'];
	}

	if ( isset( $atts['attachments'] ) ) {
		$attachments = $atts['attachments'];
	}

	if ( ! is_array( $attachments ) ) {
		$attachments = explode( "\n", str_replace( "\r\n", "\n", $attachments ) );
	}
	global $phpmailer;

	// (Re)create it, if it's gone missing
	if ( ! ( $phpmailer instanceof PHPMailer ) ) {
		require_once 'inc/class-phpmailer.php';
		require_once 'inc/class-smtp.php';
		$phpmailer = new PHPMailer( true );
	}

	// Headers
	$cc       = array();
	$bcc      = array();
	$reply_to = array();

	if ( empty( $headers ) ) {
		$headers = array();
	} else {
		if ( ! is_array( $headers ) ) {
			// Explode the headers out, so this function can take both
			// string headers and an array of headers.
			$tempheaders = explode( "\n", str_replace( "\r\n", "\n", $headers ) );
		} else {
			$tempheaders = $headers;
		}
		$headers = array();

		// If it's actually got contents
		if ( ! empty( $tempheaders ) ) {
			// Iterate through the raw headers
			foreach ( (array) $tempheaders as $header ) {
				if ( strpos( $header, ':' ) === false ) {
					if ( false !== stripos( $header, 'boundary=' ) ) {
						$parts    = preg_split( '/boundary=/i', trim( $header ) );
						$boundary = trim( str_replace( array( "'", '"' ), '', $parts[1] ) );
					}
					continue;
				}
				// Explode them out
				list( $name, $content ) = explode( ':', trim( $header ), 2 );

				// Cleanup crew
				$name    = trim( $name );
				$content = trim( $content );

				switch ( strtolower( $name ) ) {
					// Mainly for legacy -- process a From: header if it's there
					case 'from':
						$bracket_pos = strpos( $content, '<' );
						if ( $bracket_pos !== false ) {
							// Text before the bracketed email is the "From" name.
							if ( $bracket_pos > 0 ) {
								$from_name = substr( $content, 0, $bracket_pos - 1 );
								$from_name = str_replace( '"', '', $from_name );
								$from_name = trim( $from_name );
							}

							$from_email = substr( $content, $bracket_pos + 1 );
							$from_email = str_replace( '>', '', $from_email );
							$from_email = trim( $from_email );

							// Avoid setting an empty $from_email.
						} elseif ( '' !== trim( $content ) ) {
							$from_email = trim( $content );
						}
						break;
					case 'content-type':
						if ( strpos( $content, ';' ) !== false ) {
							list( $type, $charset_content ) = explode( ';', $content );
							$content_type                   = trim( $type );
							if ( false !== stripos( $charset_content, 'charset=' ) ) {
								$charset = trim( str_replace( array( 'charset=', '"' ), '', $charset_content ) );
							} elseif ( false !== stripos( $charset_content, 'boundary=' ) ) {
								$boundary = trim( str_replace( array( 'BOUNDARY=', 'boundary=', '"' ), '', $charset_content ) );
								$charset  = '';
							}

							// Avoid setting an empty $content_type.
						} elseif ( '' !== trim( $content ) ) {
							$content_type = trim( $content );
						}
						break;
					case 'cc':
						$cc = array_merge( (array) $cc, explode( ',', $content ) );
						break;
					case 'bcc':
						$bcc = array_merge( (array) $bcc, explode( ',', $content ) );
						break;
					case 'reply-to':
						$reply_to = array_merge( (array) $reply_to, explode( ',', $content ) );
						break;
					default:
						// Add it to our grand headers array
						$headers[ trim( $name ) ] = trim( $content );
						break;
				}
			}
		}
	}

	// Empty out the values that may be set
	$phpmailer->clearAllRecipients();
	$phpmailer->clearAttachments();
	$phpmailer->clearCustomHeaders();
	$phpmailer->clearReplyTos();

	// From email and name
	// If we don't have a name from the input headers
	if ( ! isset( $from_name ) ) {
		$from_name = GLOBAL_SETFROMNAME;
	}

	/* If we don't have an email from the input headers default to wordpress@$sitename
	 * Some hosts will block outgoing mail from this address if it doesn't exist but
	 * there's no easy alternative. Defaulting to admin_email might appear to be another
	 * option but some hosts may refuse to relay mail from an unknown domain. See
	 * https://core.trac.wordpress.org/ticket/5007.
	 */

	if ( ! isset( $from_email ) ) {
		// Get the site domain and get rid of www.
		$from_email = GLOBAL_SETFROM;
	}


	try {
		$phpmailer->setFrom( $from_email, $from_name, false );
	} catch ( phpmailerException $e ) {
		$mail_error_data                             = compact( 'to', 'subject', 'message', 'headers', 'attachments' );
		$mail_error_data['phpmailer_exception_code'] = $e->getCode();

		do_action( 'wp_mail_failed', new CP_Error( 'send_mail_failed', $e->getMessage(), $mail_error_data ) );
		return false;
	}

	// Set mail's subject and body
	$phpmailer->Subject = $subject;
	$phpmailer->Body    = $message;

	// Set destination addresses, using appropriate methods for handling addresses
	$address_headers = compact( 'to', 'cc', 'bcc', 'reply_to' );

	foreach ( $address_headers as $address_header => $addresses ) {
		if ( empty( $addresses ) ) {
			continue;
		}

		foreach ( (array) $addresses as $address ) {
			try {
				// Break $recipient into name and address parts if in the format "Foo <bar@baz.com>"
				$recipient_name = '';

				if ( preg_match( '/(.*)<(.+)>/', $address, $matches ) ) {
					if ( count( $matches ) == 3 ) {
						$recipient_name = $matches[1];
						$address        = $matches[2];
					}
				}

				switch ( $address_header ) {
					case 'to':
						$phpmailer->addAddress( $address, $recipient_name );
						break;
					case 'cc':
						$phpmailer->addCc( $address, $recipient_name );
						break;
					case 'bcc':
						$phpmailer->addBcc( $address, $recipient_name );
						break;
					case 'reply_to':
						$phpmailer->addReplyTo( $address, $recipient_name );
						break;
				}
			} catch ( phpmailerException $e ) {
				continue;
			}
		}
	}

	// Set to use PHP's mail()
	$phpmailer->isMail();

	// Set Content-Type and charset
	// If we don't have a content-type from the input headers
	if ( ! isset( $content_type ) ) {
		$content_type = 'text/plain';
	}

	
	$phpmailer->ContentType = $content_type;

	// Set whether it's plaintext, depending on $content_type
	if ( 'text/html' == $content_type ) {
		$phpmailer->isHTML( true );
	}

	// If we don't have a charset from the input headers
	if ( ! isset( $charset ) ) {
		$charset = get_bloginfo( 'charset' );
	}

	// Set the content-type and charset
	$phpmailer->CharSet = $charset;

	// Set custom headers.
	if ( ! empty( $headers ) ) {
		foreach ( (array) $headers as $name => $content ) {
			// Only add custom headers not added automatically by PHPMailer.
			if ( ! in_array( $name, array( 'MIME-Version', 'X-Mailer' ) ) ) {
				$phpmailer->addCustomHeader( sprintf( '%1$s: %2$s', $name, $content ) );
			}
		}

		if ( false !== stripos( $content_type, 'multipart' ) && ! empty( $boundary ) ) {
			$phpmailer->addCustomHeader( sprintf( "Content-Type: %s;\n\t boundary=\"%s\"", $content_type, $boundary ) );
		}
	}

	if ( ! empty( $attachments ) ) {
		foreach ( $attachments as $attachment ) {
			try {
				$phpmailer->addAttachment( $attachment );
			} catch ( phpmailerException $e ) {
				continue;
			}
		}
	}

	
	// Send!
	try {
		return $phpmailer->send();
	} catch ( phpmailerException $e ) {

		$mail_error_data                             = compact( 'to', 'subject', 'message', 'headers', 'attachments' );
		$mail_error_data['phpmailer_exception_code'] = $e->getCode();

		do_action( 'wp_mail_failed', new CP_Error( 'send_mail_failed', $e->getMessage(), $mail_error_data ) );

		return false;
	}
}