<?php
namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

	$this->load_admin_assets();
	$this->get_admin_header();
	
?>

<div id="cdp-content-wrapper" class="feed-wrapper">
	<div class="tr-wrapper">
		<div class="td-full"><h3 class="title">Vehicle Management System Feed</h3></div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-full center">
			<?php
				if( !empty($this->vms) && !empty($this->company) ){ 
					echo '<span class="td-title">Feed Status: </span> <span class="td-message succuss">VMS Connection Active</span>';
				} else {
					echo '<span class="td-title">Feed Status: </span> <span class="td-message error">Missing valid company ID in settings page.</span>';
				}
			?>
		</div>
	</div>
	
	<div class="tr-wrapper">
		<div class="td-full"><h3 class="title">Company Information</h3></div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two center"><span class="td-title">Company ID:</span></div>
		<div class="td-two"><?php echo $this->company->id; ?></div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two center"><span class="td-title">Company Name:</span></div>
		<div class="td-two"><?php echo $this->company->name; ?></div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two center"><span class="td-title">Company Address:</span></div>
		<div class="td-two"><?php echo $this->company->street.'</br>'.$this->company->city.', '.$this->company->state.' '.$this->company->zip; ?></div>
	</div>
	<div class="tr-wrapper tr-color">
		<div class="td-two center"><span class="td-title">Country Code:</span></div>
		<div class="td-two"><?php echo $this->company->country_code; ?></div>
	</div>
	<?php if( isset($this->company->automall_ids) ){
		$dealers = $this->vms->get_automall_dealer_names();
		if( $dealers ){
			echo '<div class="tr-wrapper tr-color">';
			echo '<div class="td-two center"><span class="td-title">AutoMall Accounts:</span></div>';
			echo '<div class="td-two">';
			foreach( $dealers[$this->company->id] as $dealer){
				foreach( $dealer as $id => $name ){
					echo '<span class="automall-dealer-name">'.$name.' ('.$id.')</span>';	
				}
			}
			echo '</div>';
			echo '</div>';	
		}
	}?>
</div>
<?php
	$this->admin_footer();
?>
