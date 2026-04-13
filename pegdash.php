<?php
/**
 * Plugin Name: Pensar BIG Dashboard
 * Description: Dashboard Premium para administrar campañas de anuncios. Módulo Front-End interactivo con BD SQL interna.
 * Version: 2.0.0
 * Author: Antigravity Code
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Variables de entorno de WP
define('PEGDASH_PLUGIN_URL', untrailingslashit(plugin_dir_url(__FILE__)));
define('PEGDASH_PLUGIN_DIR', untrailingslashit(plugin_dir_path(__FILE__)));

// Incluimos las clases del backend SQL y la interfaz AJAX nativa
require_once PEGDASH_PLUGIN_DIR . '/includes/class-pegdash-db.php';
require_once PEGDASH_PLUGIN_DIR . '/includes/class-pegdash-ajax.php';

// Hook para crear las tablas automáticamente cuando se active el plugin
register_activation_hook(__FILE__, array('PegDash_DB', 'create_tables'));

class PegDash_Plugin {

    public function __construct() {
        // Enlazar los Hooks de WordPress
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));

        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_shortcode('pegdash', array($this, 'render_shortcode'));
        add_filter('the_content', array($this, 'auto_render_dashboard'));

        // Iniciamos el receptor de AJAX PHP Nativo
        new PegDash_Ajax();
    }

    public function add_admin_menu() {
        add_menu_page(
            'Configuración Pensar BIG',
            'Pensar BIG',
            'manage_options',
            'pegdash-settings',
            array($this, 'render_settings_page'),
            'dashicons-chart-pie',
            30
        );
    }

    public function register_settings() {
        register_setting('pegdash_options_group', 'pegdash_dashboard_page_id');
        register_setting('pegdash_options_group', 'pegdash_allowed_role');
    }

    public function render_settings_page() {
        if (!function_exists('wp_roles')) {
            require_once ABSPATH . 'wp-includes/capabilities.php';
        }
        $wp_roles = wp_roles();
        $all_roles = $wp_roles->roles;
        ?>
        <div class="wrap">
            <h1>Configuración de Pensar BIG (Local DB AJAX)</h1>
            <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-left: 4px solid #ff5100; margin-top: 20px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <form method="post" action="options.php">
                    <?php
                        settings_fields('pegdash_options_group');
                        do_settings_sections('pegdash_options_group');
                        $selected_page = get_option('pegdash_dashboard_page_id');
                        $selected_role = get_option('pegdash_allowed_role');
                    ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row">Página de Destino:</th>
                            <td>
                                <?php
                                wp_dropdown_pages(array(
                                    'name' => 'pegdash_dashboard_page_id',
                                    'show_option_none' => '-- Ninguna --',
                                    'option_none_value' => '0',
                                    'selected' => $selected_page
                                ));
                                ?>
                                <p class="description">Selecciona la página en el <strong>front-end</strong> donde se inyectará automáticamente.</p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Perfil de Acceso:</th>
                            <td>
                                <select name="pegdash_allowed_role">
                                    <option value="" <?php selected($selected_role, ''); ?>>-- Únicamente Administradores --</option>
                                    <?php foreach ($all_roles as $role_key => $role_details) : ?>
                                        <?php if ($role_key === 'administrator') continue; ?>
                                        <option value="<?php echo esc_attr($role_key); ?>" <?php selected($selected_role, $role_key); ?>>
                                            <?php echo translate_user_role($role_details['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Shortcode Manual:</th>
                            <td>
                                <input type="text" value="[pegdash]" readonly style="background: #f0f0f1; font-family: monospace; padding: 5px; width: 100px; text-align: center;">
                            </td>
                        </tr>
                    </table>
                    <?php submit_button('Guardar Configuración WP BD'); ?>
                </form>
            </div>
        </div>
        <?php
    }

    public function has_dashboard_access() {
        if ( !is_user_logged_in() ) return false;
        if ( current_user_can('manage_options') ) return true;

        $allowed_role = get_option('pegdash_allowed_role');
        $user = wp_get_current_user();
        if ( !empty($allowed_role) && in_array( $allowed_role, (array) $user->roles ) ) {
            return true;
        }
        return false;
    }

    public function enqueue_frontend_assets() {
        global $post;
        $selected_page = get_option('pegdash_dashboard_page_id');
        
        $is_selected_page = ( is_page($selected_page) && !empty($selected_page) && $selected_page != '0' );
        $has_shortcode = ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'pegdash' ) );

        if ( ($is_selected_page || $has_shortcode) && $this->has_dashboard_access() ) {
            wp_enqueue_script('tailwindcss', 'https://cdn.tailwindcss.com', array(), null, false);
            wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', array(), null, false);
            wp_enqueue_script('lucide', 'https://unpkg.com/lucide@latest', array(), null, false);

            wp_enqueue_style('pegdash-style', PEGDASH_PLUGIN_URL . '/assets/css/style.css', array(), time());
            
            // Enqueue main app script
            wp_enqueue_script('pegdash-app', PEGDASH_PLUGIN_URL . '/assets/js/app.js', array(), time(), true);

            // IMPORTANTE: Pasamos las URLs de nuestra AJAX y el Nonce (token de validacion de WP) al JS
            wp_localize_script('pegdash-app', 'pegDashVars', array(
                'ajaxUrl' => esc_url_raw(admin_url('admin-ajax.php')),
                'nonce'   => wp_create_nonce('pegdash_nonce')
            ));
        }
    }

    public function render_shortcode($atts) {
        if ( !$this->has_dashboard_access() ) {
            return '<div style="padding: 40px; text-align: center; background: #000; color: #fff; border: 1px solid #333; border-radius: 20px; font-family: sans-serif;">
                        <h3 style="color:#ff5100; margin-top: 0;">Error 403: Acceso Restringido</h3>
                        <p>No tienes los permisos para visualizar este Dashboard.</p>
                    </div>';
        }

        ob_start();
        echo '<script>
            tailwind.config = {
                corePlugins: {
                    preflight: false,
                }
            }
        </script>';
        include PEGDASH_PLUGIN_DIR . '/views/dashboard.php';
        return ob_get_clean();
    }

    public function auto_render_dashboard($content) {
        $selected_page = get_option('pegdash_dashboard_page_id');
        
        if ( is_page($selected_page) && !empty($selected_page) && $selected_page != '0' && in_the_loop() && is_main_query() ) {
            if ( !has_shortcode($content, 'pegdash') ) {
                $content .= reset( $this->render_shortcode(array()) );
            }
        }
        
        return $content;
    }
}

new PegDash_Plugin();
