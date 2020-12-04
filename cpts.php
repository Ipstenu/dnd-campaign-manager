<?php
/*
 * Custom Post Types
 *
 * @since 1.0
 */

/**
 * class DNDCM_CPTs
 */
class DNDCM_CPTs {

	protected static $all_post_types;

	/**
	 * Constructor
	 */
	public function __construct() {

		// Define show taxonomies
		self::$all_post_types = array(
			'post_type_character'   => array(
				'name' => 'character',
				'icon' => 'dashicons-businesswoman',
				'css'  => '\\f12f',
			),
			'post_type_item'   => array(
				'name' => 'item',
				'icon' => 'dashicons-coffee',
				'css'  => '\\f16f',
			),
			'post_type_location'   => array(
				'name' => 'location',
				'icon' => 'dashicons-admin-site-alt2',
				'css'  => '\\f11e',
			),
			'post_type_note'   => array(
				'name' => 'note',
				'icon' => 'dashicons-clipboard',
				'css'  => '\\f481',
			),
		);

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'init', array( $this, 'create_post_type' ), 0 );
		add_action( 'init', array( $this, 'create_taxonomies' ), 0 );
	}

	/**
	 * Admin Init
	 */
	public function admin_init() {
		add_action( 'admin_head', array( $this, 'admin_css' ) );
		add_action( 'dashboard_glance_items', array( $this, 'dashboard_glance_items' ) );
	}

	/*
	 * CPT Settings
	 *
	 */
	public function create_post_type() {

		foreach ( self::$all_post_types as $post_type => $pt_data ) {

			$labels = array(
				'name'                     => ucfirst( $pt_data['name'] ),
				'singular_name'            => ucfirst( $pt_data['name'] ),
				'menu_name'                => ucfirst( $pt_data['name'] ) . 's',
				'add_new_item'             => 'Add New ' . ucfirst( $pt_data['name'] ),
				'edit_item'                => 'Edit ' . ucfirst( $pt_data['name'] ),
				'new_item'                 => 'New ' . ucfirst( $pt_data['name'] ),
				'view_item'                => 'View ' . ucfirst( $pt_data['name'] ),
				'all_items'                => 'All ' . ucfirst( $pt_data['name'] ) . 's',
				'search_items'             => 'Search ' . ucfirst( $pt_data['name'] ) . 's',
				'not_found'                => 'No ' . ucfirst( $pt_data['name'] ) . 's found',
				'not_found_in_trash'       => 'No ' . ucfirst( $pt_data['name'] ) . 's found in Trash',
				'update_item'              => 'Update ' . ucfirst( $pt_data['name'] ),
				'featured_image'           => ucfirst( $pt_data['name'] ) . ' Image',
				'set_featured_image'       => 'Set ' . ucfirst( $pt_data['name'] ) . ' image',
				'remove_featured_image'    => 'Remove ' . ucfirst( $pt_data['name'] ) . ' image',
				'use_featured_image'       => 'Use as ' . ucfirst( $pt_data['name'] ) . ' image',
				'archives'                 => ucfirst( $pt_data['name'] ) . ' archives',
				'insert_into_item'         => 'Insert into ' . ucfirst( $pt_data['name'] ),
				'uploaded_to_this_item'    => 'Uploaded to this ' . ucfirst( $pt_data['name'] ),
				'filter_items_list'        => 'Filter ' . ucfirst( $pt_data['name'] ) . ' list',
				'items_list_navigation'    => ucfirst( $pt_data['name'] ) . ' list navigation',
				'items_list'               => ucfirst( $pt_data['name'] ) . ' list',
				'item_published'           => ucfirst( $pt_data['name'] ) . ' published.',
				'item_published_privately' => ucfirst( $pt_data['name'] ) . ' published privately.',
				'item_reverted_to_draft'   => ucfirst( $pt_data['name'] ) . ' reverted to draft.',
				'item_scheduled'           => ucfirst( $pt_data['name'] ) . ' scheduled.',
				'item_updated'             => ucfirst( $pt_data['name'] ) . ' updated.',
			);
			$args   = array(
				'label'               => $post_type,
				'description'         => ucfirst( $pt_data['name'] ),
				'labels'              => $labels,
				'public'              => true,
				'show_in_rest'        => true,
				'rest_base'           => $pt_data['name'],
				'menu_position'       => 6,
				'menu_icon'           => $pt_data['icon'],
				'hierarchical'        => true,
				'supports'            => array( 'title', 'excerpt', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
				'has_archive'         => $pt_data['name'] . 's',
				'capability_type'     => 'page',
				'rewrite'             => array( 'slug' => $pt_data['name'] ),
				'delete_with_user'    => false,
				'exclude_from_search' => false,
			);
			register_post_type( $post_type, $args );
		}
	}

	/*
	 * Custom Taxonomies
	 */
	public function create_taxonomies() {

		// Labels for taxonomy
		$labels = array(
			'name'                       => 'Tag',
			'singular_name'              => 'Tag',
			'search_items'               => 'Search Tags',
			'popular_items'              => 'Popular Tags',
			'all_items'                  => 'All Tags',
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => 'Edit Tag',
			'update_item'                => 'Update Tag',
			'add_new_item'               => 'Add New Tag',
			'new_item_name'              => 'New Tag Name',
			'separate_items_with_commas' => 'Separate Tags  with commas',
			'add_or_remove_items'        => 'Add or remove Tags',
			'choose_from_most_used'      => 'Choose from the most used Tags',
			'not_found'                  => 'No Tags found.',
			'menu_name'                  => 'Tags',
		);

		//parameters for the new taxonomy
		$arguments = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_in_rest'          => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'show_in_nav_menus'     => true,
			'rewrite'               => array( 'slug' => $pt_data['name'] . '-tags' ),
		);

		foreach ( self::$all_post_types as $post_type => $pt_data ) {
			// Register taxonomy
			register_taxonomy( $pt_data['name'] . '_tags', $post_type, $arguments );
		}
	}

	/*
	 * Add to 'Right Now'
	 */
	public function dashboard_glance_items() {

		foreach ( self::$all_post_types as $post_type => $pt_data ) {
			foreach ( array( 'post_type_' . $pt_data['name'] ) as $post_type ) {
				$num_posts = wp_count_posts( $post_type );
				if ( $num_posts && $num_posts->publish ) {
					if ( 'post_type_' . $pt_data['name'] === $post_type ) {
						// translators: %s is the number of Posts in that type
						$text = _n( '%s ' . ucfirst( $pt_data['name'] ), '%s ' . ucfirst( $pt_data['name'] ) . 's', $num_posts->publish );
					}
					$text = sprintf( $text, number_format_i18n( $num_posts->publish ) );
					printf( '<li class="%1$s-count"><a href="edit.php?post_type=%1$s">%2$s</a></li>', esc_attr( $post_type ), esc_html( $text ) );
				}
			}
		}
	}

	/*
	 * Style for dashboard
	 */
	public function admin_css() {
		foreach ( self::$all_post_types as $post_type => $pt_data ) {
			echo "<style type='text/css'>
				#adminmenu #menu-posts-" . $post_type . " div.wp-menu-image:before, #dashboard_right_now li." . $post_type . "-count a:before {
					content: '" . $pt_data['css'] . "';
					margin-left: -1px;
				}
			</style>";
		}
	}

}

new DNDCM_CPTs();
