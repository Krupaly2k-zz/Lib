<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/krupaly2k
 * @since      1.0.0
 *
 * @package    Lib_upload
 * @subpackage Lib_upload/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Lib_upload
 * @subpackage Lib_upload/includes
 * @author     Krupal Lakhia <krupaly2k@gmail.com>
 */
class Lib_upload_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	protected static $db_version = 1;
	public static function activate() {
		$current_db_version = get_option('class-lib_upload-db-version');
		if ( ! $current_db_version ) {
			$current_db_version = 0;
		}
	 
		if ( intval( $current_db_version ) < Lib_upload_Activator::$db_version ) {
			if ( Lib_upload_Activator::create_or_upgrade_db() ) {
				update_option( 'class-lib_upload-db-version', Lib_upload_Activator::$db_version );
			}
		}
	
	}
	
	/**
	 * Creates the database tables required for the plugin if 
	 * they don't exist. Otherwise updates them as needed.
	 *
	 * @return bool true if update was successful.
	 */
	private static function create_or_upgrade_db() {
    global $wpdb;
 
    $table_name = $wpdb->prefix . 'lib';
         
    $charset_collate = '';
    if ( ! empty( $wpdb->charset ) ) {
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }
    if ( ! empty( $wpdb->collate ) ) {
        $charset_collate .= " COLLATE {$wpdb->collate}";
    }
 
    $sql = "CREATE TABLE " . $table_name . "("
         . "id mediumint(9) NOT NULL AUTO_INCREMENT, "
         . "title varchar(50) NOT NULL,"
		 . "coverfile text NULL,"
         . "file text NULL, "
		 . "category varchar(20) NOT NULL, "
         . "author varchar(50) NULL, "
		 . "description text NULL, "
         . "created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
         . "updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL, "
         . "UNIQUE KEY id (id)"
         . ")" . $charset_collate. ";";
 
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
 
    return true;
}

}