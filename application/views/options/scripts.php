<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;
?>

	<div id="view-FormWrapper" class="view-wrapper">
		<div class="tr-wrapper">
			<div class="td-full">
				<h4 class="divider">Script Settings</h4>
				<button class="add-row-button" tag="scriptRows">+</button>
			</div>
		</div>
		<div class="tr-wrapper tr-color wrapper">
			<div id="scriptRows" class="inner-table-content">

				<?php
				$rows = get_script_rows( $this->options[ 'vehicle_management_system' ][ 'scripts' ][ 'data' ] );
				echo $rows;
				?>
			</div>
			<div class="ajax-loading-message">Loading Rows...</div>
		</div>
	</div>