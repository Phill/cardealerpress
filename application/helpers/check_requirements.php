<?php

class CDP_Requirements {

	private $_error_message = null;
	private $_plugin_basename = null;
	private $_local_versions = array();
	private $_required_versions = array();

	public function has_been_checked() {
		return get_option( 'cdp_requirements_checked' );
	}

	public function set_master_file( $file ) {
		$this->_master_file = $file;
	}

	public function check_requirements() {
		$this->get_local_versions();
		$this->get_required_versions();
		if( $this->compare_versions() == false ) {
			$this->unable_to_load();
			return false;
		} else {
			$this->set_has_been_checked_flag();
			return true;
		}
	}

	private function get_local_versions() {
		$this->_local_versions[ 'php' ] = $this->get_php_version();
		$this->_local_versions[ 'wordpress' ] = $this->get_wordpress_version();
	}

	private function get_php_version() {
		return phpversion();
	}

	private function get_wordpress_version() {
		global $wp_version;
		return $wp_version;
	}

	private function get_required_versions() {
		$this->_required_versions[ 'php' ] = $this->get_required_php_version();
		$this->_required_versions[ 'wordpress' ] = $this->get_required_wordpress_version();;
	}

	private function get_required_php_version() {
		if( ! isset( $this->_required_versions[ 'php' ] ) ){
			$this->_required_versions[ 'php' ]  = '5.3';
		}
		return $this->_required_versions[ 'php' ];
	}

	private function get_required_wordpress_version() {
		if( ! isset( $this->_required_versions[ 'wordpress' ] ) ){
			$this->_required_versions[ 'wordpress' ]  = '3.2';
		}
		return $this->_required_versions[ 'wordpress' ];
	}

	private function compare_versions() {
		foreach( $this->_required_versions as $requirement_name => $required_version ) {
			if( ! isset( $this->_local_versions[ $requirement_name ] ) || $required_version >= $this->_local_versions[ $requirement_name ] ) {
				$this->_error_message = '<strong> ' .
				strtoupper( $requirement_name ) . ' is required to be <span style="color:red;">' . $required_version . '</span> or greater.
				 Your version is: <span style="color:red;">' . $this->_local_versions[ $requirement_name ] . '</span>
				</strong>';
				return false;
			}
		}
		return true;
	}

	public function unable_to_load() {
		add_action( 'admin_notices' , array( &$this , 'display_admin_error' ) );
		add_action( 'admin_init' , array( &$this , 'deactivate_plugin' ) );
	}

	public function display_admin_error() {
		echo
		'<div class="error">
			<p><span style="font-weight:bold; color:red;">ERROR:</span>: Unable to activate plugin. System requirements are not met.</p>
			<p>' . $this->_error_message . '</p>
			<p>Plugin has been <strong>deactivated</strong>.</p>
		</div>';
		$this->hide_default_activate_notice();
	}

	public function hide_default_activate_notice() {
		unset( $_GET[ 'activate' ] );
	}

	public function deactivate_plugin() {
		deactivate_plugins( plugin_basename( $this->_master_file ) );
	}

	private function set_has_been_checked_flag() {
		update_option( 'cdp_requirements_checked' , true );
	}

}

?>
