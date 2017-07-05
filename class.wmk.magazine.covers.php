<?php 

/**
* 
*/

class WMK_Magazine_Covers
{
	public static $magazine_cover_options_au;
	public function __construct() {
		//add_action('admin_menu', array($this,'add_new_menu_items'));
	}

	public static function init_magazinecovers_page() {
		self::$magazine_cover_options_au = array(
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
		add_settings_section("magazine_covers_section", "Australian Covers", WMK_Magazine_Covers::display_magazine_covers_header(), "magazinecovers-options");
		foreach (self::$magazine_cover_options_au as $au_options) {
	        register_setting("magazine_covers_section", $au_options['id']);
	    }
		
	}

	public static function display_magazinecovers_page() {
		WP_Media_Kit::view('magazine-covers',self::$magazine_cover_options_au);
	}

	public static function display_magazine_covers_header() {

	}


}