<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;
?>

	<div id="view-EmailWrapper" class="view-wrapper">
		<div class="tr-wrapper">
			<div class="td-full">
				<h4 class="divider">AutoMall Emails <small>(Applies email filter based off of AutoMall ID)</small></h4>
				<button class="add-row-button" tag="emailRows">+</button>
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Email New:</span></div>
			<div class="td-two">
				<input type="text" id="default_email_new" class="cdp-input" name="vehicle_management_system/theme/emails/defaults/new" value="<?php echo $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'emails' ]['defaults']['new'] ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Email Used:</span></div>
			<div class="td-two">
				<input type="text" id="default_email_used" class="cdp-input" name="vehicle_management_system/theme/emails/defaults/used" value="<?php echo $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'emails' ]['defaults']['used'] ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color wrapper">
			<div id="emailRows" class="inner-table-content">
				<?php
				$rows = get_email_rows( $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'emails' ][ 'dealers' ] );
				echo $rows;
				?>
			</div>
			<div class="ajax-loading-message">Loading Rows...</div>
		</div>
	</div>