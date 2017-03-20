<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/krupaly2k
 * @since      1.0.0
 *
 * @package    Lib_upload
 * @subpackage Lib_upload/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lib_upload
 * @subpackage Lib_upload/admin
 * @author     Krupal Lakhia <krupaly2k@gmail.com>
 */
class Lib_upload_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lib_upload_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lib_upload_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/lib_upload-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lib_upload_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lib_upload_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/lib_upload-admin.js', array( 'jquery' ), $this->version, false );
		
		
	}
	
public function add_plugin_admin_menu() {

    /*
     * Add a settings page for this plugin to the Settings menu.
     *
     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
     *
     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
     *
     */
    add_options_page( 'Library Upload', 'WP Lib', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
    );
}

 /**
 * Add settings action link to the plugins page.
 *
 * @since    1.0.0
 */
 
public function add_action_links( $links ) {
    /*
    *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
    */
   $settings_link = array(
    '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
   );
   return array_merge(  $settings_link, $links );

}

/**
 * Render the settings page for this plugin.
 *
 * @since    1.0.0
 */
 
public function display_plugin_setup_page() {
    include_once( 'partials/lib_upload-admin-display.php' );
}
 public function options_update() {
    register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
 }
 
 /**
 * Creates the settings menu and sub menus for adding and listing licenses.
 */
public function add_upload_menu_page() {
    add_menu_page(
        __( 'Library', $this->plugin_name ),
        __( 'Library', $this->plugin_name ),
        'edit_posts',
        'wp-lib',
        array( $this, 'render_upload_menu_list' ),
        'dashicons-media-video',
        '26.1'
    );
 
    add_submenu_page(
        'wp-lib',
        __( 'Library', $this->plugin_name ),
        __( 'Library', $this->plugin_name ),
        'edit_posts',
        'wp-lib',
        array( $this, 'render_upload_menu_list' )
    );
 
    add_submenu_page(
        'wp-lib',
        __( 'Add new', $this->plugin_name ),
        __( 'Add new', $this->plugin_name ),
        'edit_posts',
        'wp-lib-new',
        array( $this, 'render_upload_menu_new' )
    );
}

public function render_upload_menu_new() {
    // Used in the "Product" drop-down list in view
    $products = get_posts(
        array(
            'orderby'           => 'post_title',
            'order'             => 'ASC',
            'post_type'         => 'wplm_product',
            'post_status'       => 'publish',
            'post_status'       => 'publish',
            'nopaging'          => true,
            'suppress_filters'  => true
        )
    );
 
    require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/lib_upload-admin-display.php';
}

/**
 * Renders the list of licenses menu page using the "licenses_list.php" partial.
 */
public function render_upload_menu_list() {
    $list_table = new Lib_List_Table( $this->plugin_name );
	        if( isset($_POST['s']) ){
                $list_table->prepare_items($_POST['s']);
        } else {
                $list_table->prepare_items();
        }

    
 
    require plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/lib_list.php';
}
/**
 * Handler for the add_license action (submitting 
 * the "Add New License" form).
 */
