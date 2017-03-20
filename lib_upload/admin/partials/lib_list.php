<?php
/**
 * The view for the admin page used for listing licenses.
 *
 * @package    Wp_License_Manager
 * @subpackage Wp_License_Manager/admin/partials
 */
 ob_start();
?>
 
<div class="wrap">
    <h2>
        <?php _e( 'Library', $this->plugin_name ); ?>
        <a class="add-new-h2" href="<?php echo admin_url( 'admin.php?page=wp-licenses-new' );?>">
            <?php _e( 'Add new', $this->plugin_name ) ?>
        </a>
    </h2>

	<?php if($_REQUEST['msg'] == 'edit')
	{
		
?>
		  <div class="notice notice-success">
      <p><?php _e( 'Record has been edited', 'my_plugin_textdomain' ); ?></p>
  </div>
  
	<?php } if($_REQUEST['msg'] == 'add') { ?>
		  <div class="notice notice-success">
      <p><?php _e( 'Record has been added', 'my_plugin_textdomain' ); ?></p>
  </div>
<?php
	}
	if($_REQUEST['msg'] == 'delete') { ?>
<div class="notice notice-error">
      <p><?php _e( 'Record has been deleted', 'my_plugin_textdomain' ); ?></p>
  </div>
<?php
	}
if($_REQUEST['action'] == 'delete')
	{
		global $wpdb;
		$id = $_REQUEST['book'];
		$wpdb->delete( 'wp_lib', array( 'id' => $id ), array( '%d' ) );
		
		?>
<div class="notice notice-error">
      <p><?php _e( 'Record has been deleted', 'my_plugin_textdomain' ); ?></p>
  </div>
		
  
<?php


	}
	
	?>
  <form method="post">
    <input type="hidden" name="page" value="ttest_list_table">
    <?php
    $list_table->search_box( 'search', 'search_id' );

     $list_table->display(); ?>
	</form>
	

</div>