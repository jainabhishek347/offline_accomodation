<?php
/*
* Plugin Name: custom contact details with wp list
* Description: This plugin to create custom Contact Details List from database using Wp List.
* Version:     1.0.0
* Author:      Shail Mehta
* Author URI:  https://profiles.wordpress.org/mehtashail/
* License:     GPLv2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: wordpress.org
*/

defined('ABSPATH');

require plugin_dir_path(__FILE__) . 'includes/wpsc-form.php';

function wpsc_install()
{
    global $wpdb;
    global $wpsc_db_version;

    $table_name = $wpdb->prefix . 'wsc';
    $sql = "CREATE TABLE " . $table_name . " (
      id int(11) NOT NULL AUTO_INCREMENT,
      name VARCHAR (50) NOT NULL,
      email VARCHAR(100) NOT NULL,
      address VARCHAR(500) NOT NULL,
      phone int(11) NOT NULL,
      date date NOT NULL
      PRIMARY KEY  (id)
    );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    add_option('wpsc_db_version', $wpsc_db_version);
    $installed_ver = get_option('wpsc_db_version');
    if ($installed_ver != $wpsc_db_version) {
        $sql = "CREATE TABLE " . $table_name . " (
          id int(11) NOT NULL AUTO_INCREMENT,
          name VARCHAR (50) NOT NULL,
          email VARCHAR(100) NOT NULL,
          address VARCHAR(500) NOT NULL,
          phone int(11),
          date date
          PRIMARY KEY  (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option('wpsc_db_version', $wpsc_db_version);
    }
}

register_activation_hook(__FILE__, 'wpsc_install');
function wpsc_install_data()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'wsc';

}

register_activation_hook(__FILE__, 'wpsc_install_data');
function wpsc_update_db_check()
{
    global $wpsc_db_version;
    if (get_site_option('wpsc_db_version') != $wpsc_db_version) {
        wpsc_install();
    }
}

add_action('plugins_loaded', 'wpsc_update_db_check');


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


class Custom_contact_details_with_wp_List extends WP_List_Table
{
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'detail',
            'plural' => 'details',
        ));
    }

    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    function column_name($item)
    {
        $actions = array(
            'edit' => sprintf('<a href="?page=details_form&id=%s">%s</a>', $item['id'], __('Edit')),
            $delete_fields = 'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete')),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }


    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'email' => __('E-Mail'),
            'address' => __('Address'),
            'phone' => __('Phone'),
            'date' => __('Date')  
        );
        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', true),
            'email' => array('email', true),
            'address' => array('address',true),
            'phone' => array('phone', true),
            'date' => array('date',true)
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wsc';

        if ('delete' === $this->current_action()) {
           $ids = $_REQUEST['id'];
           if (is_array($ids)){
               $ids = implode(',', $ids);
           }
            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wsc';
        $per_page = 10;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}

function wpsc_admin_menu()
{
    add_menu_page(__('AnantKalen Path'), __('AnantKalen Path'), 'activate_plugins', 'details', 'wpsc_details_page_handler', 'dashicons-list-view');
    add_submenu_page('details', __('Details'), __('Details'), 'activate_plugins', 'details', 'wpsc_details_page_handler');
    add_submenu_page('details', __('Add new'), __('Add new'), 'activate_plugins', 'details_form', 'wpsc_details_form_page_handler');
}

add_action('admin_menu', 'wpsc_admin_menu');
function wpsc_validate_detail($item)
{
    $messages = array();
    if (empty($item['name'])) $messages[] = __('Name is required');
    if (!empty($item['email']) && !is_email($item['email'])) $messages[] = __('E-Mail is in wrong format');
    if (empty($item['address'])) $messages[] = __('Address is required');
    if (empty($item['phone'])) $messages[] = __('Phone is required');
    if (empty($item['date'])) $messages[] = __('Date is required');
    if (empty($messages)) return true;
    return implode('<br />', $messages);
}
function showdetails_shortcode() {
   //return 'Hello world!';
    global $wpdb;
        $table_name = $wpdb->prefix . 'wsc';
        $per_page = 10;
        
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY id $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        $i = 1;
        $str = '<link rel="stylesheet" type="text/css" href="'.plugin_dir_url(__FILE__).'includes/css/jquery.dataTables.min.css">';
        $str .= '<script src="'.plugin_dir_url(__FILE__).'includes/js/jquery-3.5.1.js"></script>';
        $str .= '<script src="'.plugin_dir_url(__FILE__).'includes/js/jquery.dataTables.min.js"></script>';
        $str .= '<table id="datatable" class="display" style="width:100%"><thead><tr><th>Number</th><th>Date</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th></tr></thead><tboday>';
        foreach($items as $key => $item){
        $str .= '<tr>';
        $str .= '<td>'.$i.'</td><td>'.$item["date"].'</td><td>'.$item["name"].'</td><td>'.$item["email"].'</td><td>'.$item["phone"].'</td><td>'.$item["address"].'</td>';
        $str .= '</tr>';     
          $i++; 
        }
        $str .= '</tboday><tfoot><tr><th>Number</th><th>Date</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th></tr></tfoot></table>';
        $str .= '<script>$(document).ready(function() { $("#datatable").DataTable();} );</script>' ;
        return $str;
}
add_shortcode( 'anantkalen_paath_list', 'showdetails_shortcode' );

