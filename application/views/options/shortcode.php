<?php
namespace Wordpress\Plugins\Dealertrend\Inventory\Api;

	$this->load_admin_assets();
	$this->get_admin_header();
?>

<div id="cdp-content-wrapper" class="shortcode-wrapper">
	<div id="cdp-content-tab-wrapper">
		<div id="tab-content-inventory-list" class="tab-button tab-button-list active" >[inventory_list]</div>
		<div id="tab-content-inventory-detail" class="tab-button tab-button-detail" >[inventory_detail]</div>
		<div id="tab-content-inventory-slider" class="tab-button tab-button-slider" >[inventory_slider]</div>
	</div>
	
	<div id="cdp-content-display-wrapper">
		<div id="content-inventory-list" class="tab-content tab-content-inventory-list active">
			<div class="tr-wrapper tr-color">
				<div class="td-full center">
					<span style="color: #000; font-size: 12px; display: block;"><span style="font-weight: bold;">[inventory_list]</span></span>
					<span style="color: #000; font-size: 12px; display: block; margin: 0;">e.g. [inventory_list saleclass="Used" make="Ford" model="F-150"]</span>
				</div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center"><span class="td-title">Parameters</span></div>
				<div class="td-two long center"><span class="td-title">Acceptable Values</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">saleclass</div>
				<div class="td-two long center">New,Used<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: New</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">make</div>
				<div class="td-two long center">Make Name (e.g. Dodge, Toyota, etc)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">model</div>
				<div class="td-two long center">Model Name (e.g. Charger, Camry, etc)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">trim</div>
				<div class="td-two long center">Trim Name (e.g. SE, XLE, etc)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">vehicleclass</div>
				<div class="td-two long center">car, truck, sport_utility, van, minivan<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">price_from</div>
				<div class="td-two long center">Any numeric value (e.g. 1000)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: empty</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">price_to</div>
				<div class="td-two long center">Any numeric value (e.g. 100000)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: empty</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">certified</div>
				<div class="td-two long center">yes<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: empty</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">tag</div>
				<div class="td-two long center"> special, gas-saver, cherry-deal, good-buy, low-miles, one-owner, sale-pending, custom-wheels, hybrid, local-trade-in, moon-roof, navigation, priced-to-go, rare, under-blue-book, wont-last <span style="display: block; font-size: 10px; font-style: italic; color: #707070">*custom tags also acceptable</span> <span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: empty</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">limit</div>
				<div class="td-two long center">Any numeric value between 1-50<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 10</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">style</div>
				<div class="td-two long center">newspaper,newspaper_sb,clear<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: newspaper</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">dealer_id</div>
				<div class="td-two long center">Pull the inventory from another VMS ID (Useful for sites that are using an Automall ID)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: Settings ID</span></div>
			</div>
		</div>
		
		<div id="content-inventory-detail" class="tab-content tab-content-inventory-detail">
			<div class="tr-wrapper tr-color">
				<div class="td-full center">
					<span style="color: #000; font-size: 12px; display: block;"><span style="font-weight: bold;">[inventory_detail]</span></span>
					<span style="color: #000; font-size: 12px; display: block; margin: 0;">e.g. [inventory_detail form_id=5 search=1 saleclass="Used"]</span>
				</div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center"><span class="td-title">Parameters</span></div>
				<div class="td-two long center"><span class="td-title">Acceptable Values</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">saleclass</div>
				<div class="td-two long center">All,New,Used<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">d_saleclass</div>
				<div class="td-two long center">All,New,Used<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: New <small>(only works when salecass = all)</small></span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">make</div>
				<div class="td-two long center">Make Name (e.g. Dodge, Toyota, etc)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">model</div>
				<div class="td-two long center">Model Name (e.g. Charger, Camry, etc)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">trim</div>
				<div class="td-two long center">Trim Name (e.g. SE, XLE, etc)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">vehicleclass</div>
				<div class="td-two long center">car, truck, sport_utility, van, minivan<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">tag</div>
				<div class="td-two long center"> special, gas-saver, cherry-deal, good-buy, low-miles, one-owner, sale-pending, custom-wheels, hybrid, local-trade-in, moon-roof, navigation, priced-to-go, rare, under-blue-book, wont-last <span style="display: block; font-size: 10px; font-style: italic; color: #707070">*custom tags also acceptable</span> <span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: empty</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">limit</div>
				<div class="td-two long center">Any numeric value between 1-50<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 10</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">dealer_id</div>
				<div class="td-two long center">Pull the inventory from another VMS ID (Useful for sites that are using an Automall ID)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: Settings ID</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">form_id</div>
				<div class="td-two long center">ID of Gravity Form <span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 0</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">search</div>
				<div class="td-two long center">Toggle search feature on or off.<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 0 (off)</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">style</div>
				<div class="td-two long center">clear<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: detail</span></div>
			</div>
		</div>
		
		<div id="content-inventory-slider" class="tab-content tab-content-inventory-slider">
			<div class="tr-wrapper tr-color">
				<div class="td-full center">
					<span style="color: #000; font-size: 12px; display: block;"><span style="font-weight: bold;">[inventory_slider]</span></span>
					<span style="color: #000; font-size: 12px; display: block; margin: 0;">e.g. [inventory_slider saleclass="Used"]</span>
				</div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center"><span class="td-title">Parameters</span></div>
				<div class="td-two long center"><span class="td-title">Acceptable Values</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">saleclass</div>
				<div class="td-two long center">All,New,Used<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">make</div>
				<div class="td-two long center">Make Name (e.g. Dodge, Toyota, etc)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">model</div>
				<div class="td-two long center">Model Name (e.g. Charger, Camry, etc)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">trim</div>
				<div class="td-two long center">Trim Name (e.g. SE, XLE, etc)<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">vehicleclass</div>
				<div class="td-two long center">car, truck, sport_utility, van, minivan<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: All</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">tag</div>
				<div class="td-two long center"> special, gas-saver, cherry-deal, good-buy, low-miles, one-owner, sale-pending, custom-wheels, hybrid, local-trade-in, moon-roof, navigation, priced-to-go, rare, under-blue-book, wont-last <span style="display: block; font-size: 10px; font-style: italic; color: #707070">*custom tags also acceptable</span> <span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: empty</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">limit</div>
				<div class="td-two long center">Any numeric value between 1-50<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 10</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">price</div>
				<div class="td-two long center">A numeric value range separated by a comma<span style="display: block; font-size: 10px; font-style: italic; color: #707070">e.g.: 1700,23000</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">mileage</div>
				<div class="td-two long center">A numeric value range separated by a comma<span style="display: block; font-size: 10px; font-style: italic; color: #707070">e.g.: 78000,92111</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">year</div>
				<div class="td-two long center">A numeric value range separated by a comma<span style="display: block; font-size: 10px; font-style: italic; color: #707070">e.g.: 1990,1998</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">dealer_id</div>
				<div class="td-two long center">Pull the inventory from another VMS ID (Useful for sites that are using an Automall ID).<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: Settings ID</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">is_slider</div>
				<div class="td-two long center">Toggle slider feature on or off.<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 1 (on)</span></div>
			</div>
			
			<hr>
			
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">title</div>
				<div class="td-two long center">Title to display over slider.<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: empty</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">autoplay</div>
				<div class="td-two long center">Toggle autoplay feature on or off.<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 1 (on)</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">center_mode</div>
				<div class="td-two long center">Toggle center_mode feature on or off.<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 0 (off)</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">dots</div>
				<div class="td-two long center">Toggle dots feature on or off.<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 0 (off)</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">infinite</div>
				<div class="td-two long center">Toggle infinite feature on or off.<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 1 (on)</span></div>
			</div>
			<div class="tr-wrapper tr-color">
				<div class="td-two short center">autoplay_speed</div>
				<div class="td-two long center">Control speed of the slider.<span style="display: block; font-size: 10px; font-style: italic; color: #707070">Default: 3000</span></div>
			</div>
		</div>
		
	</div>
</div>

<?php
	$this->admin_footer();
?>