<?php
/**
 * Created by PhpStorm.
 * User: Tu TV
 * Date: 26/11/2015
 * Time: 7:41 PM
 */

echo 'hello world';

try {
	$server   = $_SERVER;
	$response = file_get_contents( 'php://input' );

	$object_res = json_decode( $response );
	/**
	 * Push
	 */
	$push    = $object_res->push;
	$changes = $push->changes[0];
	$commits = $changes->commits;

	$now      = date_create()->setTimezone( new DateTimeZone( 'Asia/Ho_Chi_Minh' ) )->format( 'H:i:s d/m/Y' );
	$temp_str = $now . PHP_EOL;
	if ( count( $commits ) > 0 ) {
		foreach ( $commits as $index => $commit ) {
			$author       = $commit->author;//Object
			$raw_author   = $author->raw;//Tu TV <tutv95@gmail.com>
			$message      = $commit->message;//demo 02
			$message      = str_replace( array( "\r\n", "\r", "\n" ), '', $message );//demo 02
			$write_commit = $raw_author . ' - "' . $message . '"' . PHP_EOL;
			$temp_str .= $write_commit;
		}
	}

	$path_log_txt = 'ok.txt';

	if ( countLineTextFile( $path_log_txt ) > 1000 ) {
		file_put_contents( $path_log_txt, PHP_EOL . sprintf( $temp_str ), FILE_TEXT );
	} else {
		file_put_contents( $path_log_txt, PHP_EOL . sprintf( $temp_str ), FILE_APPEND );
	}
} catch ( Exception $e ) {
	file_put_contents( $path_log_txt, PHP_EOL . sprintf( $e->getMessage() ), FILE_APPEND );
}

function countLineTextFile( $path ) {
	$count  = 0;
	$handle = fopen( $path, 'r' );
	while ( ! feof( $handle ) ) {
		$line = fgets( $handle );
		$count ++;
	}

	fclose( $handle );

	return $count;
}