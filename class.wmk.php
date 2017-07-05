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
	public static $magazine_cover_options_au;

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
	        array('WMK_Magazine_Covers','display_magazinecovers_page'),
	        "dashicons-admin-page", 
	        23
	    );
	}

	public function admin_init() {
        $this->run_plugin();
        WMK_Magazine_Covers::init_magazinecovers_page();

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
				'id' => 'globalbrand',
				'desc' => 'THE GLOBAL BUSINESS BRAND','mediakit',
				'page' => 'mediakit'
			),
			array(
				'id' => 'ournumbers',
				'desc' => 'OUR NUMBERS',
				'page' => 'mediakit'
			),
			array(
				'id' => 'ouraudience',
				'desc' => 'OUR AUDIENCE',
				'page' => 'mediakit'
			),
			array(
				'id' => 'executiveawards',
				'desc' => 'Executive of the Year Awards',
				'page' => 'mediakit'
			),
			array(
				'id' => 'inspire',
				'desc' => 'Inspire',
				'page' => 'mediakit'
			),
			array(
				'id' => 'innovate',
				'desc' => 'Innovate',
				'page' => 'mediakit'
			),
			array(
				'id' => 'invest',
				'desc' => 'Invest',
				'page' => 'mediakit'
			),
			array(
				'id' => 'indulge',
				'desc' => 'Indulge',
				'page' => 'mediakit'
			),
			array(
				'id' => 'editorialcalendar',
				'desc' => 'Editorial Calendar',
				'page' => 'mediakit'
			),
			array(
				'id' => 'displayrates',
				'desc' => 'Display Rates',
				'page' => 'mediakit'
			),
			array(
				'id' => 'digitalrates',
				'desc' => 'Digital Rates',
				'page' => 'mediakit'
			),
			array(
				'id' => 'videointerviews',
				'desc' => 'Video Interviews',
				'page' => 'mediakit'
			),
		);
        $mediakit_fields = array(
        	array (
        		'id'    => $prefix.'globalbrand_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Global Brand Section',
			    'type'  => 'checkbox',
			    'metabox' => 'globalbrand'
			),
        	array (
        		'id'    => $prefix.'globalbrand_main',
			    'label' => 'Editors Global Brand Content',
			    'desc'  => 'Editors Global Brand Content',
			    'type'  => 'editor',
			    'metabox' => 'globalbrand'
			),
			array (
				'id'    => $prefix.'ournumbers_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Our Numbers Section',
			    'type'  => 'checkbox',
			    'metabox' => 'ournumbers'
			),
        	array (
        		'id'    => $prefix.'ournumbers_main',
			    'label' => 'Our Numbers Main Content',
			    'desc'  => 'Our Numbers Main Content',
			    'type'  => 'editor',
			    'metabox' => 'ournumbers'
			),
			array (
				'id'    => $prefix.'ouraudience_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Our Audience Section',
			    'type'  => 'checkbox',
			    'metabox' => 'ouraudience'
			),
        	array (
        		'id'    => $prefix.'ouraudience_main',
			    'label' => 'Our Audience Main Content',
			    'desc'  => 'Our Audience Main Content',
			    'type'  => 'editor',
			    'metabox' => 'ouraudience'
			),
			array (
				'id'    => $prefix.'executiveawards_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Executive Awards Section',
			    'type'  => 'checkbox',
			    'metabox' => 'executiveawards'
			),
			array (
        		'id'    => $prefix.'executiveawards_main',
			    'label' => 'Executive Awards Main Content',
			    'desc'  => 'Executive Awards Main Content',
			    'type'  => 'editor',
			    'metabox' => 'executiveawards'
			),
			array (
				'id'    => $prefix.'inspire_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Inspire Section',
			    'type'  => 'checkbox',
			    'metabox' => 'inspire'
			),
        	array (
        		'id'    => $prefix.'inspire_main',
			    'label' => 'Inspire Main Content',
			    'desc'  => 'Inspire & Mission Main Content',
			    'type'  => 'editor',
			    'metabox' => 'inspire'
			),
			array (
				'id'    => $prefix.'innovate_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Innovate Section',
			    'type'  => 'checkbox',
			    'metabox' => 'innovate'
			),
        	array (
        		'id'    => $prefix.'innovate_main',
			    'label' => 'Innovate Main Content',
			    'desc'  => 'Innovate Main Content',
			    'type'  => 'editor',
			    'metabox' => 'innovate'
			),
			array (
				'id'    => $prefix.'invest_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Invest Section',
			    'type'  => 'checkbox',
			    'metabox' => 'invest'
			),
        	array (
        		'id'    => $prefix.'invest_main',
			    'label' => 'Invest Main Content',
			    'desc'  => 'Invest Main Content',
			    'type'  => 'editor',
			    'metabox' => 'invest'
			),
			array (
				'id'    => $prefix.'indulge_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Indulge Section',
			    'type'  => 'checkbox',
			    'metabox' => 'indulge'
			),
        	array (
        		'id'    => $prefix.'indulge_main',
			    'label' => 'Indulge Main Content',
			    'desc'  => 'Indulge Main Content',
			    'type'  => 'editor',
			    'metabox' => 'indulge'
			),
			array (
				'id'    => $prefix.'editorialcalendar_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Editorial Calendar Section',
			    'type'  => 'checkbox',
			    'metabox' => 'editorialcalendar'
			),
        	array (
        		'id'    => $prefix.'editorialcalendar_main',
			    'label' => 'Editorial Calendar Main Content',
			    'desc'  => 'Editorial Calendar Main Content',
			    'type'  => 'editor',
			    'metabox' => 'editorialcalendar'
			),
			array (
				'id'    => $prefix.'displayrates_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Display Rates Section',
			    'type'  => 'checkbox',
			    'metabox' => 'displayrates'
			),
        	array (
        		'id'    => $prefix.'displayrates_main',
			    'label' => 'Display Rates Main Content',
			    'desc'  => 'Display Rates Main Content',
			    'type'  => 'editor',
			    'metabox' => 'displayrates'
			),
			array (
				'id'    => $prefix.'digitalrates_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Digital Rates Section',
			    'type'  => 'checkbox',
			    'metabox' => 'digitalrates'
			),
        	array (
        		'id'    => $prefix.'digitalrates_main',
			    'label' => 'Digital Rates Main Content',
			    'desc'  => 'Digital Rates Main Content',
			    'type'  => 'editor',
			    'metabox' => 'digitalrates'
			),
			array (
				'id'    => $prefix.'videointerviews_enable',
			    'label' => 'Enabled',
			    'desc'  => 'Enable Video Interviews Section',
			    'type'  => 'checkbox',
			    'metabox' => 'videointerviews'
			),
        	array (
        		'id'    => $prefix.'videointerviews_main',
			    'label' => 'Video Interviews Main Content',
			    'desc'  => 'Video Interviews Main Content',
			    'type'  => 'editor',
			    'metabox' => 'videointerviews'
			),
        );

        $mediakit_meta = new WMK_Post_Meta($mediakit_fields);
        foreach ($mediakit_sections as $section) {
        	$mediakit_meta->add_meta_box($section['id'],$section['desc'],$section['page']);
        }
	}

	public static function view($name,$args) {
		$magazine_cover_options_au = $args;
		$file = IW_WMK_ROOT_PATH . '/views/' . $name . '.php';
		include($file);
	}
}