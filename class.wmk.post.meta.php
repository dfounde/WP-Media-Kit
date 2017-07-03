<?php 

/**
* Class responsible for adding and removing custom meta fields
*
* @package Wordpress Media Kit
* @license    http://opensource.org/licenses/gpl-license.php  GNU Public License
* @author  Daniel Gundi
*/

class WMK_Post_Meta {
	private $prefix = 'wmk_meta_';
	private $_label;
	private $_desc;
	private $_id;
	private $_callback;
	private $_fields;
	private $_context;
	private $_priority;
	private $_posttype;

	public function __construct($fields = array()) {
		$this->_fields = $fields;
	}

	public function add_meta_box($id,$label,$page,$context = 'normal',$priority = 'default') {
		$this->_id = $id;
		$this->_label = $label;
	//	$this->_callback = $callback;
		$this->_page = $page;
		$this->_context = $context; 
		$this->_priority = $priority;

		add_meta_box(
	        $this->_id,
	        $this->_label,
	        array($this,'render_meta_box'),
	        $this->_page,
	        $this->_context,
	        $this->_priority,
	        array($this->_id)
    	); 

    	add_action('save_post', array($this,'save_custom_meta'));
	}

	public function render_meta_box($post, $callback_args) {
		$fields = $this->_fields;
		$id =  $callback_args['id'];
		echo '<input type="hidden" name="custom_metabox_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />'; 
		foreach ($fields as $field) {
			if ( $id == $field['metabox'] ) {
				$meta = get_post_meta($post->ID, $field['id'], true);
				switch($field['type']) {
					case 'checkbox':
		            	echo '<label for="'.$field['id'].'">'.$field['label'].'</label>
		                <input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
		                    <br /><br />';
		            break;
		            case 'textbox':
		            	 echo '<strong><label for="'.$field['id'].'">'.$field['label'].'</label></strong>
	                           <input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="25" />
	                    <br /><span class="description">'.$field['desc'].'</span> <br /><br />';
		            break;
		            case 'textarea':
		            	echo '<strong><label for="'.$field['id'].'">'.$field['label'].'</label></strong>
	                          <textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
	                    <br /><span class="description">'.$field['desc'].'</span> <br /><br />';
		            break;
		            case 'editor':
		            	$content = '';
		            	wp_editor($meta, $field['id']);
		            break;
				}
			}
		}	
	}

	public function remove_meta_box() {
		remove_meta_box( 'tagsdiv-post_tag' , 'mediakit' , 'normal' ); 
	}

	public function save_custom_meta($post_id) {
		$fields = $this->_fields;
	    // verify nonce
	    if (!wp_verify_nonce($_POST['custom_metabox_nonce'], basename(__FILE__))) 
	        return $post_id;
	    // check autosave
	    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
	        return $post_id;
	    // check permissions
	    if ('page' == $_POST['post_type']) {
	        if (!current_user_can('edit_page', $post_id))
	            return $post_id;
	        } elseif (!current_user_can('edit_post', $post_id)) {
	            return $post_id;
	    }
	     
	    // loop through fields and save the data
	    foreach ($fields as $field) {
	        $old = get_post_meta($post_id, $field['id'], true);
	        $new = $_POST[$field['id']];
	        if ($new && $new != $old) {
	            update_post_meta($post_id, $field['id'], $new);
	        } elseif ('' == $new && $old) {
	            delete_post_meta($post_id, $field['id'], $old);
	        }
	    } // end foreach   
	}
}