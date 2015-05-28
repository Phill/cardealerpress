<?php
namespace Wordpress\Plugins\Dealertrend\Inventory\Api;

	$this->load_admin_assets();
	$this->get_admin_header();
	
	$year = date( 'Y' );
	$inactive_models = empty($this->options['vehicle_reference_system']['data']['makes'])?'inactive':'';
	$inactive_trims = (!empty($inactive_models) || vrs_isEmpty($this->options['vehicle_reference_system']['data']['models']) )?'inactive':'';
	$refresh_image = cdp_get_image_source().'refresh_icon.png';
	//print_r($this->options[ 'vehicle_reference_system' ]);
?>
<div id="cdp-content-wrapper" class="showcase-wrapper">
	<div id="cdp-content-tab-wrapper">
		<div id="tab-content-general" class="tab-button tab-button-general active" >General</div>
		<div id="tab-content-theme" class="tab-button tab-button-theme" tag="current-theme">Theme</div>
		<div id="tab-content-videos" class="tab-button tab-button-videos">Videos</div>
		<div id="tab-content-messages" class="tab-button tab-button-messages">Messages</div>
	</div>
	<div id="cdp-content-display-wrapper">
		<div id="content-general" class="tab-content tab-content-general active">			
			<div class="tr-wrapper">
				<div class="td-full"><h4 class="divider">Make Filter</h4></div>
			</div>			
			<div class="tr-wrapper tr-color">
				<div class="td-full left">
					<button name="vrs-make-filter" tag="getMakesVRS" filter="<?php echo $year; ?>" class="msw-button">Makes</button>
				</div>
				<div class="td-full">
					<div id="vrs-make-filter" class="msw-view-wrapper">
						<div class="ajax-loading-message">Loading Make Data...</div>
					</div>
				</div>
			</div>
			<div class="tr-wrapper">
				<div class="td-full"><h4 class="divider">Model Filter <img src="<?php echo $refresh_image; ?>" class="refresh-button" tag="vrs-model-filter" title="Refresh Data" /></h4></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-full left">
					<button name="vrs-model-filter-<?php echo $year+1; ?>" tag="getModelsVRS" filter="<?php echo $year + 1; ?>" class="msw-button <?php echo $inactive_models;?>">Models (<?php echo $year + 1; ?>)</button>
				</div>
				<div class="td-full">
					<div id="vrs-model-filter-<?php echo $year+1; ?>" class="msw-view-wrapper vrs-model-filter">
						<div class="ajax-loading-message">Loading Model Data...</div>
					</div>
				</div>
				<div class="td-full left">
					<button name="vrs-model-filter-<?php echo $year; ?>" tag="getModelsVRS" filter="<?php echo $year; ?>" class="msw-button <?php echo $inactive_models;?>">Models (<?php echo $year; ?>)</button>
				</div>
				<div class="td-full">
					<div id="vrs-model-filter-<?php echo $year; ?>" class="msw-view-wrapper vrs-model-filter">
						<div class="ajax-loading-message">Loading Model Data...</div>
					</div>
				</div>
				<div class="td-full left">
					<button name="vrs-model-filter-<?php echo $year-1; ?>" tag="getModelsVRS" filter="<?php echo $year - 1; ?>" class="msw-button <?php echo $inactive_models;?>">Models (<?php echo $year - 1; ?>)</button>
				</div>
				<div class="td-full">
					<div id="vrs-model-filter-<?php echo $year-1; ?>" class="msw-view-wrapper vrs-model-filter">
						<div class="ajax-loading-message">Loading Model Data...</div>
					</div>
				</div>
			</div>
			
			<div class="tr-wrapper">
				<div class="td-full"><h4 class="divider">Trim Filter <img src="<?php echo $refresh_image; ?>" class="refresh-button" tag="vrs-trim-filter" title="Refresh Data" /></h4></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-full left">
					<button name="vrs-trim-filter-<?php echo $year+1; ?>" tag="getTrimsVRS" filter="<?php echo $year + 1; ?>" class="msw-button <?php echo $inactive_trims;?>">Trims (<?php echo $year + 1; ?>)</button>
				</div>
				<div class="td-full">
					<div id="vrs-trim-filter-<?php echo $year+1; ?>" class="msw-view-wrapper vrs-trim-filter">
						<div class="ajax-loading-message">Loading Trim Data...</div>
					</div>
				</div>
				<div class="td-full left">
					<button name="vrs-trim-filter-<?php echo $year; ?>" tag="getTrimsVRS" filter="<?php echo $year; ?>" class="msw-button <?php echo $inactive_trims;?>">Trims (<?php echo $year; ?>)</button>
				</div>
				<div class="td-full">
					<div id="vrs-trim-filter-<?php echo $year; ?>" class="msw-view-wrapper vrs-trim-filter">
						<div class="ajax-loading-message">Loading Trim Data...</div>
					</div>
				</div>
				<div class="td-full left">
					<button name="vrs-trim-filter-<?php echo $year-1; ?>" tag="getTrimsVRS" filter="<?php echo $year - 1; ?>" class="msw-button <?php echo $inactive_trims;?>">Trims (<?php echo $year - 1; ?>)</button>
				</div>
				<div class="td-full">
					<div id="vrs-trim-filter-<?php echo $year-1; ?>" class="msw-view-wrapper vrs-trim-filter">
						<div class="ajax-loading-message">Loading Trim Data...</div>
					</div>
				</div>
			</div>
		</div>
		
		<div id="content-CurrentTheme" class="tab-content tab-content-theme">
			<div id="ajax-CurrentTheme" class="ajax-loading-message">Loading New Theme...</div>
			<?php
				echo get_admin_showcase_theme_view( $this->options[ 'vehicle_reference_system' ][ 'theme' ]);
			?>
		</div>
		
		<div id="content-videos" class="tab-content tab-content-videos">
			<div id="view-VideoWrapper" class="view-wrapper">
				<div class="tr-wrapper">
					<div class="td-full">
						<h4 class="divider">Video Settings</h4>
						<button class="add-row-button" tag="videoRows">+</button>
					</div>
				</div>
				<div class="tr-wrapper tr-color wrapper">
					<div id="videoRows" class="inner-table-content">
						<?php
							$rows = get_video_rows( $this->options[ 'vehicle_reference_system' ][ 'videos' ] );
							echo $rows;
						?>
					</div>
					<div class="ajax-loading-message">Loading Rows...</div>
				</div>
			</div>
		</div>
		
		<div id="content-messages" class="tab-content tab-content-messages">
			<div id="view-MessagesWrapper" class="view-wrapper">
				<div class="tr-wrapper">
					<div class="td-full">
						<h4 class="divider">Custom Messages</h4>
						<button class="add-row-button" tag="messageRows">+</button>
					</div>
				</div>
				<div class="tr-wrapper tr-color wrapper">
					<div id="messageRows" class="inner-table-content">
						<?php
							$rows = get_message_rows( $this->options[ 'vehicle_reference_system' ][ 'messages' ] );
							echo $rows;
						?>
					</div>
					<div class="ajax-loading-message">Loading Rows...</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
<?php
	$this->admin_footer();
?>