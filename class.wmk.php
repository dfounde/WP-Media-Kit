<?php 

/**
* Class to initiate all hooks for Wordpress Media Kit Plugin.
*
* @package Wordpress Media Kit
* @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
* @author  Daniel Gundi
*/

if ( ! defined( 'ABSPATH' ) ) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

class WP_Media_Kit
{
	private static $instance = null;

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	private function __construct()
	{
		add_action('admin_enqueue_scripts', array($this,'load_backend_resources'));
		add_action("admin_init", array($this,'admin_init'));
		add_action("init", array($this,'register_mediakit_post_type'));
		add_action('admin_menu', array($this,'add_new_menu_items'));

	}

	public function load_backend_resources() {
		wp_enqueue_script('jquery-ui-draggable');
	    wp_enqueue_script('jquery-ui-droppable');
	    wp_enqueue_script('jquery-ui-sortable');
	    wp_enqueue_style('wmk-admin-styles',  plugin_dir_url(__FILE__) . 'static/css/admin-styles.css');
	    wp_enqueue_script('wmk-admin-scripts', plugin_dir_url(__FILE__) . 'static/js/admin-scripts.js', array('jquery','jquery-ui-droppable','jquery-ui-draggable', 'jquery-ui-sortable'));
	    wp_enqueue_media ();
	    wp_enqueue_script('moment-js', plugin_dir_url(__FILE__) . 'static/js/moment.js', array('jquery','jquery-ui-droppable','jquery-ui-draggable', 'jquery-ui-sortable'));
	}

	public function add_new_menu_items() {
		add_submenu_page(
	        "edit.php?post_type=mediakit",
	        "Magazine Covers",
	        "Magazine Covers",
	        "manage_options",
	        "magazinecovers-options",
	        array('WMK_Magazine_Covers','init_magazinecovers_page'),
	        "dashicons-admin-page", 
	        23
	    );
	}

	public function display_magazinecovers_page() {

	}

	public function admin_init() {
		$magazine_cover_options_au = array(
		    array(
		            'id' => 'magazine_cover_month_1',
		            'title' => 'January',
		        ),
		    array(
		            'id' => 'magazine_cover_month_2',
		            'title' => 'February',
		        ),
		    array(
		            'id' => 'magazine_cover_month_3',
		            'title' => 'March',
		        ),
		    array(
		            'id' => 'magazine_cover_month_4',
		            'title' => 'April',
		        ),
		    array(
		            'id' => 'magazine_cover_month_5',
		            'title' => 'May',
		        ),
		    array(
		            'id' => 'magazine_cover_month_6',
		            'title' => 'June',
		        ),
		    array(
		            'id' => 'magazine_cover_month_7',
		            'title' => 'July',
		        ),
		    array(
		            'id' => 'magazine_cover_month_8',
		            'title' => 'August',
		        ),
		    array(
		            'id' => 'magazine_cover_month_9',
		            'title' => 'September',
		        ),
		    array(
		            'id' => 'magazine_cover_month_10',
		            'title' => 'October',
		        ),
		    array(
		            'id' => 'magazine_cover_month_11',
		            'title' => 'November',
		        ),
		    array(
		            'id' => 'magazine_cover_month_12',
		            'title' => 'December',
		        ),

		);

		add_settings_section("magazine_covers_section", "Australian Covers", "display_magazine_covers_header", "magazinecovers-options");
		foreach ($magazine_cover_options_au as $au_options) {
            register_setting("magazine_covers_section", $au_options['id']);
        }

        $this->run_plugin();
	}

	public function register_mediakit_post_type() {
		$post_labels = array(
	        'name' => 'Media Kit', // Rename these to suit
	        'menu_name' => 'Media Kit',
	        'singular_name' => 'Media Kit',
	        'add_new' => 'Add '. 'Media Kit Page',
	        'add_new_item' => 'Add '. 'Media Kit Page',
	        'edit' => 'Edit',
	        'edit_item' => 'Edit Media Kit Page',
	        'new_item' => 'New Media Kit Page',
	        'view' => 'View Media Kit',
	        'view_item' => 'View Media Kit Page',
	        'search_items' => 'Search Media Kit Pages',
	        'not_found' => 'No Media Kit Pages',
	        'not_found_in_trash' => 'No Media Kit Pages found in Trash'
	    );  

	    $posts_args = array(
	        'labels' => $post_labels,
	        'public' => true,
			'exclude_from_search' => true,
	        'publicly_queryable' => true,
	        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
	        'has_archive' => true, 
	        'supports' => array(
	            'title',
	        ),
	        'menu_position' => 1,
	        'menu_icon' => 'dashicons-admin-post',
	        'taxonomies' => array('mediakit_category','post_tag')
	    );

	    register_post_type('mediakit', $posts_args); 

	    register_taxonomy( 'mediakit-category', 'mediakit', array( 'hierarchical' => true, 'label' => 'Media Kit Categories', 'show_admin_column' => true, 'query_var' => true, 'rewrite' => array('slug' => 'mediakit/category','with_front' => FALSE )) ); 

	    flush_rewrite_rules();
	}

