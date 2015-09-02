<?php
/**********
	VMS Search Box
**********/
class vms_search_box_widget extends WP_Widget {
	private $widget_cdp_vms;
	private $widget_cdp_options;
	// constructor
	function vms_search_box_widget() {
		parent::__construct(false, $name = 'VMS Search Box', array( 'classname' => 'vms-search-box-widget', 'description' => 'Creates a VMS search box, which allows a user to search through the assigned VMS ID inventory.' ) );

		global $pagenow;
		
		$this->widget_cdp_options = get_option( 'cardealerpress_settings' );
		$this->widget_cdp_vms = new Wordpress\Plugins\CarDealerPress\Inventory\Api\vehicle_management_system(
			$this->widget_cdp_options[ 'vehicle_management_system' ][ 'host' ],
			$this->widget_cdp_options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]
		);

		wp_register_script(
			'vms-search-box-js' ,
			cdp_get_js_source().'widgets/vms-search-box-widget.js',
			array( 'jquery' ),
			1.0,
			true
		);

		wp_register_style(
			'vms-search-box-css',
			cdp_get_css_source().'widgets/vms-search-box-widget.css',
			false,
			1.0
		);
		
		$script = admin_url('admin-ajax.php');
		wp_localize_script( 'vms-search-box-js', 'ajax_path', $script );

