<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

?>
	<div class="tr-wrapper">
		<div class="td-full"><h4 class="divider">Loan Calculator Settings</h4></div>
	</div>
	<div id="cdp-content-inner-tab-content">
		<div id="inner-tab-content-used" class="inner-tab-button tab-button-used active">Used</div>
		<div id="inner-tab-content-new" class="inner-tab-button tab-button-new">New</div>
		<div id="inner-tab-content-custom" class="inner-tab-button tab-button-custom">Custom</div>
	</div>
	<div id="inner-content-used" class="tab-inner-content inner-tab-content-used active">
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title" title="Will display a Loan Calculator on the detail page, which will give the customer the abilty to estimate a payment value.">Display Calculator:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['display_calc']; ?>
				<input type="checkbox" id="loan-display-calc" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/display_calc" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title" title="Will display an estimated monthly payment based on the default value set. Customer will not be able to alter this value. Will display on both LIST and DETAIL pages.">Display Payment:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['display_payment']; ?>
				<input type="checkbox" id="loan-display-calc" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/display_payment" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Display Monthly Cost:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['display_monthly']; ?>
				<input type="checkbox" id="loan-display-monthly" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/display_monthly" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Display Bi-Monthly Cost:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['display_bi_monthly']; ?>
				<input type="checkbox" id="loan-display-bi-monthly" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/display_bi_monthly" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Display Total Cost:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['display_total_cost']; ?>
				<input type="checkbox" id="loan-display-total-cost" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/display_total_cost" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<hr>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Interest Rate:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['interest']; ?>
				<input type="text" id="loan-default-interest" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/interest" value="<?php echo $value; ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Trade Value:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['trade']; ?>
				<input type="text" id="loan-default-trade" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/trade" value="<?php echo $value; ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Term (months):</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['term']; ?>
				<input type="text" id="loan-default-term" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/term" value="<?php echo $value; ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Down Payment:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['down']; ?>
				<input type="text" id="loan-default-down" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/down" value="<?php echo $value; ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Sales Tax:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['tax']; ?>
				<input type="text" id="loan-default-tax" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/tax" value="<?php echo $value; ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Disclaimer:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['disclaimer']; ?>
				<textarea type="text" id="loan-default-disclaimer" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/disclaimer" ><?php echo $value; ?></textarea>
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Display Text:<br><small>Insert [payment] to display actual payment value.</small></span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['used']['default']['display_text']; ?>
				<textarea type="text" id="loan-default-text" class="cdp-input" name="vehicle_management_system/theme/loan/used/default/display_text" ><?php echo $value; ?></textarea>
			</div>
		</div>
	</div>
	<!-- -->
	<div id="inner-content-new" class="tab-inner-content inner-tab-content-new">
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title" title="Will display a Loan Calculator on the detail page, which will give the customer the abilty to estimate a payment value.">Display Calculator:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['display_calc']; ?>
				<input type="checkbox" id="loan-display-calc-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/display_calc" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title" title="Will display an estimated monthly payment based on the default value set. Customer will not be able to alter this value. Will display on both LIST and DETAIL pages.">Display Payment:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['display_payment']; ?>
				<input type="checkbox" id="loan-display-calc-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/display_payment" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Display Monthly Cost:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['display_monthly']; ?>
				<input type="checkbox" id="loan-display-monthly-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/display_monthly" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Display Bi-Monthly Cost:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['display_bi_monthly']; ?>
				<input type="checkbox" id="loan-display-bi-monthly-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/display_bi_monthly" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Display Total Cost:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['display_total_cost']; ?>
				<input type="checkbox" id="loan-display-total-cost-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/display_total_cost" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<hr>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Interest Rate:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['interest']; ?>
				<input type="text" id="loan-default-interest-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/interest" value="<?php echo $value; ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Trade Value:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['trade']; ?>
				<input type="text" id="loan-default-trade-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/trade" value="<?php echo $value; ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Term (months):</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['term']; ?>
				<input type="text" id="loan-default-term-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/term" value="<?php echo $value; ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Down Payment:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['down']; ?>
				<input type="text" id="loan-default-down-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/down" value="<?php echo $value; ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Sales Tax:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['tax']; ?>
				<input type="text" id="loan-default-tax-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/tax" value="<?php echo $value; ?>" />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Default Disclaimer:</span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['disclaimer']; ?>
				<textarea type="text" id="loan-default-disclaimer-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/disclaimer" ><?php echo $value; ?></textarea>
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-two right"><span class="td-title">Display Text:<br><small>(Insert [payment] to display actual payment value)</small></span></div>
			<div class="td-two">
				<?php $value = $this->options['vehicle_management_system']['theme']['loan']['new']['default']['display_text']; ?>
				<textarea type="text" id="loan-default-text-new" class="cdp-input" name="vehicle_management_system/theme/loan/new/default/display_text" ><?php echo $value; ?></textarea>
			</div>
		</div>
	</div>
	<!-- -->
	<div id="inner-content-custom" class="tab-inner-content inner-tab-content-custom">
		<div class="tr-wrapper">
			<div class="td-full" style="text-align:center;">
				<button style="font-size: 90%;margin: 0 auto;width: 150px;" class="add-row-button" tag="loanRows">Add Custom Loan +</button>
			</div>
		</div>
		<div class="tr-wrapper tr-color wrapper">
			<div id="loanRows" class="inner-table-content">
				<?php
				$rows = get_loan_rows( $this->options['vehicle_management_system']['theme']['loan']['custom'] );
				echo $rows;
				?>
			</div>
			<div class="ajax-loading-message">Loading Rows...</div>
		</div>
	</div>