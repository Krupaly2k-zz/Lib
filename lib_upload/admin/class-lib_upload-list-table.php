<?php
ob_start();
class Lib_List_Table extends Lib_upload_List_Table {
 
    /**
    * The plugin's text domain.
    * 
    * @access  private
    * @var     string  The plugin's text domain. Used for localization.
    */
    private $text_domain;
 
    /**
     * Initializes the WP_List_Table implementation.
     *
     * @param $text_domain  string  The text domain used for localizing the plugin.
     */
    public function __construct( $text_domain ) {
        parent::__construct();
 
        $this->text_domain = $text_domain;
    }
	
	/**
 * Defines the database columns shown in the table and a 
 * header for each column. The order of the columns in the 
 * table define the order in which they are rendered in the list table.
 *
 * @return array    The database columns and their headers for the table.
 */
public function get_columns() {
    return array(
		'cb'        => '<input type="checkbox" />',
        'title' => __( 'Title', $this->text_domain ),
        'category'       => __( 'Category', $this->text_domain ),
        'author'  => __( 'Author', $this->text_domain ),
        'created_at'  => __( 'Created', $this->text_domain )
    );
}

/**
 * Returns the names of columns that should be hidden from the list table.
 * 
 * @return array    The database columns that should not be shown in the table.
 */
public function get_hidden_columns() {
    return array( 'created_at' );
}
/**
 * Returns the columns that can be used for sorting the list table data. 
 * 
 * @return array    The database columns that can be used for sorting the table.
 */
public function get_sortable_columns() {
    return array(
        'title' => array( 'title', false ),
		'author' => array( 'author', false ),
        'created_at' => array( 'created_at', false )
    );
}

/**
 * Default rendering for table columns.
 *
 * @param $item         array   The database row being printed out.
 * @param $column_name  string  The column currently processed.
 * @return string       The text or HTML that should be shown for the column.
 */
function column_default( $item, $column_name ) {
	
    switch( $column_name ) {
        case 'title':
		case 'category':
            return $item[$column_name];
		case 'author':
            return $item[$column_name];
 
        case 'created_at':
            return $item[$column_name];
 
        default:
            break;
    }
 
    return '';
}


/**
 * Populates the class fields for displaying the list of licenses.
 */
public function prepare_items($search = null) {
	
    global $wpdb;
    
	$table_name = $wpdb->prefix . 'lib';
 
    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();
 
    $this->_column_headers = array( $columns, $hidden, $sortable );
	$this->process_bulk_action();
    // Pagination
    $lib_per_page = 20;
    $total_items = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name" );
 
    $offset = 0;
    if ( isset( $_REQUEST['paged'] ) ) {
        $page = max( 0, intval( $_REQUEST['paged'] ) - 1 );
        $offset = $page * $lib_per_page;
    }
 
    $this->set_pagination_args(
        array(
            'total_items' => $total_items,
            'per_page' => $lib_per_page,
            'total_pages' => ceil( $total_items / $lib_per_page )
        )
    );
 
    // Sorting
    $order_by = 'title'; // Default sort key
    if ( isset( $_REQUEST['orderby'] ) ) {
        // If the requested sort key is a valid column, use it for sorting
        if ( in_array( $_REQUEST['orderby'], array_keys( $this->get_sortable_columns() ) ) ) {
            $order_by = $_REQUEST['orderby'];
        }
    }
 
    $order = 'asc'; // Default sort order
    if ( isset( $_REQUEST['order'] ) ) {
        if ( in_array( $_REQUEST['order'], array( 'asc', 'desc' ) ) ) {
            $order = $_REQUEST['order'];
        }
    }
 
    // Do the SQL query and populate items
    $this->items = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM $table_name ORDER BY $order_by $order LIMIT %d OFFSET %d", $lib_per_page, $offset ),
        ARRAY_A );
		
	if( $search != NULL ){
       
        // Trim Search Term
        $search = trim($search);
       
        /* Notice how you can search multiple columns for your search term easily, and return one data set */
        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."table_name WHERE `col_one` LIKE '%%%s%%' OR `col_two` LIKE '%%%s%%'", $search, $search), ARRAY_A);
		$this->items = $wpdb->get_results(
        $wpdb->prepare( "SELECT * FROM $table_name WHERE `title` LIKE '%%%s%%' OR `author` LIKE '%%%s%%'", $search, $search. "LIMIT %d OFFSET %d", $lib_per_page, $offset ),
        ARRAY_A );
	
	}
	
	
}
function column_title($item) {
  $delete_nonce = wp_create_nonce( 'sp_delete_lib' );
 

 $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&book=%s">Edit</a>','wp-lib-new','edit',$item['id']),
                'delete' => sprintf( '<a href="?page=%s&action=%s&book=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce )

        );

  return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions) );
}

public function delete_book( $id ) {
	
	
  global $wpdb;

  $wpdb->delete(
    "{$wpdb->prefix}lib",
    [ 'id' => $id ],
    [ '%d' ]
  );
  echo $wpdb->last_query; exit;
}

function get_bulk_actions() {
  $actions = array(
    'delete'    => 'Delete'
  );
  return $actions;
}


function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="lib[]" value="%s" />', $item['id']
        );    
    }
	
	 function no_items() {
		_e( 'No Records found.' );
	}
	public static function delete_lib( $id ) {
		echo "k"; exit;
  global $wpdb;

  $wpdb->delete(
    "{$wpdb->prefix}lib",
    [ 'id' => $id ],
    [ '%d' ]
  );
}
	public function process_bulk_action() {
global $wpdb;
  //Detect when a bulk action is being triggered...
  if ( 'delete' === $this->current_action() ) {

    // In our file that handles the request, verify the nonce.
    $nonce = esc_attr( $_REQUEST['_wpnonce'] );
	
    if($_POST['action'] == 'delete')
	{
		
		foreach($_POST['lib'] as $id)
		{
			
			$wpdb->delete( 'wp_lib', array( 'id' => $id ), array( '%d' ) );
		}
	wp_redirect( admin_url( 'admin.php?page=wp-lib&msg=delete' ) );  
	exit;
	}
		
      
    }
	
	
}

}