<?php
/**
 * harut functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package harut
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function harut_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on harut, use a find and replace
		* to change 'harut' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'harut', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'harut' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'harut_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'harut_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function harut_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'harut_content_width', 640 );
}
add_action( 'after_setup_theme', 'harut_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function harut_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'harut' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'harut' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'harut_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function harut_scripts() {
	wp_enqueue_style( 'harut-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'harut-style', 'rtl', 'replace' );

	wp_enqueue_script( 'harut-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'harut_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}




/********************************************************************************/
 
// Подклучаим api для WP
function enqueue_wp_api_script() {
    wp_enqueue_script('wp-api');
}
add_action('wp_enqueue_scripts', 'enqueue_wp_api_script');



//Action для Токена 
add_action('rest_api_init', function () {

    register_rest_route('custom/v1', '/get-token', [
        'methods' => 'GET',
        'callback' => function () {
            $username = 'wp_harut';
            $password = '*m0KUkquh3dHoTqr)b';

            $response = wp_remote_post(get_rest_url() . 'jwt-auth/v1/token', [
                'body' => [
                    'username' => $username,
                    'password' => $password,
                ],
            ]);

            $body = wp_remote_retrieve_body($response);

            if (wp_remote_retrieve_response_code($response) === 200) {
                return json_decode($body);
            } else {
                return new WP_Error('token_error', 'Ошибка получения токена', ['status' => 403]);
            }
        },
        'permission_callback' => '__return_true',
    ]);


});











// WPDB Create Table создаем таблицу 
function ajax_create_table() {
	global $wpdb;

	$table_name = $wpdb->prefix . $_POST['name'];

	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
	    id mediumint(9) NOT NULL AUTO_INCREMENT,
	    column1 varchar(255) NOT NULL,
	    column2 text NOT NULL,
	    PRIMARY KEY  (id)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql);

	die();
}
add_action('wp_ajax_create_table', 'ajax_create_table');
add_action('wp_ajax_nopriv_create_table', 'ajax_create_table');



// WPDB Insert row this colum
function ajax_insert_row() {

	global $wpdb;

	$colum1 = $_POST['colum1'];	
	$colum2 = $_POST['colum2'];	

	$table_name = $wpdb->prefix . 'bookings'; // замените 'my_table' на вашу таблицу

	$data = array(
	    'column1' => $colum1, // замените column1 на имя вашего столбца
	    'column2' => $colum2, // замените column2 на имя вашего столбца
	);

	$format = array('%s', '%s'); // %s для строки, %d для целого числа

	// Добавление записи в таблицу
	$wpdb->insert($table_name, $data, $format);

	// Проверка на ошибки
	if ($wpdb->last_error) {
	    echo 'Ошибка при добавлении записи: ' . $wpdb->last_error;
	} else {
	    echo 'Запись успешно добавлена';
	}	

	die();
}
add_action('wp_ajax_insert_row', 'ajax_insert_row');
add_action('wp_ajax_nopriv_insert_row', 'ajax_insert_row');






// WPDB update
function ajax_update() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'posts';

	$wpdb->update( 
	    $table_name, 
	    array( 
	        'post_title' => 'Updated Title' // Обновляемое значение
	    ),
	    array( 
	        'ID' => 1 // Условие обновления
	    ),
	    array( 
	        '%s' // Формат для 'post_title'
	    ),
	    array( 
	        '%d' // Формат для 'ID'
	    )
	);
	
	
	die();
}
add_action('wp_ajax_update', 'ajax_update');
add_action('wp_ajax_nopriv_update', 'ajax_update');




// WPDB Delete
function ajax_delete() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'posts';

	$wpdb->delete( 
	    $table_name, 
	    array( 'ID' => 1 )
	);

	die();
}

add_action('wp_ajax_delete', 'ajax_delete');
add_action('wp_ajax_nopriv_delete', 'ajax_delete');




//******** Создаем ROUT  - http://localhost/mywp/harut/wp-json/custom/v1/bookings *************//
function bookings_rest_route() {
    register_rest_route( 'custom/v1', '/bookings', array(
        'methods' => 'GET',
        'callback' => function ( $data ) {
            // Пример возвращаемого ответа. Замените на вашу логику получения данных из базы.
            global $wpdb;
            $table_name = $wpdb->prefix . 'bookings';
            $results = $wpdb->get_results( "SELECT * FROM $table_name" );

            // Проверяем, есть ли записи
            if ( empty( $results ) ) {
                return new WP_REST_Response( 'No bookings found', 404 );
            }

            // Возвращаем данные в формате JSON
            return new WP_REST_Response( $results, 200 );
        },
        'permission_callback' => '__return_true', // Для теста отключаем проверку прав доступа
    ) );
}

add_action( 'rest_api_init', 'bookings_rest_route' );