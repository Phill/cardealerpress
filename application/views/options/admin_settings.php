<?php
	$this->load_admin_assets();
	$this->get_admin_header();
?>

<div id="cdp-content-wrapper" class="settings-wrapper">
	<div class="tr-wrapper">
		<div class="td-full"><h3 class="title">Company Settings</h3></div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two center"><span class="td-title">Company ID:</span></div>
		<div class="td-two">
			<input type="text" name="vehicle_management_system/company_information/id" id="company-id" extra="refresh" value="<?php echo $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]; ?>" class="cdp-input input-short" />
		</div>
		<div class="td-full center"><small>Inventory will not be retreived without providing a valid company ID.</small></div>
	</div>
	<?php
	if( !empty($this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]) ){
	?>
	<div class="tr-wrapper tr-color">
		<div class="td-two center"><span class="td-title">Search Engine Visibility:</span></div>
		<div class="td-two">
			<input type="checkbox" id="discourage_seo_visibility" name="alt_settings/discourage_seo_visibility" <?php if ( $this->options[ 'alt_settings' ][ 'discourage_seo_visibility' ] != '' ) { echo 'checked'; } ?> class="cdp-input" />
		</div>
		<div class="td-full center"><small>Discourage search engines from indexing inventory/showcase pages</small></div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two center"><span class="td-title">Show Plugin Debug Info:</span></div>
		<div class="td-two">
			<input type="checkbox" id="discourage_seo_visibility" name="alt_settings/debug_plugin_info" <?php if ( $this->options[ 'alt_settings' ][ 'debug_plugin_info' ] != '' ) { echo 'checked'; } ?> class="cdp-input" />
		</div>
		<div class="td-full center"><small>Shows current plugin settings "commented out" on front end to help debug issues.</small></div>
	</div>
	<?php
	}
	?>

</div>
<?php
	$this->admin_footer();
?>