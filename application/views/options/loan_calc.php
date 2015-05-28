	<div class="tr-wrapper">
		<div class="td-full"><h4 class="divider">Loan Calculator Settings</h4></div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two right"><span class="td-title">Display Calculator:</span></div>
		<div class="td-two">
			<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'loan' ][ 'display_calc' ]; ?>
			<input type="checkbox" id="loan-display-calc" class="cdp-input" name="vehicle_management_system/theme/loan/display_calc" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
		</div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two right"><span class="td-title">Default Interest Rate:</span></div>
		<div class="td-two">
			<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'loan' ][ 'default_interest' ]; ?>
			<input type="text" id="loan-default-interest" class="cdp-input" name="vehicle_management_system/theme/loan/default_interest" value="<?php echo $value; ?>" />
		</div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two right"><span class="td-title">Default Trade Value:</span></div>
		<div class="td-two">
			<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'loan' ][ 'default_trade' ]; ?>
			<input type="text" id="loan-default-trade" class="cdp-input" name="vehicle_management_system/theme/loan/default_trade" value="<?php echo $value; ?>" />
		</div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two right"><span class="td-title">Default Term (months):</span></div>
		<div class="td-two">
			<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'loan' ][ 'default_term' ]; ?>
			<input type="text" id="loan-default-term" class="cdp-input" name="vehicle_management_system/theme/loan/default_term" value="<?php echo $value; ?>" />
		</div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two right"><span class="td-title">Default Down Payment:</span></div>
		<div class="td-two">
			<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'loan' ][ 'default_down' ]; ?>
			<input type="text" id="loan-default-down" class="cdp-input" name="vehicle_management_system/theme/loan/default_down" value="<?php echo $value; ?>" />
		</div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two right"><span class="td-title">Default Sales Tax:</span></div>
		<div class="td-two">
			<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'loan' ][ 'default_tax' ]; ?>
			<input type="text" id="loan-default-tax" class="cdp-input" name="vehicle_management_system/theme/loan/default_tax" value="<?php echo $value; ?>" />
		</div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two right"><span class="td-title">Display Monthly Cost:</span></div>
		<div class="td-two">
			<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'loan' ][ 'display_monthly' ]; ?>
			<input type="checkbox" id="loan-display-monthly" class="cdp-input" name="vehicle_management_system/theme/loan/display_monthly" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
		</div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two right"><span class="td-title">Display Bi-Monthly Cost:</span></div>
		<div class="td-two">
			<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'loan' ][ 'display_bi_monthly' ]; ?>
			<input type="checkbox" id="loan-display-bi-monthly" class="cdp-input" name="vehicle_management_system/theme/loan/display_bi_monthly" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
		</div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two right"><span class="td-title">Display Total Cost:</span></div>
		<div class="td-two">
			<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'loan' ][ 'display_total_cost' ]; ?>
			<input type="checkbox" id="loan-display-total-cost" class="cdp-input" name="vehicle_management_system/theme/loan/display_total_cost" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
		</div>
	</div>
