<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;
?>

	<div id="view-KeywordWrapper" class="view-wrapper">
		<div class="tr-wrapper">
			<div class="td-full">
				<h4 class="divider">Type Ahead Keywords <small>(List page text search)</small></h4>
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<?php $enabled = $this->options['vehicle_management_system']['keywords']['enable']; ?>
			<div class="td-two right"><span class="td-title"><?php echo (empty($enabled)?'Enable ':'Disable ');?> Type Ahead:</span></div>
			<div class="td-two">
				<input type="checkbox" id="enable-keywords" class="cdp-input" name="vehicle_management_system/keywords/enable" <?php echo ( !empty($enabled) ) ? ' checked ' : ''; ?> />
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-full "><span class="td-title center">Add Keywords: <small>(comma separated)</small></span></div>
			<div class="td-full">
				<textarea type="text" id="add_keywords" class="cdp-input" name="vehicle_management_system/keywords/add"><?php echo $this->options['vehicle_management_system']['keywords']['add'];?></textarea>
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<div class="td-full "><span class="td-title center">Remove Keywords: <small>(comma separated)</small></span></div>
			<div class="td-full">
				<textarea type="text" id="remove_keywords" class="cdp-input" name="vehicle_management_system/keywords/exclude"><?php echo $this->options['vehicle_management_system']['keywords']['exclude'];?></textarea>
			</div>
		</div>
		<div class="tr-wrapper tr-color">
			<?php $keywords = !empty($this->options[ 'vehicle_management_system' ][ 'keywords' ]['pot']) ? explode(',',$this->options[ 'vehicle_management_system' ][ 'keywords' ]['pot']) : array();?>
			<div class="td-two center"><span class="td-title">Stored Keywords:</span></div>
			<button class="generate-keyword-pot" tag="KeywordPot"><?php echo empty($keywords) ? 'Generate' : 'Refresh'; ?></button>
			<div class="td-full">
				<div id="KeywordPot">
					<ul class="keyword-list">
						<?php
							foreach($keywords as $word){
								echo '<li>'.$word.'</li>';
							}
						?>
					</ul>
				</div>
				<div class="ajax-loading-message">Generating Keyword Pot...</div>
			</div>
		</div>
	</div>