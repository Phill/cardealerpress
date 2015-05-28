<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;
?>

	<div id="view-FormWrapper" class="view-wrapper">
		<div class="tr-wrapper">
			<div class="td-full">
				<h4 class="divider">Gravity Forms <small>(Detail Page)</small></h4>
				<button class="add-row-button" tag="formRows">+</button>
			</div>
		</div>
		<div class="tr-wrapper tr-color wrapper">
			<div id="formRows" class="inner-table-content">
				<?php
				$rows = get_form_rows( $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'forms' ] );
				echo $rows;
				?>
			</div>
			<div class="ajax-loading-message">Loading Rows...</div>
		</div>
		<div class="tr-wrapper">
			<div class="td-full">
				<h4 class="divider">Hooks <small>(Also apply to list forms)</small><br><small style="display: block; width: 90%; margin: 0 auto; text-align: center;">dt_stock_number, dt_vin, dt_year, dt_make, dt_model, dt_trim, dt_saleclass, dt_exterior, dt_interior, dt_mileage, dt_price, dt_dealer, dt_dealer_id, dt_location, dt_phone</small></h4>
			</div>
		</div>
	</div>