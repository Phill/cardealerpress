<?php
	$current_page = $_GET['page'];
	$admin_tabs = array( 'status' => 'Feed Status', 'settings' => 'Settings', 'showcase' => 'Showcase', 'shortcodes' => 'ShortCodes', 'admin' => 'Admin', 'help' => 'Help' );
	//if( empty($this->vrs) ){ unset($admin_tabs['showcase']); }
	unset($admin_tabs['showcase']);
	if( empty($this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]) ){ unset($admin_tabs['settings']); unset($admin_tabs['status']); unset($admin_tabs['shortcodes']); }
?>
<div id="cdp-header-wrapper">
	<img id="cdp-header-logo" src="<?php echo cdp_get_image_source();?>cdp_logo_50x44.png" />
	<div id="cdp-header-name">CarDealerPress Plugin</div>
	<div id="cdp-header-save">Save</div>
	<div id="cdp-header-tabs-wrapper">
		<?php
			foreach( $admin_tabs as $key => $value ){
				$class = $current_page == 'cardealerpress_'.$key ? 'cdp-header-tab active-tab' : 'cdp-header-tab';
				echo '<div class="'.$class.'"><a href="'.add_query_arg( array('page'=>'cardealerpress_'.$key) ).'">'.$value.'</a></div>'; 
			}
		?>
	</div>
</div>