		if( is_admin() && $pagenow == 'widgets.php' ) {
			add_action( 'admin_enqueue_scripts', array( &$this , 'vms_enqueue_color_picker' ) );
			wp_enqueue_style('vms-search-box-css');
			wp_enqueue_script( 'vms-search-box-js' );
		}

	}

	function widget($arge, $instance) {

		extract( $arge );
		
		$site_url = site_url();

		wp_enqueue_style('vms-search-box-css');
		wp_enqueue_script('vms-search-box-js');

		// Widget options
		$title = apply_filters('widget_title', $instance['title']);
		$trims = $instance['show_trims'];
		$text = $instance['show_text_search'];
		$hide_labels = $instance['hide_labels'];
		$display = $instance['salesclass_display'];
		$default = $instance['salesclass_default'];
		$colors = $instance['custom_colors'];

		//Get Custom Color Styles
		$title_style = (isset($colors['title']['text']) ) ? ' color: ' . $colors['title']['text'] . ';': '';
		$title_style .= (isset($colors['title']['bg']) ) ? ' background-color: ' . $colors['title']['bg'] . ';' : '';
		$widget_style = (isset($colors['widget']['bg']) ) ? ' background-color: ' . $colors['widget']['bg'] . ';' : '';
		$label_style = (isset($colors['label']['text']) ) ? ' color: ' . $colors['label']['text'] . ';': '';
		$select_style = (isset($colors['select']['text']) ) ? ' color: ' . $colors['select']['text'] . ';': '';
		$select_style .= (isset($colors['select']['bg']) ) ? ' background-color: ' . $colors['select']['bg'] . ';': '';
		$input_style = (isset($colors['input']['text']) ) ? ' color: ' . $colors['input']['text'] . ';': '';
		$input_style .= (isset($colors['input']['bg']) ) ? ' background-color: ' . $colors['input']['bg'] . ';': '';
		$save_style = (isset($colors['save']['text']) ) ? ' color: ' . $colors['save']['text'] . ';': '';
		$save_style .= (isset($colors['save']['bg']) ) ? ' background-color: ' . $colors['save']['bg'] . ';' : '';

		$salesclass = ( $display == 'both' ? $default : $display );
		$class_center = empty($hide_labels) ? '' : 'center';

		$widget_content = '';

		$widget_content = $before_widget;

		$widget_content .= '<div class="widget-vms-search-box" style="'.$widget_style.'">';

		//Display Title
		$widget_content .= $title ? '<div class="widget-vms-search-title" style="'.$title_style.'">' . $title . '</div>' : '';

		//Display Salesclass/Condition
		$widget_content .= '<div class="widget-vms-select-wrapper saleclass '.$class_center.'">';
		$widget_content .= empty($hide_labels) ? '<label class="widget-vms-label" style="'.$label_style.'">Condition: </label>' : '';
		$widget_content .= '<select class="widget-vms-select-saleclass vms-select" name="saleclass" onchange="cdp_widget_select_caller(this, \'saleclass\');">';
		if( $display == 'both' ){
			$widget_content .= '<option value="New" '. ($default == 'new' ? 'selected': '') .'>New</option>';
			$widget_content .= '<option value="Used" '. ($default == 'used' ? 'selected': '') .'>Used</option>';
		} else {
			$widget_content .= '<option value="'. ucfirst($display) .'" selected >'. ucfirst($display) .'</option>';
		}
		$widget_content .= '</select>';
		$widget_content .= '</div>';

		//Makes
		$widget_content .= '<div class="widget-vms-select-wrapper makes '.$class_center.'">';
		$widget_content .= empty($hide_labels) ? '<label class="widget-vms-label" style="'.$label_style.'">Makes: </label>' : '';
		$widget_content .= '<select class="widget-vms-select-make vms-select" name="make" onchange="cdp_widget_select_caller(this, \'make\');">';
		$makes = cdp_generate_make_options( $this->widget_cdp_vms, $salesclass, $this->widget_cdp_options['vehicle_management_system']['data']['makes_new'], FALSE );
		$widget_content .= isset( $makes['display'] ) ? $makes['display'] : '';
		$widget_content .= '</select></div>';
		
		//Models
		$widget_content .= '<div class="widget-vms-select-wrapper models '.$class_center.'">';
		$widget_content .= empty($hide_labels) ? '<label class="widget-vms-label" style="'.$label_style.'">Models: </label>' : '';
		$widget_content .= '<select class="widget-vms-select-model vms-select" name="model" onchange="cdp_widget_select_caller(this, \'model\');">';
		$models = cdp_generate_model_options( $this->widget_cdp_vms, $salesclass, $makes['val'], FALSE );
		$widget_content .= isset( $models['display'] ) ? $models['display'] : '';
		$widget_content .= '</select></div>';

		//Display Trims
		if( !empty($trims) ){
			//Trims
			$widget_content .= '<div class="widget-vms-select-wrapper trims '.$class_center.'">';
			$widget_content .= empty($hide_labels) ? '<label class="widget-vms-label" style="'.$label_style.'">Trims: </label>' : '';
			$widget_content .= '<select class="widget-vms-select-trim vms-select" name="trim" onchange="cdp_widget_select_caller(this, \'trim\');">';
			$trims = cdp_generate_trim_options( $this->widget_cdp_vms, $salesclass, $makes['val'], $models['val'], FALSE );
			$widget_content .= isset( $trims['display'] ) ? $trims['display'] : '';
			$widget_content .= '</select></div>';
		}

		//Display Text Search
		if( !empty($text) ){
			$widget_content .= '<div class="widget-vms-text-wrapper '.$class_center.'">';
			$widget_content .= empty($hide_labels) ? '<label class="vms-sb-text-label" style="'.$label_style.'">Text Search: </label>' : '';
			$widget_content .= '<input type="text" class="widget-vms-text-input" value="" style="'.$input_style.'" />';
			$widget_content .= '</div>';

		}

		//Search Button
		$widget_content .= '<div alt="'.$site_url.'" class="widget-vms-search-button" style="'.$save_style.'">SEARCH</div>';
		$widget_content .= '</div>';
		$widget_content .= '<div style="display: none;" class="widget-vms-preset-load">';
		$widget_content .= '<div key="saleclass">'.$salesclass.'</div>';
		$widget_content .= $makes['val'] ? '<div key="make">'.$makes['val'].'</div>' : '';
		$widget_content .= $models['val'] ? '<div key="model">'.$models['val'].'</div>' : '';
		$widget_content .= $trims['val'] ? '<div key="trim">'.$trims['val'].'</div>': '';
		$widget_content .= '</div>';
		$widget_content .= $after_widget;
		echo $widget_content;
	}


    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = isset( $new_instance['title'] ) ? strip_tags($new_instance['title']) : NULL;
		$instance['salesclass_display'] = isset( $new_instance['salesclass_display'] ) ? $new_instance['salesclass_display'] : 'both';
		$instance['salesclass_default'] = isset( $new_instance['salesclass_default'] ) ? $new_instance['salesclass_default'] : 'new';
		$instance['show_trims'] = isset( $new_instance['show_trims'] ) ? $new_instance['show_trims'] : 0;
		$instance['show_text_search'] = isset( $new_instance['show_text_search'] ) ? $new_instance['show_text_search'] : 0;
		$instance['custom_colors'] = isset( $new_instance['custom_colors'] ) ? $new_instance['custom_colors'] : array();
		$instance['hide_labels'] = isset( $new_instance['hide_labels'] ) ? $new_instance['hide_labels'] : 0;

	    return $instance;
    }


	function form($instance) {

	    $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$display = isset($instance['salesclass_display']) ? $instance['salesclass_display'] : '';
		$default = isset($instance['salesclass_default']) ? $instance['salesclass_default'] : '';
		$trims = isset($instance['show_trims']) ? $instance['show_trims'] : '';
		$text = isset($instance['show_text_search']) ? $instance['show_text_search'] : '';
		$colors = isset($instance['custom_colors']) ? $instance['custom_colors'] : '';
		$labels = isset($instance['hide_labels']) ? $instance['hide_labels'] : '';
    	?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Search Box Title'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<hr class="div" />
		<br>

		<p>
		<label for="<?php echo $this->get_field_id( 'salesclass_display' ); ?>"><?php _e( 'Sale Class Display:' ); ?></label>
		<select id="<?php echo $this->get_field_id( 'salesclass_display' ); ?>" name="<?php echo $this->get_field_name( 'salesclass_display' ); ?>">
		<?php
			$layout_options = array( 'both' , 'new' , 'used' );
			foreach( $layout_options as $layout_possibility ) {
				$selected = $display == $layout_possibility ? 'selected' : NULL;
				echo '<option value="' . $layout_possibility . '" ' . $selected . '>' . ucfirst( $layout_possibility ) . '</option>';

				( $display == 'both' ) ? $class = 'active' : $class = '';
			}
		?>
		</select>
		</p>

		<p class="saleclass-default <?php echo $class; ?>">
		<label for="<?php echo $this->get_field_id( 'salesclass_default' ); ?>"><?php _e( 'Sale Class Default:' ); ?></label>
		<select id="<?php echo $this->get_field_id( 'salesclass_default' ); ?>" name="<?php echo $this->get_field_name( 'salesclass_default' ); ?>">
		<?php
			$layout_options = array( 'new' , 'used' );
			foreach( $layout_options as $layout_possibility ) {
				$selected = $default == $layout_possibility ? 'selected' : NULL;
				echo '<option value="' . $layout_possibility . '" ' . $selected . '>' . ucfirst( $layout_possibility ) . '</option>';
			}
		?>
		</select>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'show_trims' ); ?>"><?php _e( 'Show Trims:' ); ?></label>
		<?php $checked = ( $trims == true ) ? 'checked="checked"' : NULL; ?>
		<input id="<?php echo $this->get_field_id( 'show_trims' ); ?>" name="<?php echo $this->get_field_name( 'show_trims' ); ?>" type="checkbox" <?php echo $checked; ?>	value="true" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'show_text_search' ); ?>"><?php _e( 'Show Text Search:' ); ?></label>
		<?php $checked = ( $text == true ) ? 'checked="checked"' : NULL; ?>
		<input id="<?php echo $this->get_field_id( 'show_text_search' ); ?>" name="<?php echo $this->get_field_name( 'show_text_search' ); ?>" type="checkbox" <?php echo $checked; ?>	value="true" />
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'hide_labels' ); ?>"><?php _e( 'Hide Labels:' ); ?></label>
		<?php $labels = ( $labels == true ) ? 'checked="checked"' : NULL; ?>
		<input id="<?php echo $this->get_field_id( 'hide_labels' ); ?>" name="<?php echo $this->get_field_name( 'hide_labels' ); ?>" type="checkbox" <?php echo $labels; ?>	value="true" />
		</p>

		<hr class="div" />
		<div class="vms-cc-header"><?php _e( 'Custom Colors'); ?></div>
		<div class="vms-cc-wrap">
		<label class="vms-cc-label" for="<?php echo $this->get_field_id( 'custom_colors' ) . '[title][text]'; ?>"><?php _e( 'Title Text:'); ?></label>
		<input alt="off" id="<?php echo $this->get_field_id( 'custom_colors' ) . '-title-text'; ?>" name="<?php echo $this->get_field_name( 'custom_colors' ) . '[title][text]'; ?>" type="text" class="vms-color-picker vms-cc-input" value="<?php echo isset($colors['title']['text'] ) ? $colors['title']['text'] : '' ;?>" />
		</div>
		<div class="vms-cc-wrap">
		<label class="vms-cc-label" for="<?php echo $this->get_field_id( 'custom_colors' ) . '[title][bg]'; ?>"><?php _e( 'Title Background:'); ?></label>
		<input alt="off" id="<?php echo $this->get_field_id( 'custom_colors' ) . '-title-bg'; ?>" name="<?php echo $this->get_field_name( 'custom_colors' ) . '[title][bg]'; ?>" type="text" class="vms-color-picker vms-cc-input" value="<?php echo isset($colors['title']['bg'] ) ? $colors['title']['bg'] : '' ;?>" />
		</div>
		<div class="vms-cc-wrap">
		<label class="vms-cc-label" for="<?php echo $this->get_field_id( 'custom_colors' ) . '[widget][bg]'; ?>"><?php _e( 'Widget Background:'); ?></label>
		<input alt="off" id="<?php echo $this->get_field_id( 'custom_colors' ) . '-widget-bg'; ?>" name="<?php echo $this->get_field_name( 'custom_colors' ) . '[widget][bg]'; ?>" type="text" class="vms-color-picker vms-cc-input" value="<?php echo isset($colors['widget']['bg'] ) ? $colors['widget']['bg'] : '' ;?>" />
		</div>
		<div class="vms-cc-wrap">
		<label class="vms-cc-label" for="<?php echo $this->get_field_id( 'custom_colors' ) . '[label][text]'; ?>"><?php _e( 'Label Text:'); ?></label>
		<input alt="off" id="<?php echo $this->get_field_id( 'custom_colors' ) . '-label-text'; ?>" name="<?php echo $this->get_field_name( 'custom_colors' ) . '[label][text]'; ?>" type="text" class="vms-color-picker vms-cc-input" value="<?php echo isset($colors['label']['text'] ) ? $colors['label']['text'] : '' ;?>" />
		</div>
		<div class="vms-cc-wrap">
		<label class="vms-cc-label" for="<?php echo $this->get_field_id( 'custom_colors' ) . '[select][text]'; ?>"><?php _e( 'Select Text:'); ?></label>
		<input alt="off" id="<?php echo $this->get_field_id( 'custom_colors' ) . '-select-text'; ?>" name="<?php echo $this->get_field_name( 'custom_colors' ) . '[select][text]'; ?>" type="text" class="vms-color-picker vms-cc-input" value="<?php echo isset($colors['select']['text'] ) ? $colors['select']['text'] : '' ;?>" />
		</div>
		<div class="vms-cc-wrap">
		<label class="vms-cc-label" for="<?php echo $this->get_field_id( 'custom_colors' ) . '[select][bg]'; ?>"><?php _e( 'Select Background:'); ?></label>
		<input alt="off" id="<?php echo $this->get_field_id( 'custom_colors' ) . '-select-bg'; ?>" name="<?php echo $this->get_field_name( 'custom_colors' ) . '[select][bg]'; ?>" type="text" class="vms-color-picker vms-cc-input" value="<?php echo isset($colors['select']['bg'] ) ? $colors['select']['bg'] : '' ;?>" />
		</div>
		<div class="vms-cc-wrap">
		<label class="vms-cc-label" for="<?php echo $this->get_field_id( 'custom_colors' ) . '[input][text]'; ?>"><?php _e( 'Input Text:'); ?></label>
		<input alt="off" id="<?php echo $this->get_field_id( 'custom_colors' ) . '-input-text'; ?>" name="<?php echo $this->get_field_name( 'custom_colors' ) . '[input][text]'; ?>" type="text" class="vms-color-picker vms-cc-input" value="<?php echo isset($colors['input']['text'] ) ? $colors['input']['text'] : '' ;?>" />
		</div>
		<div class="vms-cc-wrap">
		<label class="vms-cc-label" for="<?php echo $this->get_field_id( 'custom_colors' ) . '[input][bg]'; ?>"><?php _e( 'Input Background:'); ?></label>
		<input alt="off" id="<?php echo $this->get_field_id( 'custom_colors' ) . '-input-bg'; ?>" name="<?php echo $this->get_field_name( 'custom_colors' ) . '[input][bg]'; ?>" type="text" class="vms-color-picker vms-cc-input" value="<?php echo isset($colors['input']['bg'] ) ? $colors['input']['bg'] : '' ;?>" />
		</div>
		<div class="vms-cc-wrap">
		<label class="vms-cc-label" for="<?php echo $this->get_field_id( 'custom_colors' ) . '[save][text]'; ?>"><?php _e( 'Save Text:'); ?></label>
		<input alt="off" id="<?php echo $this->get_field_id( 'custom_colors' ) . '-save-text'; ?>" name="<?php echo $this->get_field_name( 'custom_colors' ) . '[save][text]'; ?>" type="text" class="vms-color-picker vms-cc-input" value="<?php echo isset($colors['save']['text'] ) ? $colors['save']['text'] : '' ;?>" />
		</div>
		<div class="vms-cc-wrap">
		<label class="vms-cc-label" for="<?php echo $this->get_field_id( 'custom_colors' ) . '[save][bg]'; ?>"><?php _e( 'Save Background:'); ?></label>
		<input alt="off" id="<?php echo $this->get_field_id( 'custom_colors' ) . '-save-bg'; ?>" name="<?php echo $this->get_field_name( 'custom_colors' ) . '[save][bg]'; ?>" type="text" class="vms-color-picker vms-cc-input" value="<?php echo isset($colors['save']['bg'] ) ? $colors['save']['bg'] : '' ;?>" />
		</div>
		<?php
	}


	function vms_enqueue_color_picker( $hook_suffix ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'iris',
            admin_url( 'js/iris.min.js' ),
            array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
            false,
            1
		);
	}


}


?>
