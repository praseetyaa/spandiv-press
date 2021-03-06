<?php

if(!class_exists('Spandiv_Plugin')) {

class Spandiv_Plugin {
    // Get plugin URL
    static function plugin_url($file) {
        return plugins_url($file, __DIR__);
    }

    // Get plugin directory path
    static function plugin_dir_path($file) {
        return plugin_dir_path(__DIR__) . $file;
    }

    // Get setting value
    static function get_value($key) {
        global $wpdb;
        $table_settings = $wpdb->prefix . 'spandiv';
        $data = $wpdb->get_results("SELECT * FROM $table_settings WHERE setting_key='{$key}'");
        if(count($data) > 0)
            return $data[0]->setting_value;
        else
            return '';
    }
	
	// Sync member
	static function sync_member() {
		$api_params = array(
			'url' => home_url()
		);
		$response = wp_remote_post("https://spandiv.xyz/wp-json/spandiv/v1/sync-member", array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));
	}

    // Create table
    function create_table() {
        global $wpdb;
        $table = $wpdb->prefix . 'spandiv';
        $sql = "CREATE TABLE IF NOT EXISTS `$table` (
            `setting_id` int(11) NOT NULL AUTO_INCREMENT,
            `setting_key` varchar(255) DEFAULT NULL,
            `setting_value` text DEFAULT NULL,
            PRIMARY KEY(setting_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
        ";
        if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    // Add admin page
    function add_admin_page() {
        add_menu_page(
            'Spandiv', // Page title
            'Spandiv', // Menu title
            'manage_options', // Capability
            'spandiv', // Menu slug
            array('Spandiv_Plugin', 'crud_page'), // Callable
            self::plugin_url('assets/icon.png') // Icon URL
        );
    }

    // Callable CRUD page
    function crud_page() {
        global $wpdb;
        $table_settings = $wpdb->prefix . 'spandiv';

        // If submit form
        if(isset($_POST['submit'])) {
            // Loop settings
            foreach($_POST['setting'] as $key=>$setting) {
                // Get setting by key
                $count = count($wpdb->get_results("SELECT * FROM $table_settings WHERE setting_key='{$key}'"));
                // If exists: update data
                if($count > 0)
                    $wpdb->query("UPDATE $table_settings SET setting_value='{$setting}' WHERE setting_key='{$key}'");
                // If no exists: insert data
                else
                    $wpdb->query("INSERT INTO $table_settings(setting_key,setting_value) VALUES('{$key}','{$setting}')");
            }
        }

        // View
        include_once(self::plugin_dir_path('views/index.php'));
		
		// Sync member
		self::sync_member();
    }

    // Get setting value for shortcode
    static function get_value_for_shortcode($attr) {
        $args = shortcode_atts(array(
            'key' => '',
        ), $attr );

        global $wpdb;
        $table_settings = $wpdb->prefix . 'spandiv';
        $data = $wpdb->get_results("SELECT * FROM $table_settings WHERE setting_key='{$args["key"]}'");
        if(count($data) > 0)
            return $data[0]->setting_value;
        else
            return '';
    }
}

}

?>