<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/krupaly2k
 * @since      1.0.0
 *
 * @package    Lib_upload
 * @subpackage Lib_upload/public/partials
 */
if(!isset($_REQUEST['lib']))
{	
 //echo 'KL<pre>';
 //print_r($pageposts);
 define( 'UPLOADS', trailingslashit( WP_CONTENT_DIR ) . 'plugins/lib_upload/admin/uploads/' );
		$target_dir = UPLOADS;
		
?>
<style>
.square {
    float:left;
    position: relative;
    width: 30%;
    padding-bottom : 5%; /* = width for a 1:1 aspect ratio */
    margin:1.66%;
    background-position:center center;
    background-repeat:no-repeat;
    background-size:cover; /* you change this to "contain" if you don't want the images to be cropped */
}


</style>
<div style="width:100%;float:left;">

<?php
for($i=0;$i<count($pageposts);$i++)
{

if($pageposts[$i]->category == 'Book')
{
if($pageposts[$i]->coverfile === '')
{
	$pageposts[$i]->coverfile = 'no_image.jpeg';
}
?>

<div class="square">
<span style="float:left;padding-bottom:10px;"><b><a href="?lib=<?php echo $pageposts[$i]->id;?>"><?php echo $pageposts[$i]->title; ?></a></b></span><br/>

	 <a href="?lib=<?php echo $pageposts[$i]->id;?>"><img src="<?php echo plugins_url('lib_upload/admin/uploads/'.$pageposts[$i]->coverfile);?>"></a> <br/>
	 Author : <?php echo $pageposts[$i]->author;?><br/> 	

</div>

<?php
}
else
{
	 ?>
<div class="square">
<span style="float:left;padding-bottom:10px;"><b><a href="?lib=<?php echo $pageposts[$i]->id;?>"><?php echo $pageposts[$i]->title; ?></a></b></span><br/>

	 <a href="?lib=<?php echo $pageposts[$i]->id;?>"><video width="271" height="271" src="<?php echo plugins_url('lib_upload/admin/uploads/'.$pageposts[$i]->file);?>"></a> <br/>
	 

</div>

<?php	 
}	
}

?>

</div>


<?php
}
if(isset($_REQUEST['lib']))
{
	
	$id = $_REQUEST['lib'];
		global $wpdb;
		$querystr = "SELECT * from wp_lib where id='".$id."'";
		
		$book_pdf = $wpdb->get_results($querystr, OBJECT);
		
	?>
	<div class="bookpdf">
	<iframe width="100%" height="1000" src="<?php echo plugins_url('lib_upload/admin/uploads/'.$book_pdf[0]->file);?>"></iframe></div>	<?php
	
}
	
?>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->
