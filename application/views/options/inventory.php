<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	$this->load_admin_assets();
	$this->get_admin_header();
	
?>

<div id="cdp-content-wrapper" class="inventory-wrapper">
	<div id="cdp-content-tab-wrapper">
		<div id="tab-content-general" class="tab-button tab-button-general active" >General</div>
		<div id="tab-content-theme" class="tab-button tab-button-theme" tag="current-theme">Theme</div>
		<!--<div id="tab-content-flex" class="tab-button tab-button-flex" >Flex</div>-->
		<div id="tab-content-loan" class="tab-button tab-button-loan" >Loan Calculator</div>
		<div id="tab-content-price" class="tab-button tab-button-price" >Price Text</div>
		<div id="tab-content-tags" class="tab-button tab-button-tags" >Tags</div>
		<?php
			if( function_exists('gravity_form') ){
				echo '<div id="tab-content-forms" class="tab-button tab-button-forms" >Forms</div>';
				echo '<div id="tab-content-emails" class="tab-button tab-button-emails" >AutoMall Email</div>';
			}
		?>
		<div id="tab-content-scripts" class="tab-button tab-button-scripts" >Scripts</div>
		<div id="tab-content-styles" class="tab-button tab-button-styles" >Styles</div>
	</div>
	<div id="cdp-content-display-wrapper">
		<div id="content-general" class="tab-content tab-content-general active">
			<div class="tr-wrapper">
				<div class="td-full"><h4 class="divider">General Settings</h4></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two right"><span class="td-title">Vehicles Per Page:</span></div>
				<div class="td-two">
					<select name="vehicle_management_system/theme/per_page" class="cdp-input">
						<?php
						for( $i = 1; $i <= 50; $i ++ ) {
							$selected = ( $i == $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'per_page' ] ) ? 'selected' : NULL;
							echo '<option ' . $selected . ' value="' . $i . '">'. $i .'</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two right"><span class="td-title">Sale Class Filter:</span></div>
				<div class="td-two">
					<select name="vehicle_management_system/saleclass" class="cdp-input">
						<option value="all" <?php if ( $this->options[ 'vehicle_management_system' ][ 'saleclass' ] == 'all' ) { echo 'selected'; } ?> ><?php _e('All'); ?></option>
						<option value="new" <?php if ( $this->options[ 'vehicle_management_system' ][ 'saleclass' ] == 'new' ) { echo 'selected'; } ?> ><?php _e('New'); ?></option>
						<option value="used" <?php if ( $this->options[ 'vehicle_management_system' ][ 'saleclass' ] == 'used' ) { echo 'selected'; } ?> ><?php _e('Used'); ?></option>
						<option value="certified" <?php if ( $this->options[ 'vehicle_management_system' ][ 'saleclass' ] == 'certified' ) { echo 'selected'; } ?> ><?php _e('Certified'); ?></option>
					</select>
				</div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two label right"><span class="td-title">New Make Filter:</span></div>
				<div class="td-two">
					<button name="new-make-filter" tag="getMakes" class="msw-button">Show New Makes</button>
				</div>
				<div class="td-full">
					<div id="new-make-filter" class="msw-view-wrapper">
						<div class="ajax-loading-message">Loading Make Data...</div>
					</div>
				</div>
			</div>
			
			<div class="tr-wrapper tr-color">
				<div class="td-two right"><span class="td-title"><a id="media-default-no-image" href="#" class="custom_media_upload custom_media_upload_default_image default-no-image">Default No Image:</a></span></div>
				<div class="td-two">
					<input name="vehicle_management_system/data/default_no_image" type="text" class="cdp-input custom_media_url media-default-no-image" value="<?php echo $this->options['vehicle_management_system']['data']['default_no_image'];?>"/>
				</div>
			</div>
			
			<div class="tr-wrapper">
				<div class="td-full"><h4 class="divider">Company Override</h4></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two right"><span class="td-title">Company Phone New:</span></div>
				<div class="td-two">
					<input type="text" id="phone_new" class="cdp-input" name="vehicle_management_system/custom_contact/phone/new" value="<?php echo $this->options[ 'vehicle_management_system' ][ 'custom_contact' ][ 'phone' ]['new'] ?>" />
				</div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two right"><span class="td-title">Company Phone Used:</span></div>
				<div class="td-two">
					<input type="text" id="phone_used" class="cdp-input" name="vehicle_management_system/custom_contact/phone/used" value="<?php echo $this->options[ 'vehicle_management_system' ][ 'custom_contact' ][ 'phone' ]['used'] ?>" />
				</div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two right"><span class="td-title">Company Name New:</span></div>
				<div class="td-two">
					<input type="text" id="name_new" class="cdp-input" name="vehicle_management_system/custom_contact/contact_name/new" value="<?php echo $this->options[ 'vehicle_management_system' ][ 'custom_contact' ][ 'contact_name' ]['new'] ?>" />
				</div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two right"><span class="td-title">Company Name Used:</span></div>
				<div class="td-two">
					<input type="text" id="name_used" class="cdp-input" name="vehicle_management_system/custom_contact/contact_name/used" value="<?php echo $this->options[ 'vehicle_management_system' ][ 'custom_contact' ][ 'contact_name' ]['used'] ?>" />
				</div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two right"><span class="td-title">Company Breadcrumb:</span></div>
				<div class="td-two">
					<input type="text" id="breadcrumb" class="cdp-input" name="vehicle_management_system/custom_contact/breadcrumb" value="<?php echo $this->options[ 'vehicle_management_system' ][ 'custom_contact' ][ 'breadcrumb' ] ?>" />
				</div>
			</div>
		</div>
		<div id="content-CurrentTheme" class="tab-content tab-content-theme">
			<div id="ajax-CurrentTheme" class="ajax-loading-message">Loading New Theme...</div>
			<?php
			echo get_admin_theme_view( $this->options[ 'vehicle_management_system' ][ 'theme' ]);
			?>
		</div>
		<div id="content-flex" class="tab-content tab-content-flex">
			<?php include( dirname( __FILE__ ) . '/flex_settings.php' ); ?>
		</div>
		<div id="content-loan" class="tab-content tab-content-loan">
			<?php include( dirname( __FILE__ ) . '/loan_calc.php' ); ?>
		</div>
		<div id="content-price" class="tab-content tab-content-price">
			<?php include( dirname( __FILE__ ) . '/price_text.php' ); ?>
		</div>
		<div id="content-tags" class="tab-content tab-content-tags">
			<?php include( dirname( __FILE__ ) . '/tags.php' ); ?>
		</div>
		<?php
			if( function_exists('gravity_form') ){
				echo '<div id="content-forms" class="tab-content tab-content-forms">';
				include( dirname( __FILE__ ) . '/gravity_forms_add_on.php' );
				echo '</div>';
				echo '<div id="content-emails" class="tab-content tab-content-emails">';
				include( dirname( __FILE__ ) . '/automall_emails.php' );
				echo '</div>';
			}
		?>
		<div id="content-scripts" class="tab-content tab-content-scripts">
			<?php include( dirname( __FILE__ ) . '/scripts.php' ); ?>
		</div>
		<div id="content-styles" class="tab-content tab-content-styles">
			<?php include( dirname( __FILE__ ) . '/styles.php' ); ?>
		</div>
	</div>
</div>

<?php
	$this->admin_footer();
?>