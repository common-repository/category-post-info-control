<?php
/*
 * Plugin Name: Category Post Info Control
 * Plugin URI: http://www.pspsoftware.co.za/catpic-category-post-info-control/
 * Description: Allow excluding post info/Post meta (author and date etc) from posts in certain categories
 * Version: 1.1
 * Author: Dirk Wessels
 * Author URI: http://www.pspsoftware.co.za/
 *
 * This plugin has been developped and tested with Wordpress Version 3.4.2
 *
 * Copyright 2010  Dirk Wessels (email : dirk@familyis.org)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *
 */

$filePath = plugin_basename(__FILE__);

register_activation_hook($filePath,'cat_p_i_c_install');
if($wp_version >= '2.7') {
   register_uninstall_hook($filePath,'cat_p_i_c_uninstall');
} else {
   register_deactivation_hook($filePath,'cat_p_i_c_uninstall');
}

// Actions
add_action('admin_init', 'cat_p_i_c_init');
add_filter( 'genesis_post_info', 'category_post_info_filter' );

/**
 * Function called when installing or upgrading the plugin.
 * @return void.
 */
function cat_p_i_c_install()
{
	global $wpdb;
	global $cat_p_i_c_db_version;

	$cat_p_i_c_table_name = $wpdb->prefix.'category_info';
	$cat_p_i_c_db_version = '0.0.1';

	// create table on first install
    if($wpdb->get_var("show tables like '$cat_p_i_c_table_name'") != $cat_p_i_c_table_name) {

        cat_p_i_c_createTable($wpdb, $cat_p_i_c_table_name);
        add_option("cat_p_i_c_db_version", $cat_p_i_c_db_version);
        add_option("cat_p_i_c_configuration", '');
    }

    // On plugin update only the version nulmber is updated.
    $installed_ver = get_option( "cat_p_i_c_db_version" );
    if( $installed_ver != $cat_p_i_c_db_version ) {
        update_option( "cat_p_i_c_db_version", $cat_p_i_c_db_version );
    }

}

/**
 * Function called when un-installing the plugin.
 * @return void.
 */
function cat_p_i_c_uninstall()
{
	//Nothing required for uninstall. If the user accidentally deactivates the plugin, they'll want to keep the db table with values.
}

function cat_p_i_c_createTable($wpdb, $table_name)
{
    $sql = "CREATE TABLE  ".$table_name." (
          category_id bigint(20) NOT NULL ,
          hide_postinfo_top tinyint NULL,
          hide_postinfo_bottom tinyint NULL,
          PRIMARY KEY  (`category_id`)
        );";

    $results = $wpdb->query($sql);
	
}

function cat_p_i_c_init() {

	add_action('category_edit_form', 'cat_p_i_c_add_category_edit_fields');
	add_action('edit_category', 'cat_pic_save');
}

function cat_p_i_c_add_category_edit_fields($params) {
	global $wpdb;
	
	$cat_p_i_c_table_name = $wpdb->prefix.'category_info';

	$dbrow = $wpdb->get_row("SELECT * FROM $cat_p_i_c_table_name WHERE category_id = $params->term_id");

	$hide_postinfo_topDB = 0;
	$hide_postinfo_bottomDB = 0;

	if ($dbrow != null) {
		$hide_postinfo_topDB = $dbrow->hide_postinfo_top;
		$hide_postinfo_bottomDB = $dbrow->hide_postinfo_bottom;
	}
?>
<div class="form-table postbox">
  <h3 class='hndle'>Category Post Info Control</h3>
  &nbsp;<label for="cat_p_i_c_hide_postinfo_top">Hide Post Info Top</label>:
  <input type="checkbox" name="cat_p_i_c_hide_postinfo_top" value="Yes"
  <?php if ($hide_postinfo_topDB == 1) echo "checked"; ?> >
</input>
  <br></br>
</div>
	<br><br>
<?php
}

function cat_pic_save($id) {
	global $wpdb;
	$cat_p_i_c_table_name = $wpdb->prefix.'category_info';

	$dbrow = $wpdb->get_row("SELECT * FROM $cat_p_i_c_table_name WHERE category_id = $id");

	$hide_postinfo_topDB = 0;
	$hide_postinfo_bottomDB = 0;

	if ($dbrow != null) {
		$hide_postinfo_topDB = $dbrow->hide_postinfo_top;
		$hide_postinfo_bottomDB = $dbrow->hide_postinfo_bottom;
	}
	$cat_p_i_c_hide_postinfo_top = $_POST["cat_p_i_c_hide_postinfo_top"];
    if (isset($cat_p_i_c_hide_postinfo_top)) {
		if ($cat_p_i_c_hide_postinfo_top == 'Yes') {
			$hide_postinfo_topDB = 1;
		} else {
			$hide_postinfo_topDB = 0;
		}
	} else {
		$hide_postinfo_topDB = 0;
	}
	$cat_p_i_c_hide_postinfo_bottom = $_POST["cat_p_i_c_hide_postinfo_bottom"];

	if ($dbrow == null) {
		$wpdb->insert( $cat_p_i_c_table_name, array( 'category_id' => $id, 'hide_postinfo_top' => $hide_postinfo_topDB, 'hide_postinfo_bottom' => $hide_postinfo_bottomDB ) );

	} else {
		$wpdb->update($cat_p_i_c_table_name, array( 'category_id' => $id, 'hide_postinfo_top' => $hide_postinfo_topDB, 'hide_postinfo_bottom' => $hide_postinfo_bottomDB), array( 'category_id' => $id ) );
	}


}

function cat_p_i_c_add_top_checked($id, $cat_p_i_c_top_checked){
	global $wpdb;
	
	$cat_p_i_c_table_name = $wpdb->prefix.'category_info';
	$hide_postinfo_topDB = 0;

	$dbrow = $wpdb->get_row("SELECT * FROM $cat_p_i_c_table_name WHERE category_id = $id");
	if ($dbrow != null) {
		if ($dbrow->hide_postinfo_top == 1) {
			$hide_postinfo_topDB = $dbrow->hide_postinfo_top;
		}
		if ($dbrow->hide_postinfo_bottom == 1) {
			$hide_postinfo_bottomDB = $dbrow->hide_postinfo_bottom;
		}
	}
	$cat_p_i_c_top_checked[$id] = $hide_postinfo_topDB;
	$_SESSION['cat_p_i_c_top_checked'] = $cat_p_i_c_top_checked;
	return $cat_p_i_c_top_checked;
}

function cat_p_i_c_show_post_info($id){
	$cat_p_i_c_top_checked = array();

	if (isset($_SESSION['cat_p_i_c_top_checked'])) {
		$cat_p_i_c_top_checked = $_SESSION['cat_p_i_c_top_checked'];
	}

	$hide_postinfo_topDB = 0;

	$post_categories =  get_the_category();
	foreach($post_categories as $c){
		$term_id = $c->term_id;
		if (!array_key_exists($term_id, $cat_p_i_c_top_checked)) {
			$cat_p_i_c_top_checked = cat_p_i_c_add_top_checked($term_id, $cat_p_i_c_top_checked);
		}

		if (array_key_exists($term_id, $cat_p_i_c_top_checked)) {
			$hide_postinfo_topDB = $cat_p_i_c_top_checked[$term_id];
		}

	}


	if ($hide_postinfo_topDB == 0){
		return true;
	} else {
		return false;
	}
}

/*
	Genesis function to return the post_info or not.
*/
function category_post_info_filter($post_info) {
	if (!is_page()) {
		if (!cat_p_i_c_show_post_info(get_the_ID())){
			$post_info = '';
		}
	}
	return $post_info;
}

?>
