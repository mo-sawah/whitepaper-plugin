<?php
/**
 * Plugin Name:       Whitepaper Custom Post Type
 * Description:       Adds a custom post type for "Whitepapers" with custom fields and a download box shortcode.
 * Version:           2.3.0
 * Author:            Mohamed Sawah
 * Author URI:        https://sawahsolutions.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       whitepaper-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// =============================================================================
// 1. PLUGIN ACTIVATION (DATABASE TABLE CREATION)
// =============================================================================
function whp_activate_plugin() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'whitepaper_leads';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        submission_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        email varchar(100) NOT NULL,
        whitepaper_id bigint(20) UNSIGNED NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'whp_activate_plugin' );

// =============================================================================
// 2. REGISTER CPT AND TAXONOMIES
// =============================================================================
function whp_register_post_type() {
    $labels = array('name' => _x( 'Whitepapers', 'Post Type General Name', 'whitepaper-plugin' ), 'singular_name' => _x( 'Whitepaper', 'Post Type Singular Name', 'whitepaper-plugin' ), 'menu_name' => __( 'Whitepapers', 'whitepaper-plugin' ), 'name_admin_bar' => __( 'Whitepaper', 'whitepaper-plugin' ), 'archives' => __( 'Whitepaper Archives', 'whitepaper-plugin' ), 'attributes' => __( 'Whitepaper Attributes', 'whitepaper-plugin' ), 'parent_item_colon' => __( 'Parent Whitepaper:', 'whitepaper-plugin' ), 'all_items' => __( 'All Whitepapers', 'whitepaper-plugin' ), 'add_new_item' => __( 'Add New Whitepaper', 'whitepaper-plugin' ), 'add_new' => __( 'Add New', 'whitepaper-plugin' ), 'new_item' => __( 'New Whitepaper', 'whitepaper-plugin' ), 'edit_item' => __( 'Edit Whitepaper', 'whitepaper-plugin' ), 'update_item' => __( 'Update Whitepaper', 'whitepaper-plugin' ), 'view_item' => __( 'View Whitepaper', 'whitepaper-plugin' ), 'view_items' => __( 'View Whitepapers', 'whitepaper-plugin' ), 'search_items' => __( 'Search Whitepaper', 'whitepaper-plugin' ));
    $args = array('label' => __( 'Whitepaper', 'whitepaper-plugin' ), 'description' => __( 'For company whitepapers and reports.', 'whitepaper-plugin' ), 'labels' => $labels, 'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ), 'taxonomies' => array( 'whitepaper_category', 'whitepaper_topic' ), 'hierarchical' => false, 'public' => true, 'show_ui' => true, 'show_in_menu' => true, 'menu_position' => 20, 'menu_icon' => 'dashicons-media-document', 'show_in_admin_bar' => true, 'show_in_nav_menus' => true, 'can_export' => true, 'has_archive' => true, 'exclude_from_search' => false, 'publicly_queryable' => true, 'capability_type' => 'post', 'show_in_rest' => true);
    register_post_type( 'whitepaper', $args );
}
add_action( 'init', 'whp_register_post_type', 0 );

function whp_register_taxonomies() {
    $category_labels = array('name' => _x( 'Categories', 'taxonomy general name', 'whitepaper-plugin' ), 'singular_name' => _x( 'Category', 'taxonomy singular name', 'whitepaper-plugin' ), 'search_items' => __( 'Search Categories', 'whitepaper-plugin' ), 'all_items' => __( 'All Categories', 'whitepaper-plugin' ), 'parent_item' => __( 'Parent Category', 'whitepaper-plugin' ), 'parent_item_colon' => __( 'Parent Category:', 'whitepaper-plugin' ), 'edit_item' => __( 'Edit Category', 'whitepaper-plugin' ), 'update_item' => __( 'Update Category', 'whitepaper-plugin' ), 'add_new_item' => __( 'Add New Category', 'whitepaper-plugin' ), 'new_item_name' => __( 'New Category Name', 'whitepaper-plugin' ), 'menu_name' => __( 'Categories', 'whitepaper-plugin' ));
    $category_args = array('hierarchical' => true, 'labels' => $category_labels, 'show_ui' => true, 'show_admin_column' => true, 'query_var' => true, 'show_in_rest' => true, 'rewrite' => array( 'slug' => 'whitepaper-category' ));
    register_taxonomy( 'whitepaper_category', array( 'whitepaper' ), $category_args );
    $topic_labels = array('name' => _x( 'Topics', 'taxonomy general name', 'whitepaper-plugin' ), 'singular_name' => _x( 'Topic', 'taxonomy singular name', 'whitepaper-plugin' ), 'search_items' => __( 'Search Topics', 'whitepaper-plugin' ), 'popular_items' => __( 'Popular Topics', 'whitepaper-plugin' ), 'all_items' => __( 'All Topics', 'whitepaper-plugin' ), 'edit_item' => __( 'Edit Topic', 'whitepaper-plugin' ), 'update_item' => __( 'Update Topic', 'whitepaper-plugin' ), 'add_new_item' => __( 'Add New Topic', 'whitepaper-plugin' ), 'new_item_name' => __( 'New Topic Name', 'whitepaper-plugin' ), 'separate_items_with_commas' => __( 'Separate topics with commas', 'whitepaper-plugin' ), 'add_or_remove_items' => __( 'Add or remove topics', 'whitepaper-plugin' ), 'choose_from_most_used' => __( 'Choose from the most used topics', 'whitepaper-plugin' ), 'not_found' => __( 'No topics found.', 'whitepaper-plugin' ), 'menu_name' => __( 'Topics', 'whitepaper-plugin' ));
    $topic_args = array('hierarchical' => false, 'labels' => $topic_labels, 'show_ui' => true, 'show_admin_column' => true, 'query_var' => true, 'show_in_rest' => true, 'rewrite' => array( 'slug' => 'whitepaper-topic' ));
    register_taxonomy( 'whitepaper_topic', array( 'whitepaper' ), $topic_args );
}
add_action( 'init', 'whp_register_taxonomies' );

// =============================================================================
// 3. CUSTOM FIELDS (META BOX)
// =============================================================================
function whp_add_meta_box() {
    add_meta_box('whp_details', 'Whitepaper Details', 'whp_meta_box_callback', 'whitepaper', 'normal', 'high');
}
add_action( 'add_meta_boxes', 'whp_add_meta_box' );

function whp_meta_box_callback( $post ) {
    wp_nonce_field( 'whp_save_meta_box_data', 'whp_meta_box_nonce' );
    $publisher = get_post_meta( $post->ID, '_whp_publisher', true );
    $publish_date = get_post_meta( $post->ID, '_whp_publish_date', true );
    $language = get_post_meta( $post->ID, '_whp_language', true );
    $type = get_post_meta( $post->ID, '_whp_type', true );
    $length = get_post_meta( $post->ID, '_whp_length', true );
    $download_link = get_post_meta( $post->ID, '_whp_download_link', true );
    ?>
    <style>.whp-meta-box table{width:100%}.whp-meta-box table td{padding:10px 5px}.whp-meta-box table input,.whp-meta-box table select{width:100%}.whp-meta-box label{font-weight:700}.whp-file-uploader{display:flex;align-items:center;gap:10px}</style>
    <div class="whp-meta-box">
        <p>Fill in the details for this whitepaper. These fields can be used in your Elementor template.</p>
        <table>
            <tr><td><label for="whp_publisher">Publisher</label></td><td><input type="text" id="whp_publisher" name="whp_publisher" value="<?php echo esc_attr( $publisher ); ?>" placeholder="e.g., Proofpoint"></td></tr>
            <tr><td><label for="whp_publish_date">Publish Date</label></td><td><input type="date" id="whp_publish_date" name="whp_publish_date" value="<?php echo esc_attr( $publish_date ); ?>"></td></tr>
            <tr><td><label for="whp_language">Language</label></td><td><input type="text" id="whp_language" name="whp_language" value="<?php echo esc_attr( $language ); ?>" placeholder="e.g., ENG"></td></tr>
            <tr><td><label for="whp_type">Type</label></td><td><input type="text" id="whp_type" name="whp_type" value="<?php echo esc_attr( $type ); ?>" placeholder="e.g., Whitepaper"></td></tr>
            <tr><td><label for="whp_length">Length</label></td><td><input type="text" id="whp_length" name="whp_length" value="<?php echo esc_attr( $length ); ?>" placeholder="e.g., 19 pages"></td></tr>
            <tr>
                <td><label for="whp_download_link">File</label></td>
                <td>
                    <div class="whp-file-uploader">
                        <input type="text" id="whp_download_link" name="whp_download_link" value="<?php echo esc_url( $download_link ); ?>" style="flex-grow: 1;" placeholder="Select or upload a file, or paste a URL">
                        <button type="button" class="button" id="whp_upload_file_button">Upload File</button>
                        <button type="button" class="button button-secondary" id="whp_remove_file_button" style="<?php echo empty($download_link) ? 'display:none;' : ''; ?>">Remove File</button>
                    </div>
                    <p class="description">Upload the whitepaper file or paste the direct download link.</p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

function whp_save_meta_data( $post_id ) {
    if ( ! isset( $_POST['whp_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['whp_meta_box_nonce'], 'whp_save_meta_box_data' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( !isset($_POST['post_type']) || 'whitepaper' !== $_POST['post_type'] ) return;
    $fields = ['whp_publisher', 'whp_publish_date', 'whp_language', 'whp_type', 'whp_length', 'whp_download_link'];
    foreach ( $fields as $field ) {
        if ( isset( $_POST[$field] ) ) {
            $value = ($field === 'whp_download_link') ? esc_url_raw($_POST[$field]) : sanitize_text_field( $_POST[$field] );
            update_post_meta( $post_id, '_' . $field, $value );
        }
    }
}
add_action( 'save_post', 'whp_save_meta_data' );

// =============================================================================
// 4. SHORTCODES
// =============================================================================
function whp_register_shortcodes() {
    add_shortcode( 'whitepaper_download_box', 'whp_download_box_shortcode_callback' );
    add_shortcode( 'whitepaper_content', 'whp_content_shortcode_callback' );
}
add_action( 'init', 'whp_register_shortcodes' );

function whp_content_shortcode_callback() {
    if ( is_singular( 'whitepaper' ) ) {
        $content = get_post_field( 'post_content', get_the_ID() );
        return '<div class="whp-formatted-content">' . apply_filters( 'the_content', $content ) . '</div>';
    }
    return '';
}

function whp_download_box_shortcode_callback() {
    if ( is_admin() || ! is_singular( 'whitepaper' ) ) return '';
    $post_id = get_the_ID();
    $download_link = get_post_meta( $post_id, '_whp_download_link', true );
    if ( empty( $download_link ) ) return '<!-- Whitepaper download link not set. -->';
    
    wp_enqueue_style( 'whp-style', plugin_dir_url( __FILE__ ) . 'assets/css/whitepaper-style.css', array(), '2.3.0' );
    wp_enqueue_script( 'whp-script', plugin_dir_url( __FILE__ ) . 'assets/js/whitepaper-script.js', array( 'jquery' ), '2.3.0', true );
    wp_localize_script( 'whp-script', 'whp_ajax_object', array('ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'whp_lead_nonce' ), 'post_id' => $post_id));
    
    $color_options = get_option('whp_color_settings');
    $content_options = get_option('whp_content_settings');

    $bg_color = !empty($color_options['background_color']) ? $color_options['background_color'] : '#002d56';
    $text_color = !empty($color_options['text_color']) ? $color_options['text_color'] : '#ffffff';
    $button_color = !empty($color_options['button_color']) ? $color_options['button_color'] : '#0073e6';
    $button_text_color = !empty($color_options['button_text_color']) ? $color_options['button_text_color'] : '#ffffff';
    $link_color = !empty($color_options['link_color']) ? $color_options['link_color'] : '#a9d4ff';

    $privacy_url = !empty($content_options['privacy_policy_url']) ? esc_url($content_options['privacy_policy_url']) : '#';
    $terms_url = !empty($content_options['terms_of_use_url']) ? esc_url($content_options['terms_of_use_url']) : '#';
    $protection_email = !empty($content_options['data_protection_email']) ? sanitize_email($content_options['data_protection_email']) : '';

    ob_start();
    ?>
    <style>
        :root {
            --whp-bg-color:<?php echo esc_attr($bg_color); ?>;
            --whp-text-color:<?php echo esc_attr($text_color); ?>;
            --whp-button-color:<?php echo esc_attr($button_color); ?>;
            --whp-button-text-color:<?php echo esc_attr($button_text_color); ?>;
            --whp-link-color:<?php echo esc_attr($link_color); ?>;
        }
    </style>
    <div id="whp-download-box" class="whp-download-box" data-download-url="<?php echo esc_url($download_link); ?>">
        <div class="whp-box-header">
            <h3>Download Now</h3>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        </div>
        <p class="whp-required-text">Required fields*</p>
        <div id="whp-form-container">
            <form id="whp-email-form" class="whp-email-form">
                <input type="email" id="whp_work_email" name="whp_work_email" placeholder="Enter work email" required>
                <button type="submit">Submit</button>
            </form>
            <div class="whp-checkbox-container">
                <input type="checkbox" id="whp_agree" name="whp_agree" required>
                <label for="whp_agree">By submitting this form you agree to Fuse Squared contacting you with marketing-related emails or by telephone. You may unsubscribe at any time. ProofPoint web sites and communications are subject to their&nbsp;<a href="<?php echo $privacy_url; ?>" target="_blank">Privacy Notice</a>.</label>
            </div>
            <p class="whp-disclaimer">By requesting this resource you agree to our <a href="<?php echo $terms_url; ?>" target="_blank">terms of use</a>. All data is protected by our <a href="<?php echo $privacy_url; ?>" target="_blank">Privacy Notice</a>.<?php if ($protection_email): ?> If you have any further questions please email <a href="mailto:<?php echo antispambot($protection_email); ?>"><?php echo antispambot($protection_email); ?></a>.<?php endif; ?></p>
        </div>
        <div id="whp-loader" class="whp-loader" style="display: none;"></div>
        <div id="whp-result" class="whp-result" style="display: none;"></div>
    </div>
    <?php
    return ob_get_clean();
}

// =============================================================================
// 5. AJAX HANDLER FOR SAVING LEADS
// =============================================================================
function whp_save_lead_callback() {
    check_ajax_referer( 'whp_lead_nonce', 'nonce' );
    global $wpdb;
    $table_name = $wpdb->prefix . 'whitepaper_leads';
    $email = sanitize_email( $_POST['email'] );
    $post_id = intval( $_POST['post_id'] );
    if ( !is_email( $email ) || !$post_id ) {
        wp_send_json_error( 'Invalid data.' );
    }
    $result = $wpdb->insert($table_name, array('submission_date' => current_time( 'mysql' ), 'email' => $email, 'whitepaper_id' => $post_id));
    if ($result) {
        wp_send_json_success( 'Lead saved!' );
    } else {
        wp_send_json_error( 'Could not save lead.' );
    }
}
add_action( 'wp_ajax_whp_save_lead', 'whp_save_lead_callback' );
add_action( 'wp_ajax_nopriv_whp_save_lead', 'whp_save_lead_callback' );

// =============================================================================
// 6. ADMIN MENU & PAGES (LEADS & SETTINGS)
// =============================================================================
function whp_admin_menu() {
    add_submenu_page('edit.php?post_type=whitepaper', 'Collected Emails', 'Collected Emails', 'manage_options', 'whp-leads', 'whp_leads_page_callback');
    add_submenu_page('edit.php?post_type=whitepaper', 'Settings', 'Settings', 'manage_options', 'whp-settings', 'whp_settings_page_callback');
}
add_action( 'admin_menu', 'whp_admin_menu' );

function whp_settings_page_callback() {
    ?>
    <div class="wrap whp-settings-wrap">
        <h1>Whitepaper Settings</h1>
        <form method="post" action="options.php">
            <div class="whp-settings-container">
                <div class="whp-settings-main">
                    <?php
                        settings_fields( 'whp_settings_group' );
                        do_settings_sections( 'whp-settings-page' );
                        submit_button();
                    ?>
                </div>
                <div class="whp-settings-sidebar">
                    <div id="whp-preview-container">
                        <h2>Preview</h2>
                        <div id="whp-preview-box" class="whp-download-box">
                            <div class="whp-box-header"><h3>Download Now</h3></div>
                            <p class="whp-required-text">Required fields*</p>
                            <div class="whp-email-form">
                                <input type="email" placeholder="Enter work email" disabled>
                                <button type="button">Submit</button>
                            </div>
                            <div class="whp-checkbox-container"><label>By submitting this form you agree... <a href="#" class="whp-preview-link">Privacy Notice</a>.</label></div>
                            <p class="whp-disclaimer">By requesting this resource you agree to our <a href="#" class="whp-preview-link">terms of use</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}

function whp_settings_init() {
    // Register Color Settings
    register_setting( 'whp_settings_group', 'whp_color_settings' );
    add_settings_section( 'whp_colors_section', 'Download Box Colors', null, 'whp-settings-page' );
    $color_fields = [
        'background_color' => ['label' => 'Background Color', 'default' => '#002d56', 'description' => 'Used for the main container background.'],
        'text_color' => ['label' => 'Text Color', 'default' => '#ffffff', 'description' => 'Used for the main title and body text.'],
        'button_color' => ['label' => 'Button Color', 'default' => '#0073e6', 'description' => 'Used for the submit button background.'],
        'button_text_color' => ['label' => 'Button Text Color', 'default' => '#ffffff', 'description' => 'Used for text inside the submit button.'],
        'link_color' => ['label' => 'Link Color', 'default' => '#a9d4ff', 'description' => 'Used for all links in the form disclaimers.']
    ];
    foreach($color_fields as $id => $field){
        add_settings_field($id, $field['label'], 'whp_color_field_callback', 'whp-settings-page', 'whp_colors_section', ['id' => $id, 'default' => $field['default'], 'description' => $field['description']]);
    }

    // Register Content Settings
    register_setting( 'whp_settings_group', 'whp_content_settings' );
    add_settings_section( 'whp_content_section', 'Content & Links', null, 'whp-settings-page' );
    $content_fields = [
        'privacy_policy_url' => ['label' => 'Privacy Policy URL', 'description' => 'The destination for the "Privacy Notice" links.'],
        'terms_of_use_url' => ['label' => 'Terms of Use URL', 'description' => 'The destination for the "terms of use" link.'],
        'data_protection_email' => ['label' => 'Data Protection Email', 'description' => 'Contact email for data protection questions.']
    ];
     foreach($content_fields as $id => $field){
        add_settings_field($id, $field['label'], 'whp_text_field_callback', 'whp-settings-page', 'whp_content_section', ['id' => $id, 'description' => $field['description']]);
    }
}
add_action( 'admin_init', 'whp_settings_init' );

function whp_color_field_callback($args) {
    $options = get_option('whp_color_settings');
    $id = esc_attr($args['id']);
    $default = esc_attr($args['default']);
    $value = !empty($options[$id]) ? esc_attr($options[$id]) : $default;
    $description = esc_html($args['description']);
    echo "<input type='text' name='whp_color_settings[{$id}]' value='{$value}' class='whp-color-picker' data-default-color='{$default}'>";
    echo "<p class='description'>{$description}</p>";
}

function whp_text_field_callback($args) {
    $options = get_option('whp_content_settings');
    $id = esc_attr($args['id']);
    $value = !empty($options[$id]) ? esc_attr($options[$id]) : '';
    $description = esc_html($args['description']);
    $type = ($id === 'data_protection_email') ? 'email' : 'url';
    echo "<input type='{$type}' name='whp_content_settings[{$id}]' value='{$value}' class='regular-text'>";
    echo "<p class='description'>{$description}</p>";
}


function whp_enqueue_admin_scripts( $hook_suffix ) {
    $screen = get_current_screen();
    if ( 'whitepapers_page_whp-settings' === $hook_suffix ) {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'whp-admin-script', plugin_dir_url( __FILE__ ) . 'assets/js/whitepaper-admin.js', array( 'wp-color-picker' ), '2.3.0', true );
    }
    if ( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) {
        if ( isset($screen->post_type) && 'whitepaper' == $screen->post_type ) {
            wp_enqueue_media();
            wp_enqueue_script( 'whp-post-edit-script', plugin_dir_url( __FILE__ ) . 'assets/js/whitepaper-post-edit.js', array( 'jquery' ), '2.3.0', true );
        }
    }
}
add_action( 'admin_enqueue_scripts', 'whp_enqueue_admin_scripts' );

function whp_admin_settings_page_styles() {
    $screen = get_current_screen();
    if ( 'whitepapers_page_whp-settings' !== $screen->id ) return;
    ?>
    <style>
        .whp-settings-container { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        .whp-settings-main .form-table { margin-top: 0; }
        .whp-settings-main h2 { padding-bottom: 10px; border-bottom: 1px solid #c3c4c7; }
        #whp-preview-container { background: #f6f7f7; padding: 20px; border-radius: 4px; }
        #whp-preview-container h2 { margin-top: 0; }
        #whp-preview-box { transition: all 0.3s ease; }
        .form-table th { padding: 20px 10px 20px 0; width: 150px; }
        .form-table td { padding: 15px 10px; }
        .whp-color-picker { width: 100px; }
        .wp-picker-container .wp-color-result.button { min-height: 40px; }
        .wp-picker-container .wp-color-result-text { line-height: 38px; }
    </style>
    <?php
}
add_action('admin_head', 'whp_admin_settings_page_styles');

function whp_leads_page_callback() {
    $leads_table = new WHP_Leads_List_Table();
    $leads_table->prepare_items();
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Collected Emails</h1>
        <a href="<?php echo admin_url('edit.php?post_type=whitepaper&page=whp-leads&action=export_csv'); ?>" class="page-title-action">Export to CSV</a>
        <form method="post">
            <?php $leads_table->display(); ?>
        </form>
    </div>
    <?php
}

// =============================================================================
// 7. WP_LIST_TABLE FOR LEADS
// =============================================================================
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WHP_Leads_List_Table extends WP_List_Table {
    public function get_columns() {
        return ['cb' => '<input type="checkbox" />', 'email' => 'Email', 'whitepaper_title' => 'Whitepaper', 'submission_date' => 'Date'];
    }
    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'whitepaper_leads';
        $per_page = 20;
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        $orderby = !empty($_REQUEST['orderby']) ? esc_sql($_REQUEST['orderby']) : 'submission_date';
        $order = !empty($_REQUEST['order']) ? esc_sql($_REQUEST['order']) : 'DESC';
        $query = "SELECT l.id, l.email, l.submission_date, p.post_title as whitepaper_title FROM $table_name l LEFT JOIN {$wpdb->posts} p ON l.whitepaper_id = p.ID ORDER BY $orderby $order";
        $total_items = $wpdb->query($query);
        $current_page = $this->get_pagenum();
        $this->set_pagination_args(['total_items' => $total_items, 'per_page' => $per_page]);
        $paged_query = $query . " LIMIT " . (($current_page - 1) * $per_page) . ", $per_page";
        $this->items = $wpdb->get_results($paged_query, ARRAY_A);
    }
    public function column_default( $item, $column_name ) {
        return isset($item[$column_name]) ? esc_html($item[$column_name]) : 'N/A';
    }
    public function column_submission_date( $item ) {
        return date_i18n( 'j F Y', strtotime( $item['submission_date'] ) );
    }
    protected function get_sortable_columns() {
        return ['email' => ['email', false], 'submission_date' => ['submission_date', true]];
    }
}

// =============================================================================
// 8. EXPORT TO CSV
// =============================================================================
function whp_export_leads_to_csv() {
    if (isset($_GET['page']) && $_GET['page'] == 'whp-leads' && isset($_GET['action']) && $_GET['action'] == 'export_csv') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'whitepaper_leads';
        $results = $wpdb->get_results("SELECT l.email, p.post_title, l.submission_date FROM $table_name l LEFT JOIN {$wpdb->posts} p ON l.whitepaper_id = p.ID ORDER BY l.submission_date DESC", ARRAY_A);
        if ($results) {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=whitepaper-leads-' . date('Y-m-d') . '.csv');
            $output = fopen('php://output', 'w');
            fputcsv($output, array('Email', 'Whitepaper Title', 'Submission Date'));
            foreach ($results as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
            exit();
        }
    }
}
add_action('admin_init', 'whp_export_leads_to_csv');

