<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;
?>

	<div id="view-FormWrapper" class="view-wrapper">
		<div class="tr-wrapper">
			<div class="td-full">
				<h4 class="divider">Tag Settings</h4>
				<button class="add-row-button" tag="tagRows">+</button>
			</div>
		</div>
		<div class="tr-wrapper tr-color wrapper">
			<div id="tagRows" class="inner-table-content">

				<?php
				$rows = get_tag_rows( $this->options[ 'vehicle_management_system' ][ 'tags' ][ 'data' ] );
				echo $rows;
				?>
			</div>
			<div class="ajax-loading-message">Loading Rows...</div>
		</div>
	</div>