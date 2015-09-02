<?php

namespace Wordpress\Plugins\CarDealerPress\Inventory\Api;

require_once( dirname( __FILE__ ) . '/application/helpers/http_request.php' );
require_once( dirname( __FILE__ ) . '/application/helpers/vehicle_management_system.php' );
require_once( dirname( __FILE__ ) . '/application/helpers/vehicle_reference_system.php' );
require_once( dirname( __FILE__ ) . '/application/helpers/dynamic_site_headers.php' );

require_once( dirname( __FILE__ ) . '/application/views/widgets/vms_search_box.php' );
require_once( dirname( __FILE__ ) . '/application/views/shortcode/sc_index.php' );

require_once( dirname( __FILE__ ) . '/application/functions/fn_inventory.php' );
require_once( dirname( __FILE__ ) . '/application/functions/fn_views.php' );

class cdp_plugin {

	public $options = array(
		'vehicle_management_system' => array(
			'company_information' => array(
				'id' => 0
			),
			'host' => 'http://api.dealertrend.com',
			'theme' => array(
				'name' => 'armadillo',
				'per_page' => 10,
				'loan' => array(
					'display_calc' => '',
					'default_interest' => '7.8',
					'default_trade' => '5000',
					'default_term' => '72',
					'default_down' => '3000',
					'default_tax' => '8.0',
					'display_monthly' => '',
					'display_bi_monthly' => '',
					'display_total_cost' => ''
				),
				'forms' => array(),
				'emails' => array(
					'defaults' => array(
						'new' => '',
						'used'=> ''
					),
					'dealers' => array()
				),
				'price_text' => array(
					'new' => array('standard_price' => '', 'compare_price' => '', 'sale_price' => '', 'default_price' =>  ''),
					'used' => array('standard_price' => '', 'compare_price' => '', 'sale_price' => '', 'default_price' =>  '')
				),
				'show_standard_eq' => 0,
				'style' => array(
					'flex' => array(
						'toggle' => '',
						'settings' => array(
							'containers' => array(
								'one' => array( 'order' => 1, 'max-width' => 100),
								'two' => array( 'order' => 1, 'max-width' => 100),
								'three' => array( 'order' => 1, 'max-width' => 100),
								'four' => array( 'order' => 1, 'max-width' => 100),
								'five' => array( 'order' => 1, 'max-width' => 100),
								'six' => array( 'order' => 1, 'max-width' => 100)
							)
						)
					)
				),
				'add_geo_zip' => '',
				'display_geo' => '',
				'display_tags' => '',
				'display_similar' => '',
				'default_image_tab' => '',
				'default_info_tab' => '',
				'list_info_button' => '',
				'list_form_button' => '',
				'list_gform_id' => '',
				'detail_gform_id' => '',
				'hide_certified_saleclass' => ''
			),
			'saleclass' => 'all',
			'data' => array(
				'makes_new' => array(),
				'default_no_image' => ''
			),
			'custom_contact' => array(
				'phone' => array('new'=>'','used'=>''),
				'contact_name' => array('new'=>'','used'=>''),
				'breadcrumb' => ''
			),
			'tags' => array(
				'data' => array()
			),
			'scripts' => array(
				'data' => array()
			),
			'styles' => array(
				'data' => array()
			),
			'keywords' => array( 'enable'=>'','add'=>'', 'exclude'=>'', 'pot'=>'' )
		),
		'vehicle_reference_system' => array(
			'host' => 'http://vrs.dealertrend.com'
		),
		'alt_settings' => array(
			'discourage_seo_visibility' => '',
			'debug_plugin_info' => ''
		)
	);

	public static $plugin_information = array();
	public $parameters = array();
	public $taxonomy = null;
	public $vms;
	public $vrs;
	public $company;
	public $seo_headers;
	public $plugin_slug = '';
	
	function __construct() {
		$this->plugin_slug = plugin_basename(__FILE__);
		$this->execute();
	}

