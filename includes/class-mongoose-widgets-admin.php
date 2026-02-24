<?php
/**
 * Admin settings page — toggle widgets on/off.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mongoose_Widgets_Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
        add_action( 'admin_init', [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    public function add_menu_page() {
        add_menu_page(
            __( 'Mongoose Widgets', 'mongoose-widgets' ),
            __( 'Mongoose Widgets', 'mongoose-widgets' ),
            'manage_options',
            'mongoose-widgets',
            [ $this, 'render_page' ],
            'dashicons-screenoptions',
            59
        );
    }

    public function register_settings() {
        register_setting( 'mongoose_widgets_settings', 'mongoose_widgets_enabled', [
            'type'              => 'array',
            'sanitize_callback' => [ $this, 'sanitize_enabled' ],
            'default'           => null,
        ] );
    }

    public function sanitize_enabled( $value ) {
        if ( ! is_array( $value ) ) {
            return [];
        }
        $valid = array_keys( Mongoose_Widgets_Registry::get_widgets() );
        return array_values( array_intersect( $value, $valid ) );
    }

    public function enqueue_admin_assets( $hook ) {
        if ( 'toplevel_page_mongoose-widgets' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'mongoose-widgets-admin',
            MONGOOSE_WIDGETS_URL . 'assets/css/admin.css',
            [],
            MONGOOSE_WIDGETS_VERSION
        );
    }

    public function render_page() {
        $widgets = Mongoose_Widgets_Registry::get_widgets();
        $enabled = Mongoose_Widgets::get_enabled_widgets();
        ?>
        <div class="wrap mw-admin-wrap">
            <h1><?php esc_html_e( 'Mongoose Widgets', 'mongoose-widgets' ); ?></h1>
            <p class="mw-admin-description"><?php esc_html_e( 'Enable or disable individual widgets. Disabled widgets will not appear in the Elementor editor and their assets will not be loaded.', 'mongoose-widgets' ); ?></p>

            <form method="post" action="options.php">
                <?php settings_fields( 'mongoose_widgets_settings' ); ?>

                <!-- Hidden field ensures an empty array is saved when nothing is checked -->
                <input type="hidden" name="mongoose_widgets_enabled[]" value="">

                <div class="mw-widget-grid">
                    <?php foreach ( $widgets as $slug => $widget ) :
                        $is_enabled = in_array( $slug, $enabled, true );
                    ?>
                        <div class="mw-widget-card <?php echo $is_enabled ? 'mw-widget-card--active' : ''; ?>">
                            <div class="mw-widget-card__header">
                                <span class="mw-widget-card__icon <?php echo esc_attr( $widget['icon'] ); ?>"></span>
                                <label class="mw-toggle">
                                    <input type="checkbox"
                                           name="mongoose_widgets_enabled[]"
                                           value="<?php echo esc_attr( $slug ); ?>"
                                           <?php checked( $is_enabled ); ?>>
                                    <span class="mw-toggle__slider"></span>
                                </label>
                            </div>
                            <h3 class="mw-widget-card__title"><?php echo esc_html( $widget['label'] ); ?></h3>
                            <p class="mw-widget-card__desc"><?php echo esc_html( $widget['description'] ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php submit_button( __( 'Save Changes', 'mongoose-widgets' ) ); ?>
            </form>
        </div>
        <?php
    }
}