public function handle_add_lib() {

    global $wpdb;
 
    if ( ! empty( $_POST )
        && check_admin_referer( 'wp-lib-add-lib', 
            'wp-lib-add-lib-nonce' ) ) {
 
        // Nonce valid, handle data
		
		define( 'UPLOADS', trailingslashit( WP_CONTENT_DIR ) . '/plugins/lib_upload/admin/uploads/' );
		$target_dir = UPLOADS;
		
		if($_FILES["fileup"]["name"] != '')
		{
$target_file = $target_dir . basename($_FILES["fileup"]["name"]);

$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["fileup"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        //echo "File is not an image.";
        $uploadOk = 0;
    }
// Check file size
if ($_FILES["fileup"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}


// Allow certain file formats
$allowed = array("video/mp4", "application/pdf");

if(!in_array($_FILES['fileup']['type'], $allowed)) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
else{
	$uploadOk = 1;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	
    if (move_uploaded_file($_FILES["fileup"]["tmp_name"], $target_file)) {
		chmod($target_file, 0777);

        echo "The file ". basename( $_FILES["fileup"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
	 
}


		if($_FILES["coverfile"]["name"] != '')
		{
$target_file1 = $target_dir . basename($_FILES["coverfile"]["name"]);

$imageFileType1 = pathinfo($target_file1,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

    $check1 = getimagesize($_FILES["coverfile"]["tmp_name"]);
    if($check1 !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk1 = 1;
    } else {
        //echo "File is not an image.";
        $uploadOk1 = 0;
    }
// Check file size
if ($_FILES["coverfile"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk1 = 0;
}

// Allow certain file formats
$allowed = array("image/jpeg", "image/gif", "image/jpg", "image/png");

if(!in_array($_FILES['coverfile']['type'],$allowed)) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk1 = 0;
}
else{
	$uploadOk1 = 1;

}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk1 == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	
    if (move_uploaded_file($_FILES["coverfile"]["tmp_name"], $target_file1)) {
		chmod($target_file1, 0777);

        echo "The file ". basename( $_FILES["coverfile"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
	 
}

 
        $title = sanitize_text_field( $_POST['title'] );
		$file = sanitize_text_field( $_FILES['fileup']['name'] );
		$coverfile = sanitize_text_field( $_FILES['coverfile']['name'] );
		$description = sanitize_text_field( $_POST['desc'] );
		$author = sanitize_text_field( $_POST['author'] );
		$cat = sanitize_text_field( $_POST['cat'] );
		        // Save data to database
        $table_name = $wpdb->prefix . 'lib';
        $wpdb->insert(
            $table_name,
            array(
                'id' => '',
                'title' => $title,
                'file' => $file,
				'coverfile' => $coverfile,
				'description' => $description,
				'category' => $cat,
				'author' => $author,
				'created_at' => current_time( 'mysql' ),
                'updated_at' => current_time( 'mysql' )
            ),
            array(
                '%d',
                '%s',
				'%s',
                '%s',
                '%s',
                '%s',
                '%s',
				'%s',
                '%s'
            )
        );
 
        // Redirect to the list of licenses for displaying the new license
        wp_redirect( admin_url( 'admin.php?page=wp-lib&msg=add' ) );
		
    }
}

public function handle_edit_lib() {
    global $wpdb;
 
    if ( ! empty( $_POST )
        && check_admin_referer( 'wp-edit', 
            'wp-edit-nonce' ) ) {
 
        // Nonce valid, handle data
		define( 'UPLOADS', trailingslashit( WP_CONTENT_DIR ) . '/plugins/lib_upload/admin/uploads/' );
		$target_dir = UPLOADS;
		
$target_file = $target_dir . basename($_FILES["fileup"]["name"]);
if($_FILES["fileup"]["name"] != '')
{
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["fileup"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        //echo "File is not an image.";
        $uploadOk = 0;
    }
// Check file size
if ($_FILES["fileup"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}


// Allow certain file formats
$allowed = array("video/mp4", "application/pdf");

if(!in_array($_FILES['fileup']['type'], $allowed)) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
else{
	$uploadOk =1;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	
    if (move_uploaded_file($_FILES["fileup"]["tmp_name"], $target_file)) {
		chmod($target_file, 0777);

        echo "The file ". basename( $_FILES["fileup"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
	
}
}


$target_file1 = $target_dir . basename($_FILES["coverfile"]["name"]);
if($_FILES["coverfile"]["name"] != '')
{
$imageFileType1 = pathinfo($target_file1,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

    $check1 = getimagesize($_FILES["coverfile"]["tmp_name"]);
    if($check1 !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk1 = 1;
    } else {
        //echo "File is not an image.";
        $uploadOk1 = 0;
    }
// Check file size
if ($_FILES["coverfile"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk1 = 0;
}
// Allow certain file formats
$allowed = array("image/jpeg", "image/gif", "image/jpg", "image/png");

if(!in_array($_FILES['coverfile']['type'],$allowed)) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk1 = 0;
}
else{
	$uploadOk =1;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk1 == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	
    if (move_uploaded_file($_FILES["coverfile"]["tmp_name"], $target_file1)) {
		chmod($target_file1, 0777);

        echo "The file ". basename( $_FILES["coverfile"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
	
	
}
}

//$lib_edit = array_push($lib_edit,'')

		$id = 	sanitize_text_field( $_POST['id'] );
        $title = sanitize_text_field( $_POST['title'] );
				$file = sanitize_text_field( $_FILES['fileup']['name'] );
		
		$coverfile = sanitize_text_field( $_FILES['coverfile']['name'] );
		$description = sanitize_text_field( $_POST['desc'] );
		$author = sanitize_text_field( $_POST['author'] );
		$cat = sanitize_text_field( $_POST['cat'] );
		        // Save data to database
        $table_name = $wpdb->prefix . 'lib';
		
		if($coverfile != '' && $file == '')
		{
			$lib_edit = array('title'=>$title,'coverfile'=>$coverfile,'description'=>$description,'category'=>$cat,'author'=>$author);
		}
		else if($coverfile == '' && $file != '')
		{
			$lib_edit = array('title'=>$title,'file'=>$file,'description'=>$description,'category'=>$cat,'author'=>$author);
		}
		else if($coverfile == '' && $file == '')
		{
			$lib_edit = array('title'=>$title,'description'=>$description,'category'=>$cat,'author'=>$author);
		}
		else{
				$lib_edit = array('title'=>$title,'file'=>$file,'coverfile'=>$coverfile,'description'=>$description,'category'=>$cat,'author'=>$author);
		}
		
		$wpdb->update(
            $table_name,
            $lib_edit,
			array('id'=>$id),
            array(
                '%s',
				'%s',
                '%s',
                '%s',
                '%s',
                '%s',
				'%s'
            ),
			array( '%d' )
        );
 
        // Redirect to the list of licenses for displaying the new license
        wp_redirect( admin_url( 'admin.php?page=wp-lib&msg=edit' ) );
		
    }
}

public function handle_delete_lib() {
    global $wpdb;
 
    if ( ! empty( $_POST )
        && check_admin_referer( 'wp-delete', 
            'wp-delete-nonce' ) ) {
 
        // Nonce valid, handle data
		define( 'UPLOADS', trailingslashit( WP_CONTENT_DIR ) . '/plugins/lib_upload/admin/uploads/' );
		$target_dir = UPLOADS;
		
$target_file = $target_dir . basename($_FILES["fileup"]["name"]);
if($_FILES["fileup"]["name"] != '')
{
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

    $check = getimagesize($_FILES["fileup"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        //echo "File is not an image.";
        $uploadOk = 0;
    }
// Check file size
if ($_FILES["fileup"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType !='mp4' ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	
    if (move_uploaded_file($_FILES["fileup"]["tmp_name"], $target_file)) {
		chmod($target_file, 0777);

        echo "The file ". basename( $_FILES["fileup"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
	
	 if (move_uploaded_file($_FILES["coverfile"]["tmp_name"], $target_file)) {
		chmod($target_file, 0777);

        echo "The file ". basename( $_FILES["coverfile"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
}

		$id = 	sanitize_text_field( $_POST['id'] );
        $title = sanitize_text_field( $_POST['title'] );
		$file = sanitize_text_field( $_FILES['fileup']['name'] );
		$coverfile = sanitize_text_field( $_FILES['coverfile']['name'] );
		$description = sanitize_text_field( $_POST['desc'] );
		$author = sanitize_text_field( $_POST['author'] );
		$cat = sanitize_text_field( $_POST['cat'] );
		        // Save data to database
        $table_name = $wpdb->prefix . 'lib';
		
		
        $wpdb->update(
            $table_name,
            array(
                'title' => $title,
                'file' => $file,
				'coverfile' => $coverfile,
				'description' => $description,
				'category' => $cat,
				'author' => $author,
				'updated_at' => current_time( 'mysql' )
            ),
			array('id'=>$id),
            array(
                '%s',
				'%s',
                '%s',
                '%s',
                '%s',
                '%s',
				'%s'
            ),
			array( '%d' )
        );
 
        // Redirect to the list of licenses for displaying the new license
        wp_redirect( admin_url( 'admin.php?page=wp-lib' ) );
    }
}


 
}