	function execute() {
		$this->load_plugin_information();
		$this->load_options();
		$this->send_additional_header_data();
		$this->set_variables();
		$this->load_widgets();
		$this->load_admin();
		$this->setup_routing();
		$this->register_front_ajax();
		$this->queue_templates();
		$this->add_filter_hooks();
		$this->add_menu_link();
		$this->add_shortcode();
		$this->wp_header_add();
	}

	/*
		//// LOAD PLUGIN INFOFORMATION
	*/
	function load_plugin_information() {

		$data = array();

		$file_headers = array (
			'Name' => 'Plugin Name',
			'PluginURI' => 'Plugin URI',
			'Version' => 'Version',
			'Description' => 'Description',
			'Author' => 'Author',
			'AuthorURI' => 'Author URI'
		);

		$data = get_file_data( $this->get_master_file() , $file_headers , 'plugin' );
		
		$plugin_file = pathinfo( $this->get_master_file() );
		$data[ 'PluginURL' ] = plugins_url( '' , $this->get_master_file() );
		$data[ 'PluginBaseName' ] = $this->get_plugin_basename();
		$data[ 'PluginDir' ] = dirname( __FILE__ );
		self::$plugin_information = $data;
	}

	private function get_master_file() {
		return dirname( __FILE__ ) . '/cardealerpress.php';
	}

	private function get_plugin_basename() {
		return plugin_basename( $this->get_master_file() );
	}

	/*
		//// LOAD OPTIONS
	*/
	function load_options() {
		$loaded_options = get_option( 'cardealerpress_settings' ) ;
		if( !$loaded_options ) {
			update_option( 'cardealerpress_settings' , $this->options );
		} else {
			if( $this->validate_options( $loaded_options , $this->options ) ) {
				update_option( 'cardealerpress_settings' , $loaded_options );
			}
			foreach( $loaded_options as $option_group => $option_values ) {
				$this->options[ $option_group ] = $option_values;
			}
		}
	}

	function validate_options( &$options , &$defaults , &$modified = false ) {
		foreach( $defaults as $key => $value ) {
			if( is_array( $value ) ) {
				$this->validate_options( $options[ $key ] , $value , $modified );
			} elseif( !isset( $options[ $key ] ) || $options[ $key ] == NULL ) {
				$options[ $key ] = $defaults[ $key ];
				$modified = true;
			}
		}

		return $modified;
	}
	
	/*
		//// Send Headers
	*/
	function send_additional_header_data(){
		add_action( 'send_headers', array($this, 'additional_headers') );
	}
	
	function additional_headers() {
		if( !is_admin() ){
			global $wp_version;
			header( 'X-WP-Version: '.$wp_version );
			header( 'X-CDP-Version: '.self::$plugin_information['Version'] );
			if( isset($this->options['vehicle_management_system']['company_information']['id']) ){
				header( 'X-CDP-Company-ID: '.$this->options['vehicle_management_system']['company_information']['id'] );
			}
			if( isset($this->options['vehicle_management_system']['theme']['name']) ){
				header( 'X-CDP-Theme-Name: '.$this->options['vehicle_management_system']['theme']['name'] );
			}
		}
	}

