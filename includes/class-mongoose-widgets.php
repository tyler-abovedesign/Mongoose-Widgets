<?php
/**
 * Core singleton — Elementor integration, category registration, widget loading.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Mongoose_Widgets {

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function init() {
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_elementor' ] );
            return;
        }

        // Admin settings page.
        require_once MONGOOSE_WIDGETS_PATH . 'includes/class-mongoose-widgets-admin.php';
        new Mongoose_Widgets_Admin();

        // Register custom widget category.
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_categories' ] );

        // Register enabled widgets.
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

        // Register frontend assets.
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );

        // Enqueue assets in Elementor editor.
        add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_editor_assets' ] );
    }

    public function admin_notice_missing_elementor() {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php esc_html_e( 'Mongoose Widgets requires Elementor to be installed and activated.', 'mongoose-widgets' ); ?></p>
        </div>
        <?php
    }

    public function register_categories( $elements_manager ) {
        $elements_manager->add_category( 'mongoose-widgets', [
            'title' => esc_html__( 'Mongoose Widgets', 'mongoose-widgets' ),
            'icon'  => 'fa fa-plug',
        ] );
    }

    /**
     * Return the list of currently enabled widget slugs.
     */
    public static function get_enabled_widgets() {
        $all     = array_keys( Mongoose_Widgets_Registry::get_widgets() );
        $enabled = get_option( 'mongoose_widgets_enabled', null );

        // First install — all enabled by default.
        if ( is_null( $enabled ) ) {
            return $all;
        }

        return (array) $enabled;
    }

    public function register_widgets( $widgets_manager ) {
        $registry = Mongoose_Widgets_Registry::get_widgets();
        $enabled  = self::get_enabled_widgets();

        foreach ( $enabled as $slug ) {
            if ( ! isset( $registry[ $slug ] ) ) {
                continue;
            }
            $widget = $registry[ $slug ];
            require_once $widget['file'];
            $widgets_manager->register( new $widget['class']() );
        }
    }

    public function register_assets() {
        $registry = Mongoose_Widgets_Registry::get_widgets();
        $enabled  = self::get_enabled_widgets();

        foreach ( $enabled as $slug ) {
            if ( ! isset( $registry[ $slug ] ) ) {
                continue;
            }
            $widget = $registry[ $slug ];

            if ( ! empty( $widget['css'] ) ) {
                wp_register_style(
                    $widget['css']['handle'],
                    $widget['css']['src'],
                    [],
                    MONGOOSE_WIDGETS_VERSION
                );
            }

            if ( ! empty( $widget['js'] ) ) {
                wp_register_script(
                    $widget['js']['handle'],
                    $widget['js']['src'],
                    [],
                    MONGOOSE_WIDGETS_VERSION,
                    true
                );
            }
        }
    }

    public function enqueue_editor_assets() {
        $registry = Mongoose_Widgets_Registry::get_widgets();
        $enabled  = self::get_enabled_widgets();

        foreach ( $enabled as $slug ) {
            if ( ! isset( $registry[ $slug ] ) ) {
                continue;
            }
            $widget = $registry[ $slug ];

            if ( ! empty( $widget['css'] ) ) {
                wp_enqueue_style( $widget['css']['handle'] );
            }

            if ( ! empty( $widget['js'] ) ) {
                wp_enqueue_script( $widget['js']['handle'] );
            }
        }
    }
}