	private function run_plugin() {
		$prefix = 'wmk_meta_';
		$mediakit_sections = array(
			array(
				'id' => 'ed_letter',
				'desc' => 'Editor\'s Letter & Big Features','mediakit',
				'page' => 'mediakit'
			),
			array(
				'id' => 'cornerstones_mission',
				'desc' => 'Cornerstones & Mission',
				'page' => 'mediakit'
			),
			array(
				'id' => 'every_issue',
				'desc' => 'In Every Issue',
				'page' => 'mediakit'
			),
			array(
				'id' => 'brand_reach',
				'desc' => 'Brand Reach',
				'page' => 'mediakit'
			),
			array(
				'id' => 'reader_profile',
				'desc' => 'Reader Profile',
				'page' => 'mediakit'
			),
			array(
				'id' => 'executive_awards',
				'desc' => 'Executive of the Year Awards',
				'page' => 'mediakit'
			),
			array(
				'id' => 'global_editions',
				'desc' => 'Global Editions',
				'page' => 'mediakit'
			),
			array(
				'id' => 'advertising_opportunities',
				'desc' => 'Advertising Opportunities & Deadlines',
				'page' => 'mediakit'
			),
			array(
				'id' => 'digital_opportunities',
				'desc' => 'Digital Advertising Opportunities & Deadlines',
				'page' => 'mediakit'
			),
		);
        $mediakit_fields = array(
        	array (
        		'id'    => $prefix.'edletter_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Editors Letter Section',
			    'type'  => 'checkbox',
			    'metabox' => 'ed_letter'
			),
        	array (
        		'id'    => $prefix.'edletter_main',
			    'label' => 'Editors Letter Main Content',
			    'desc'  => 'Editors Letter Main Content',
			    'type'  => 'editor',
			    'metabox' => 'ed_letter'
			),
			array (
				'id'    => $prefix.'cornerstones_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Cornerstones & Mission Section',
			    'type'  => 'checkbox',
			    'metabox' => 'cornerstones_mission'
			),
        	array (
        		'id'    => $prefix.'cornerstones_main',
			    'label' => 'Cornerstones & Mission Main Content',
			    'desc'  => 'Cornerstones & Mission Main Content',
			    'type'  => 'editor',
			    'metabox' => 'cornerstones_mission'
			),
			array (
				'id'    => $prefix.'every_issue_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Every Issue Section',
			    'type'  => 'checkbox',
			    'metabox' => 'every_issue'
			),
        	array (
        		'id'    => $prefix.'every_issue_main',
			    'label' => 'Every Issue Main Content',
			    'desc'  => 'Every Issue Main Content',
			    'type'  => 'editor',
			    'metabox' => 'every_issue'
			),
			array (
				'id'    => $prefix.'brand_reach_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Brand Reach Section',
			    'type'  => 'checkbox',
			    'metabox' => 'brand_reach'
			),
        	array (
        		'id'    => $prefix.'brand_reach_main',
			    'label' => 'Cornerstones & Mission Main Content',
			    'desc'  => 'Cornerstones & Mission Main Content',
			    'type'  => 'editor',
			    'metabox' => 'brand_reach'
			),
			array (
				'id'    => $prefix.'reader_profile_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Cornerstones & Mission Section',
			    'type'  => 'checkbox',
			    'metabox' => 'reader_profile'
			),
        	array (
        		'id'    => $prefix.'reader_profile_main',
			    'label' => 'Reader Profile Main Content',
			    'desc'  => 'Reader Profile Main Content',
			    'type'  => 'editor',
			    'metabox' => 'reader_profile'
			),
				array (
				'id'    => $prefix.'executive_awards_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Executive Awards Section',
			    'type'  => 'checkbox',
			    'metabox' => 'executive_awards'
			),
        	array (
        		'id'    => $prefix.'executive_awards_main',
			    'label' => 'Executive Awards Main Content',
			    'desc'  => 'Executive Awards Main Content',
			    'type'  => 'editor',
			    'metabox' => 'executive_awards'
			),
				array (
				'id'    => $prefix.'global_editions_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Global Editions Section',
			    'type'  => 'checkbox',
			    'metabox' => 'global_editions'
			),
        	array (
        		'id'    => $prefix.'global_editions_main',
			    'label' => 'Global Editions Main Content',
			    'desc'  => 'Global Editions Main Content',
			    'type'  => 'editor',
			    'metabox' => 'global_editions'
			),
			array (
				'id'    => $prefix.'advertising_opportunities_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Advertising Opportunities Section',
			    'type'  => 'checkbox',
			    'metabox' => 'advertising_opportunities'
			),
        	array (
        		'id'    => $prefix.'advertising_opportunities_main',
			    'label' => 'Advertising Opportunities Main Content',
			    'desc'  => 'Advertising Opportunities Main Content',
			    'type'  => 'editor',
			    'metabox' => 'advertising_opportunities'
			),
			array (
				'id'    => $prefix.'digital_opportunities_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Digital Advertising Section',
			    'type'  => 'checkbox',
			    'metabox' => 'digital_opportunities'
			),
        	array (
        		'id'    => $prefix.'digital_opportunities_main',
			    'label' => 'Digital Advertising Main Content',
			    'desc'  => 'Digital Advertising Main Content',
			    'type'  => 'editor',
			    'metabox' => 'digital_opportunities'
			),
        );

        $mediakit_meta = new WMK_Post_Meta($mediakit_fields);
        foreach ($mediakit_sections as $section) {
        	$mediakit_meta->add_meta_box($section['id'],$section['desc'],$section['page']);
        }
	}
}