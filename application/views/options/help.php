<?php
	$this->load_admin_assets();
	$this->get_admin_header();
?>
	<div id="cdp-content-wrapper" class="settings-wrapper">
		<div class="tr-wrapper">
			<div class="td-full"><h3 class="title">Initial Setup</h3></div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-full center">
				<p>To get the plugin started you'll need a valid Company ID.</p>
				<p>A company ID will be provided to you upon purchasing a subscription with <a href="http://cardealerpress.com" target="_blank">CarDealerPress</a></p>
				<p>After you've received a Company ID, you'll need to go to the <a id="settings-link" href="<?php echo add_query_arg( array('page'=>'cardealerpress_admin') ); ?>" title="CarDealerPress API Settings">Admin Settings</a> and fill in the respective company ID field.</p>
				<p>For further assistance click the link below:<br><a href="http://support.dealertrend.com" title="CarDealer Press Help Link" >CarDealerPress Help</a> </p>
			</div>
		</div>
		<?php
		if( !empty($this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ])){
		?>
		<div class="tr-wrapper">
			<div class="td-full"><h3 class="title">Viewing Inventory</h3></div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-full center">
				<p>If the VMS and Company Feed are both loaded, you may view your inventory here:<br>
					<a href="<?php bloginfo( 'url' ); echo $this->admin['link_new']; ?>" target="_blank">New Inventory</a><br>
					<a href="<?php bloginfo( 'url' ); echo $this->admin['link_used']; ?>" target="_blank">Used Inventory</a>
				</p>
				<p>Please note that any pages or sub-pages that reside at this permalink will no longer be shown.</p>
			</div>
		</div>		
		<div class="tr-wrapper">
			<div class="td-full"><h3 class="title">Plugin Legend</h3></div>
		</div>		
		<div class="tr-wrapper tr-color">
			<div class="td-full center">
				<p class="fail">Unavailable</p>
				<p>This means that the feed is currently not available. If this is showing, then that feed will not display information on your site.</p>
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-full center">
				<p class="success">Loaded</p>
				<p>If you see this, that means the feed is loaded and the information will be displayed on your website.</p>
			</div>
		</div>
		<div class="tr-wrapper">
			<div class="td-full"><h3 class="title">Sitemap Links</h3></div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-full center">
				<p><a target="_blank" href="<?php bloginfo( 'url' ); echo '/new-vehicle-sitemap.xml'; ?>">Sitemap New</a></p>
				<p><a target="_blank" href="<?php bloginfo( 'url' ); echo '/used-vehicle-sitemap.xml'; ?>">Sitemap Used</a></p>
			</div>
		</div>
		<div class="tr-wrapper">
			<div class="td-full"><h3 class="title">Clean Uninstall</h3></div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-full center" style="padding: 2% 0;">
				<?php $plugins_url = admin_url('plugins.php'); ?>
				<button id="cdp-uninstall-button" name="uninstall" target="<?php echo $plugins_url;?>" value="true">Perform Clean Uninstall</button>
				<div id="cdp-uninstall-dialog" title="Confirm Uninstall" style="display:none;">
					<p>Are you sure you want to do this?.</p>
					<p>Click 'Proceed' below to <strong>permanently</strong> delete all current plugin data and deactivate the plugin.</p>
					<p>The plugin will revert back to its <strong>default settings</strong> upon reactivation.</p>
					<div name="confirm-uninstall" >
						<button id="cdp-uninstall-yes" value="true">Proceed</button>
						<button id="cdp-uninstall-cancel" value="false">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<?php
		}
		?>
	</div>
<?php
	$this->admin_footer();
?>