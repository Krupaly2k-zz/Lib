<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/krupaly2k
 * @since      1.0.0
 *
 * @package    Lib_upload
 * @subpackage Lib_upload/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"></div>
<?php  if($_REQUEST['action'] == 'edit')
{ 
$tt = 'Edit Library';
$button_title = 'Edit Library';
}
else
{
	$tt = 'Add New Library';
	$button_title = 'Add Library';
}

?>
    <h2><?php _e( $tt, $this->plugin_name ); ?></h2>
    <p>
        <?php
            $instructions = 'Use this form to add video/book data. '
                . 'After filling all the data, make sure to click on save button.';
 
            _e( $instructions, $this->plugin_name );
        ?>
    </p>
	
	
 <?php

 if(isset($_REQUEST['book']))
 {

 global $wpdb;
if($_REQUEST['action'] == 'edit')
{
 $book = $_REQUEST['book'];
		$querystr = "SELECT * from wp_lib where id='".$book."'";
		$book_pdf = $wpdb->get_results($querystr, OBJECT);
		foreach($book_pdf as $book)
		{
			$title = $book->title;
			$coverfile = $book->coverfile;
			$file = $book->file;
			$category = $book->category;
			$author = $book->author;
			$description = $book->description;
			$id = $book->id; 	
			$act = 'edit';
			$field = 'wp-edit';
			$nonce = 'wp-edit-nonce';
		}
}
else if($_REQUEST['action'] == 'delete')
	{
		$id = $_REQUEST['book'];
		$wpdb->delete( 'wp_lib', array( 'id' => $id ), array( '%d' ) );

	}		
 
else
{
	$field = 'wp-lib-add-lib';
	$nonce = 'wp-lib-add-lib-nonce';	
	$act = 'lib_add_lib';
}	
 }
 ?>
    <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post" enctype="multipart/form-data">
        <?php if($_REQUEST['action'] == 'edit')
		{
			wp_nonce_field( 'wp-edit', 'wp-edit-nonce' );
			?>
			<input type="hidden" name="action" value="edit">
			<?php
		}
		else if($_REQUEST['action'] == 'delete')
		{
			wp_nonce_field( 'wp-delete', 'wp-delete-nonce' );
		}
		else{
			wp_nonce_field( 'wp-lib-add-lib', 'wp-lib-add-lib-nonce' );
			?>
			<input type="hidden" name="action" value="lib_add_lib">
			<?php
		}
		
		?>
        
		<?php if($id != '')
		{?> 
		<input type="hidden" name="id" value="<?php echo $id;?>">
		<?php  } ?>
        <table class="form-table">
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="title">
                        <?php _e( 'Title', $this->plugin_name ); ?>
                        <span class="description"><?php _e( '(required)', $this->plugin_name ); ?></span>
                    </label>
                </th>
                <td>
                    <input name="title" type="text" value="<?php echo $title;?>" id="title" aria-required="true">
                </td>
            </tr>
			
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="coverfile">
                        <?php _e( 'File to upload (Book cover pic / Video file)', $this->plugin_name ); ?>
                        <span class="description"><?php _e( '(required)', $this->plugin_name ); ?></span>
                    </label>
                </th>
                <td>
                                    
					<input name="coverfile" type="file" id="coverfile" aria-required="true"><?php if($coverfile != ''){ ?><?php echo $coverfile;?>&nbsp;<img src="<?php echo plugins_url('lib_upload/admin/uploads/'.$coverfile);?>" width="50" height="50"><?php } ?>
                        <?php /*foreach ( $products as $product ) : ?>
                            <option value="<?php echo $product->ID; ?>"><?php echo $product->post_title; ?></option>
                        <?php endforeach;*/ ?>
                    
                </td>
            </tr>
			<tr class="form-field form-required">
                <th scope="row">
                    <label for="file">
                        <?php _e( 'PDF file for book / Video File)', $this->plugin_name ); ?>
                        <span class="description"><?php _e( '(required)', $this->plugin_name ); ?></span>
                    </label>
                </th>
                <td>
                                    
					<input name="fileup" type="file" id="fileup" aria-required="true"><?php if($file != ''){ echo $file; } ?>
                        <?php /*foreach ( $products as $product ) : ?>
                            <option value="<?php echo $product->ID; ?>"><?php echo $product->post_title; ?></option>
                        <?php endforeach;*/ ?>
                    
                </td>
            </tr>
			<tr class="form-field form-required">
                <th scope="row">
                    <label for="cat">
                        <?php _e( 'Category', $this->plugin_name ); ?>
                        <span class="description"><?php _e( '(required)', $this->plugin_name ); ?></span>
                    </label>
                </th>
                <td>
					<select name="cat" id="cat">
					
						<option value="Video" <?php if($category == 'Video'){ ?> selected="selected" <?php } ?>>Video</option>
						<option value="Book" <?php if($category == 'Book'){ ?> selected="selected" <?php } ?>>Book</option>
					</select>
                    
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="description">
                        <?php _e( 'Description', $this->plugin_name ); ?>
                        <span class="description"><?php _e( '(required)', $this->plugin_name ); ?></span>
                    </label>
                </th>
                <td>
					<textarea name="desc" id="desc" rows="20" cols="5"><?php if($description != ''){ echo $description; }?></textarea>
                    
                </td>
            </tr>
			<tr class="form-field form-required">
                <th scope="row">
                    <label for="author">
                        <?php _e( 'Author', $this->plugin_name ); ?>
                        
                    </label>
                </th>
                <td>
					<input type="text" name="author" id="author" value="<?php echo $author;?>">
                    
                </td>
            </tr>
        </table>
 
        <p class="submit">
            <input type="submit" name="add-lib" class="button button-primary"
                   value="<?php _e( $button_title, $this->plugin_name ); ?>" >
        </p>
    </form>
	
	<?php 
	?>
 
</div>

