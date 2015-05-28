
	<div class="tr-wrapper">
		<div class="td-full"><h4 class="divider">Flex Settings</h4></div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two right"><span class="td-title">Use Flex:</span></div>
		<div class="td-two">
			<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'toggle' ]; ?>
			<input type="checkbox" id="flex-toggle-flag" class="cdp-input" name="vehicle_management_system/theme/style/flex/toggle" <?php echo ( !empty($value) ) ? ' checked ' : ''; ?> />
		</div>
	</div>

	<div class="tr-wrapper tr-color wrapper">
		<div id="flexSettings" class="inner-table-content">
			<div class="inner-row-wrapper">
				<div class="inner-row-label"><h5>Container One</h5></div>
				<div class="inner-row-content">
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'one' ][ 'order' ]; ?>
						<div class="inner-td label half"><span class="td-title">Order:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/one/order" id="" class="cdp-input input-short" value="<?php echo $value; ?>" /></div>
					</div>
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'one' ][ 'max-width' ]; ?>
						<div class="inner-td label half"><span class="td-title">Max Width:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/one/max-width" id="" class="cdp-input input-med" value="<?php echo $value; ?>" /> px</div>
					</div>
				</div>
			</div>
			<div class="inner-row-wrapper">
				<div class="inner-row-label"><h5>Container Two</h5></div>
				<div class="inner-row-content">
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'two' ][ 'order' ]; ?>
						<div class="inner-td label half"><span class="td-title">Order:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/two/order" id="" class="cdp-input input-short" value="<?php echo $value; ?>" /></div>
					</div>
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'two' ][ 'max-width' ]; ?>
						<div class="inner-td label half"><span class="td-title">Max Width:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/two/max-width" id="" class="cdp-input input-med" value="<?php echo $value; ?>" /> px</div>
					</div>
				</div>
			</div>
			<div class="inner-row-wrapper">
				<div class="inner-row-label"><h5>Container Three</h5></div>
				<div class="inner-row-content">
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'three' ][ 'order' ]; ?>
						<div class="inner-td label half"><span class="td-title">Order:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/three/order" id="" class="cdp-input input-short" value="<?php echo $value; ?>" /></div>
					</div>
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'three' ][ 'max-width' ]; ?>
						<div class="inner-td label half"><span class="td-title">Max Width:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/three/max-width" id="" class="cdp-input input-med" value="<?php echo $value; ?>" /> px</div>
					</div>
				</div>
			</div>
			<div class="inner-row-wrapper">
				<div class="inner-row-label"><h5>Container Four</h5></div>
				<div class="inner-row-content">
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'four' ][ 'order' ]; ?>
						<div class="inner-td label half"><span class="td-title">Order:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/four/order" id="" class="cdp-input input-short" value="<?php echo $value; ?>" /></div>
					</div>
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'four' ][ 'max-width' ]; ?>
						<div class="inner-td label half"><span class="td-title">Max Width:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/four/max-width" id="" class="cdp-input input-med" value="<?php echo $value; ?>" /> px</div>
					</div>
				</div>
			</div>
			<div class="inner-row-wrapper">
				<div class="inner-row-label"><h5>Container Five</h5></div>
				<div class="inner-row-content">
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'five' ][ 'order' ]; ?>
						<div class="inner-td label half"><span class="td-title">Order:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/five/order" id="" class="cdp-input input-short" value="<?php echo $value; ?>" /></div>
					</div>
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'five' ][ 'max-width' ]; ?>
						<div class="inner-td label half"><span class="td-title">Max Width:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/five/max-width" id="" class="cdp-input input-med" value="<?php echo $value; ?>" /> px</div>
					</div>
				</div>
			</div>
			<div class="inner-row-wrapper">
				<div class="inner-row-label"><h5>Container Six</h5></div>
				<div class="inner-row-content">
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'six' ][ 'order' ]; ?>
						<div class="inner-td label half"><span class="td-title">Order:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/six/order" id="" class="cdp-input input-short" value="<?php echo $value; ?>" /></div>
					</div>
					<div class="inner-tr-wrapper">
						<?php $value = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'style' ][ 'flex' ][ 'settings' ][ 'containers' ][ 'six' ][ 'max-width' ]; ?>
						<div class="inner-td label half"><span class="td-title">Max Width:</span></div>
						<div class="inner-td value half"><input type="number" name="vehicle_management_system/theme/style/flex/settings/containers/six/max-width" id="" class="cdp-input input-med" value="<?php echo $value; ?>" /> px</div>
					</div>
				</div>
			</div>
			
		</div>
		<div class="ajax-loading-message">Loading Theme Flex Settings...</div>
	</div>