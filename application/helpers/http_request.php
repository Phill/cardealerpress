<?php

namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

class http_request {

	const timeout = 20;

	public $url = null;

	public $group = null;

	public $request_parameters = array(
		'timeout' => http_request::timeout,
		'headers' => array(
			'Referer' => NULL,
			'X-WordPress-Version' => NULL,
			'X-Plugin-Version' => NULL
		)
	);

	function __construct( $url , $group ) {
		global $wp , $wp_version;
		$this->url = $url;
		$this->group = $group;
		$this->plugin_options = get_option( 'cardealerpress_settings' );
		$this->request_parameters[ 'headers' ][ 'Referer' ] = $wp ? site_url() . '/' . $wp->request : site_url().'/';
		$this->request_parameters[ 'headers' ][ 'X-WordPress-Version' ] = $wp_version;
		$this->request_parameters[ 'headers' ][ 'X-Plugin-Version' ] = cdp_plugin::$plugin_information[ 'Version' ];	
	}

	function get_file( $sanitize = false ) {
		$response = wp_remote_request( $this->url , $this->request_parameters );
		if( wp_remote_retrieve_response_code( $response ) == 200 ) {
			if( $sanitize == true ) {
				$response[ 'body' ] = wp_kses_data( $response[ 'body' ] );
			}
			return $response;
		} else {
			if( is_wp_error( $response) ) {
				$error_title = 'CDP WP Error - ';
				$error_code = $response->get_error_code();
				$error_message = $response->get_error_message();
			} else {
				$error_title = 'CDP API Error - ';
				$error_code = wp_remote_retrieve_response_code( $response );
				$error_message = wp_remote_retrieve_response_message( $response );
			}
			$error_array = array( 'code' => $error_code , 'message' => $error_message );
			if( $this->plugin_options[ 'alt_settings' ][ 'debug_plugin_info' ] ){
				error_log('Version: '.cdp_plugin::$plugin_information[ 'Version' ].' | '.$error_title.' code: '.$error_code.' | message: '.$error_message.' | call: '.$this->url);
			}
			return $error_array;
		}
	}

	function cache_file( $data ) {
		return wp_cache_add( $this->url , $data , $this->group , 7200 );
	}
	
	function cached() {
		return wp_cache_get( $this->url , $this->group );
	}

}

?>
