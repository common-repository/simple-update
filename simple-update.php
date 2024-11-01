<?php

// Plugin Name: Flowpress Simple Update
// Plugin URI: http://flowpress.ca/
// Description: A simpler way to update your page content.
// Version: 0.0.3
// Author: Mario Dabek, Flowpress
// Requires at least: 3.5
// Tested up to: 4.5.2
// Stable tag: 4.6
// License: GPLv2 or later
// License URI: http://www.gnu.org/licenses/gpl-2.0.html

class simple_update{

	var $import_validation;
	
	function enqueue_scripts(){
		wp_enqueue_script('simple_update',plugins_url( '/simple-update.js' , __FILE__ ) ,array( 'jquery' ), '0.0.2');
		wp_enqueue_style( 'simple_update', plugins_url( '/simple-update.css', __FILE__ ), array(), '0.0.2' );
	}
	
}

$simple_update = new simple_update;

add_action('admin_menu',array(&$simple_update,'setup_menu'));
add_action('init', array(&$simple_update,'enqueue_scripts'), 1);

function add_some_box() {
    add_meta_box(
        'simple_update', 
        'Simple Update [ <a href="http://flowpress.ca/">Flowpress</a> ]',
        'add_something_in_the_box',
        array('post','page'), 
    	'normal',         
    	'high'       
    );
}
 
function add_something_in_the_box() {
	echo '<div class="simple_update_temp_data">nothing here</div>';
	echo '<div class="simple_update_temp_vars">no simple update shortcuts found</div>';
}
 
if (is_admin()) add_action('admin_menu', 'add_some_box');

function add_permissions_box() {

        add_meta_box(
            'simple_update_permissions', 
            'Simple Update Options [ <a href="http://flowpress.ca/">Flowpress</a> ]',
            'get_meta_permissions',
            array('post','page'), 
            'side',         
            'low'       
        );
   
}

function get_meta_permissions() {

    global $post;
    global $wpdb;
    $value = get_post_meta( $post->ID, 'hide_wp_editor', true);  
    $checked = ( $value != '' ) ? ' checked ' :''; 
    global $current_user;
    get_currentuserinfo();

    if (($_POST->post_author == $current_user->ID) || current_user_can('administrator')) {
        echo "<label>Hide Editor</label>&nbsp;&nbsp;<input type='checkbox' $checked name='hide_wp_editor'>";
    } else {
        echo "<label>Hide Editor</label>&nbsp;&nbsp;
        <input type='checkbox' $checked disabled>
        <input style='display:none;' type='checkbox' $checked name='hide_wp_editor'>";
    }

}
 
function hide_editor_save($postid) {

	if ($_POST['hide_wp_editor'] == '') {
		delete_post_meta($postid, 'hide_wp_editor');
	} else { 
		update_post_meta( $postid, 'hide_wp_editor', true ); 
	}
}

add_action('save_post','hide_editor_save');

if (is_admin()) add_action('admin_menu', 'add_permissions_box');


function su_button_script() {
    if(wp_script_is("quicktags"))
    {
        ?>
            <script type="text/javascript">
                
                function getSel()
                {
                    var txtarea = document.getElementById("content");
                    var start = txtarea.selectionStart;
                    var finish = txtarea.selectionEnd;
                    return txtarea.value.substring(start, finish);
                }

                QTags.addButton( 
                    "su", 
                    "SimpleUpdate", 
                    su_callback
                );

                function su_callback(title)
                {
                    var selected_text = getSel();
                    console.log(selected_text);
                    if (!selected_text) {
                    	alert('Select the text you want to create a shortcut for first.');
                    	return false;
                    }
                    title = suFunction();
                    if (title) {
                    	QTags.insertContent("<div class='simple_update' data-title='" + title + "'>" + selected_text + "</div>");
                    	source_changed();
                    }
                }


				function suFunction() {
				    var title = prompt("Please enter shortcut name", "");
				    if (title != null) {
				    	return title;
				    }
				    if (input === null) {
						return suFunction(); //break out of the function early
					}
				}

            </script>
        <?php
    }
}

add_action("admin_print_footer_scripts", "su_button_script");

// tinymce button code

add_action( 'init', 'code_button' );
function code_button() {
    add_filter( "mce_external_plugins", "code_add_button" );
    add_filter( 'mce_buttons', 'code_register_button' );
}
function code_add_button( $plugin_array ) {
    $plugin_array['mycodebutton'] = $dir = plugins_url( 'tinymce_custom.js', __FILE__ );
    return $plugin_array;
}
function code_register_button( $buttons ) {
    array_push( $buttons, 'codebutton' );
    return $buttons;
}