	/*
		//// SET VARIABLES
	*/
	function set_variables(){
		
		if( empty($this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]) ){
			add_action( 'admin_notices', array($this, 'display_admin_register_notice') );
		} else {
			if( $this->options[ 'vehicle_management_system' ][ 'host' ] && $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ] ){
				$this->vms = new vehicle_management_system(
					$this->options[ 'vehicle_management_system' ][ 'host' ],
					$this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ]
				);
			}
			
			if( !empty($this->vms) ){
				$company_raw = $this->vms->get_company_information()->please();
				$this->company = isset($company_raw['body']) ? json_decode($company_raw['body']) : array();
			}
		
			if( !empty($this->vms) && !empty($this->company) ){
				$seo_hack = array( 'city' => $this->company->seo->city , 'state' => $this->company->seo->state , 'country_code' => $this->company->country_code );
				$this->seo_headers = new dynamic_site_headers(
					$this->options[ 'vehicle_management_system' ][ 'host' ],
					$this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ],
					(array) $this->parameters + (array) $seo_hack,
					$this->options[ 'alt_settings' ][ 'discourage_seo_visibility' ]
				);
			}
		
			if( $this->options[ 'vehicle_reference_system' ][ 'host' ] && !empty($this->company) ){
				$this->vrs = new vehicle_reference_system (
					$this->options[ 'vehicle_reference_system' ][ 'host' ],
					$this->company->country_code
				);
			}
		}
	}
	
	function display_admin_register_notice(){
		$notice = '<div class="error"><p>CarDealerPress requires a Company ID prior to use. <a href="http://cardealerpress.com">Purchase Subscription</a> or <a href="'.get_admin_url().'admin.php?page=cardealerpress_admin">Add</a> your Company ID.</p></div>';
		echo $notice;
	}
	
	/*
		//// LOAD WIDGETS
	*/
	function load_widgets() {
		if( $this->options[ 'vehicle_management_system' ][ 'host' ] && $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ] ) {
			add_action( 'widgets_init' , create_function( '' , 'return register_widget( "vms_search_box_widget" );' ) );
		}
	}
	
	/*
		//// LOAD ADMIN
	*/
	function load_admin() {
		if( is_admin() ){
			include( dirname( __FILE__ ) . '/application/views/options/page.php' );
			$this->admin = new cdp_admin($this->options, $this->company, $this->vms, $this->vrs, self::$plugin_information['PluginDir'] );
		}
	}

	/*
		//// SETUP ROUTING
	*/
	function setup_routing() {
		add_action( 'rewrite_rules_array' , array( &$this , 'add_rewrite_rules' ) , 1 );
		add_action( 'init' , array( &$this , 'create_taxonomies' ) );
	}
	
	function add_rewrite_rules( $existing_rules ) {
		$new_rules = array();
		if( $this->options[ 'vehicle_management_system' ][ 'host' ] && $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ] ) {
			$new_rules[ '^(inventory)' ] = 'index.php?taxonomy=inventory';
			$new_rules[ '^(new-vehicle-sitemap\.xml$)' ] = 'index.php?taxonomy=new-vehicle-sitemap';
			$new_rules[ '^(used-vehicle-sitemap\.xml$)' ] = 'index.php?taxonomy=used-vehicle-sitemap';
		}

		return $new_rules + $existing_rules;
	}
	
	function create_taxonomies() {
		if( $this->options[ 'vehicle_management_system' ][ 'host' ] && $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ] ) {
			add_filter( 'widget_text' , 'do_shortcode' );
			register_sidebar(array(
				'name' => 'Inventory Vehicle Detail Page',
				'id' => 'vehicle-detail-page',
				'description' => 'Widgets in this area will show up on the Vehicle Detail Page.',
				'before_title' => '<h1>',
				'after_title' => '</h1>',
				'before_widget' => '<div id="%1$s" class="inventory widget %2$s">',
				'after_widget' => '</div>'
			));
			register_sidebar(array(
				'name' => 'Inventory Vehicle List Page',
				'id' => 'vehicle-listing-page',
				'description' => 'Widgets in this area will show up on the Vehicle List Page.',
				'before_title' => '<h1>',
				'after_title' => '</h1>',
				'before_widget' => '<div id="%1$s" class="inventory-list widget %2$s">',
				'after_widget' => '</div>'
			));
			$labels = array(
				'name' => _x( 'Inventory' , 'taxonomy general name' )
			);
			register_taxonomy(
				'inventory',
				array( 'page' ),
				array(
					'hierarchical' => false,
					'labels' => $labels,
					'show_ui' => false,
					'query_var' => true,
					'rewrite' => array( 'slug' => 'inventory' ),
					'show_in_nav_menus' => false,
					'show_tagcloud' => false
				)
			);
			if( !empty($this->seo_headers->headers) ){
				$saleclass = $this->options[ 'vehicle_management_system' ][ 'saleclass' ];

				if( $saleclass == 'all' || $saleclass == 'new' ){
					$labels = array(
						'name' => _x( 'New Inventory Sitemap' , 'taxonomy general name' )
					);
					register_taxonomy(
						'new-vehicle-sitemap',
						array( 'page' ),
						array(
							'hierarchical' => false,
							'labels' => $labels,
							'show_ui' => false,
							'query_var' => true,
							'rewrite' => array( 'slug' => 'new-vehicle-sitemap' ),
							'show_in_nav_menus' => false,
							'show_tagcloud' => false
						)
					);				
				}

				if( $saleclass == 'all' || $saleclass == 'used' || $saleclass == 'certified' ){
					$labels = array(
						'name' => _x( 'Used Inventory Sitemap' , 'taxonomy general name' )
					);
					register_taxonomy(
						'used-vehicle-sitemap',
						array( 'page' ),
						array(
							'hierarchical' => false,
							'labels' => $labels,
							'show_ui' => false,
							'query_var' => true,
							'rewrite' => array( 'slug' => 'used-vehicle-sitemap' ),
							'show_in_nav_menus' => false,
							'show_tagcloud' => false
						)
					);
				}

			}
		}
	}

	function queue_templates() {
		add_action( 'template_redirect' , array( &$this , 'show_theme' ) , 100 );
	}

	function show_theme() {
		global $wp_query;
		$this->taxonomy = ( isset( $wp_query->query_vars[ 'taxonomy' ] ) ) ? $wp_query->query_vars[ 'taxonomy' ] : NULL;
		//wp_enqueue_script( 'dealertrend_inventory_api_traffic_source' );
		if( $this->vms ){
			switch( $this->taxonomy ) {
				case 'inventory':
					if( $this->options[ 'vehicle_management_system' ][ 'host' ] ) {
					
						$this->parameters = $this->get_parameters();
						$this->fix_bad_wordpress_assumption();

						$current_theme = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'name' ];					
						$theme_path = dirname( __FILE__ ) . '/application/views/inventory/' . $current_theme;
						$theme_path = apply_filters('cdp_theme_path', $theme_path);

						$status = $this->vms->set_headers( $this->parameters );
					
						if( $this->autocheck_flag ){
							include_once( dirname( __FILE__ ) . '/application/views/inventory/autocheck.php' );
						} else if( $this->print_page){
							include_once( dirname( __FILE__ ) . '/application/views/inventory/print.php' );
						} else {
							if( $handle = opendir( dirname( __FILE__ ) . '/application/views/inventory' ) ) {
								while( false != ( $file = readdir( $handle ) ) ) {
									if( $file == 'inv_index.php' ) {
										include_once( dirname( __FILE__ ) . '/application/views/inventory/inv_index.php' );
									}
								}
								closedir( $handle );
							} else {
								echo __FUNCTION__ . ' Could not open directory at: ' . $theme_path;
								return false;
							}
						}

						$this->stop_wordpress();
					}
				break;
				case 'new-vehicle-sitemap':
				case 'used-vehicle-sitemap':
				if ( !empty($this->seo_headers->headers) ){
				
					$company_id = $this->options[ 'vehicle_management_system' ][ 'company_information' ][ 'id' ];

					$sitemap_request = 'http://api.dealertrend.com/api/companies/' . $company_id . '/vehicles.json';
					$sitemap_handler = new http_request( $sitemap_request , 'vehicle_sitemap' );

					$theme_path = dirname( __FILE__ ) . '/application/views/sitemap';

					if( $handle = opendir( $theme_path ) ) {
						while( false != ( $file = readdir( $handle ) ) ) {
							if( $file == 'index.php' ) {
								include_once( $theme_path . '/index.php' );
							}
						}
						closedir( $handle );
					} else {
						echo __FUNCTION__ . ' Could not open directory at: ' . $theme_path;
						return false;
					}
					$this->stop_wordpress();
				
				} else {
					echo 'Page not Available.';
					$this->stop_wordpress();
				}

				break;
			} 
		} else {
			$this->load_vms_error();
			$this->stop_wordpress();
		}
	}
	
	function register_front_ajax(){
		require_once( dirname( __FILE__ ) . '/application/functions/fn_front_ajax.php' );
		$front_ajax = new cdp_front_ajax($this->options,$this->vms,$this->vrs,$this->company);
		add_action('wp_ajax_cdp_front_ajax_request', array(&$front_ajax, 'cdp_front_handle_request') );
		add_action('wp_ajax_nopriv_cdp_front_ajax_request', array(&$front_ajax, 'cdp_front_handle_request') );
	}
	
	function load_vms_error(){
		get_header();
		$this->display_admin_register_notice();
		get_footer();
	}

	function stop_wordpress() {
		exit;
	}

	function fix_bad_wordpress_assumption() {
		global $wp_query;
		$wp_query->is_home = false;
	}
	
	function get_parameters() {
		global $wp;
		global $wp_rewrite;

		$permalink_parameters = !empty( $wp_rewrite->permalink_structure ) ? explode( '/' , $wp->request ) : array();
		$server_parameters = isset( $_GET ) ? array_map( array( &$this , 'sanitize_inputs' ) , $_GET ) : NULL;
		$parameters = array();
		$this->autocheck_flag = false;
		$this->print_page = false;

		switch( $this->taxonomy ) {
			case 'inventory';
				$server_parameters[ 'per_page' ] = $this->options[ 'vehicle_management_system' ][ 'theme' ][ 'per_page' ];

				switch( $this->options[ 'vehicle_management_system' ][ 'saleclass' ] ) {
					case 'new':
						$server_parameters[ 'saleclass' ] = 'New';
						break;
					case 'used':
						$server_parameters[ 'saleclass' ] = 'Used';
						break;
					case 'certified':
						$server_parameters[ 'saleclass' ] = 'Used';
						$server_parameters[ 'certified' ] = 'yes';
						break;
				}

				if( isset( $permalink_parameters[1] ) && $permalink_parameters[1] == 'autocheck' ){
					$this->autocheck_flag = true;
					$this->autocheck_vin = ( isset($permalink_parameters[2]) ) ? $permalink_parameters[2] : 0 ;
				} else {
					foreach( $permalink_parameters as $key => $value ) {
						switch( $key ) {
							case 0: $index = 'taxonomy'; break;
							case 1:
								if( is_numeric( $value ) ) {
									$index = 'year';
								} else {
									$index = 'saleclass';
								}
							break;
							case 2: $index = 'make'; break;
							case 3: $index = 'model'; break;
							case 4: $index = 'state'; break;
							case 5: $index = 'city'; break;
							case 6: $index = 'vin'; break;
							default: return; break;
						}
						$parameters[ $index ] = $value;
					}

					if( isset( $server_parameters['print_page'] ) ){
						$this->print_page = true;
					}
				}
			break;
		}
		return array_merge( $parameters , $server_parameters );
	}
	
	


	function flush_rewrite_rules( $override = false ) {
		if( $override === false ) {
			$pagenow = $_SERVER['SCRIPT_NAME'];
			if ( is_admin() && isset($_GET['activate'] ) && ( $pagenow == "/wp-admin/plugins.php" || $pagenow == "/wp-admin/network/plugins.php" ) ) {
				global $wp_rewrite;
				return $wp_rewrite->flush_rules();
			}
		} else {
				global $wp_rewrite;
				return $wp_rewrite->flush_rules();
		}
	}

	function sanitize_inputs( $input ) {
		if( is_array( $input ) ) {
			foreach( $input as $key => $value ) {
				$input[ $key ] = is_scalar( $value ) ? wp_kses_data( $value , false , 'http' ) : array_map( array( &$this , 'sanitize_inputs' ) , $value );
			}
		} else {
			$input = wp_kses_data( $input , false , 'http' );
		}

		return $input;
	}

	static function get_themes( $type ) {
		$directories = scandir( dirname( __FILE__ ) . '/application/views/' . $type . '/' );
		$ignore = array( '.' , '..' );
		$exclude = array( 'php' );
		foreach( $directories as $key => $value ) {
			$ext = pathinfo($value, PATHINFO_EXTENSION);
			if( in_array( $ext, $exclude ) ){
				unset( $directories[ $key ] );
			}
			if( in_array( $value , $ignore ) ){
				unset( $directories[ $key ] );
			}
		}

		return array_values( $directories );
	}

	function add_filter_hooks() {
		/**
		 * Filter to add custom link to index sitemap for wpseo by yoast
		 **/
		add_filter('wpseo_sitemap_index', array( &$this, 'add_custom_sitemap_link' ) );
		add_filter( 'redirect_canonical', array( &$this, 'stop_canonical' ) );
	}

	function add_custom_sitemap_link () {
		/**
		 * Filter to add custom link to index sitemap for wpseo by yoast
		 *
		 * @return string $custom_link
		 **/
		$date_raw = date("Y-m-d");
		$last_mod = date("Y-m-d", strtotime('-1 day', strtotime($date_raw)));
		
		$saleclass = $this->options[ 'vehicle_management_system' ][ 'saleclass' ];
		
		$custom_link = '';
		if( $saleclass == 'all' || $saleclass == 'new' ){
			$custom_link .= '<sitemap>' . "\n";
			$custom_link .= '<loc>' . home_url('new-vehicle-sitemap.xml') . '</loc>' . "\n";
			$custom_link .= '<lastmod>' . $last_mod . ' 20:00' . '</lastmod>' . "\n";
			$custom_link .= '</sitemap>' . "\n";
		}


		if( $saleclass == 'all' || $saleclass == 'used' || $saleclass == 'certified' ){
			$custom_link .= '<sitemap>' . "\n";
			$custom_link .= '<loc>' . home_url('used-vehicle-sitemap.xml') . '</loc>' . "\n";
			$custom_link .= '<lastmod>' . $last_mod . ' 20:00' . '</lastmod>' . "\n";
			$custom_link .= '</sitemap>' . "\n";		
		}

		return $custom_link;

	}

	function stop_canonical( $redirect ) {
		$sitemap = get_query_var( 'taxonomy' );
		if ( !empty( $sitemap ) && ( $sitemap == 'new-vehicle-sitemap' || $sitemap == 'used-vehicle-sitemap' ) ){
			return false;
		}
		return $redirect;
	}

	function add_menu_link() {
		// Adds a link to the admin bar for VMS
		add_action( 'wp_before_admin_bar_render', array( $this, "add_vms_link" ) );
	}

	function add_vms_link() {
		global $wp_admin_bar;
		if ( !is_super_admin() || !is_admin_bar_showing() )
			return;

		$wp_admin_bar->add_menu( array(
			'id'   => 'vms_link',
			'meta' => array( 'target' => '_blank'),
			'title' => 'Manage Inventory',
			'href' => 'http://manager.dealertrend.com'
			)
		);
	}

	function add_shortcode() {
		$sc_run = new sc_run( $this->vms, $this->vrs, $this->company, $this->options, self::$plugin_information[ 'PluginURL' ] );
		add_shortcode( 'inventory_list', array( $sc_run, 'inventory_list' ) );
		add_shortcode( 'inventory_detail', array( $sc_run, 'inventory_detail' ) );
		//add_shortcode( 'vrs_slider', array( $sc_run, 'vrs_slider' ) );
	}
	
	function wp_header_add(){
		add_action('wp_head', array($this, 'add_meta_viewport') );
	}
	
	function add_meta_viewport(){
		echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">';
	}
	
	function save_options() {
		update_option( 'cardealerpress_settings' , $this->options );
		$this->load_options();
	}

}

?>
