<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;
?>

	<div id="view-FormWrapper" class="view-wrapper">
		<div class="tr-wrapper">
			<div class="td-full">
				<h4 class="divider">Style Settings</h4>
				<button class="add-row-button" tag="styleRows">+</button>
			</div>
		</div>
		<div class="tr-wrapper tr-color wrapper">
			<div id="styleRows" class="inner-table-content">

				<?php
				$rows = get_style_rows( $this->options[ 'vehicle_management_system' ][ 'styles' ][ 'data' ] );
				echo $rows;
				?>
			</div>
			<div class="ajax-loading-message">Loading Rows...</div>
		</div>
	</div>