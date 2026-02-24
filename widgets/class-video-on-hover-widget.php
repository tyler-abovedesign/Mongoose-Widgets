<?php
/**
 * Video on Hover — Elementor widget.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mongoose_Video_On_Hover_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mongoose-video-on-hover';
    }

    public function get_title() {
        return esc_html__( 'Video on Hover', 'mongoose-widgets' );
    }

    public function get_icon() {
        return 'eicon-video-camera';
    }

    public function get_categories() {
        return [ 'mongoose-widgets' ];
    }

    public function get_keywords() {
        return [ 'video', 'hover', 'play', 'mouse', 'transition' ];
    }

    public function get_style_depends() {
        return [ 'mongoose-video-on-hover' ];
    }

    public function get_script_depends() {
        return [ 'mongoose-video-on-hover' ];
    }

    protected function register_controls() {

        /*
         * ── Content Tab ──────────────────────────────────────────
         */
        $this->start_controls_section( 'section_content', [
            'label' => esc_html__( 'Content', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        // Video source selector.
        $this->add_control( 'video_source', [
            'label'   => esc_html__( 'Video Source', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'self_hosted',
            'options' => [
                'self_hosted'  => esc_html__( 'Self Hosted', 'mongoose-widgets' ),
                'external_url' => esc_html__( 'External URL', 'mongoose-widgets' ),
            ],
        ] );

        // Self-hosted video file.
        $this->add_control( 'video_file', [
            'label'       => esc_html__( 'Video File', 'mongoose-widgets' ),
            'type'        => \Elementor\Controls_Manager::MEDIA,
            'media_types' => [ 'video' ],
            'condition'   => [
                'video_source' => 'self_hosted',
            ],
        ] );

        // External video URL.
        $this->add_control( 'video_url', [
            'label'       => esc_html__( 'Video URL', 'mongoose-widgets' ),
            'type'        => \Elementor\Controls_Manager::URL,
            'placeholder' => 'https://example.com/video.mp4',
            'condition'   => [
                'video_source' => 'external_url',
            ],
        ] );

        // Video position (object-position).
        $this->add_control( 'video_position', [
            'label'     => esc_html__( 'Video Position', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'default'   => 'center center',
            'options'   => [
                'top left'      => esc_html__( 'Top Left', 'mongoose-widgets' ),
                'top center'    => esc_html__( 'Top Center', 'mongoose-widgets' ),
                'top right'     => esc_html__( 'Top Right', 'mongoose-widgets' ),
                'center left'   => esc_html__( 'Center Left', 'mongoose-widgets' ),
                'center center' => esc_html__( 'Center Center', 'mongoose-widgets' ),
                'center right'  => esc_html__( 'Center Right', 'mongoose-widgets' ),
                'bottom left'   => esc_html__( 'Bottom Left', 'mongoose-widgets' ),
                'bottom center' => esc_html__( 'Bottom Center', 'mongoose-widgets' ),
                'bottom right'  => esc_html__( 'Bottom Right', 'mongoose-widgets' ),
            ],
            'selectors' => [
                '{{WRAPPER}} .mw-voh-video' => 'object-position: {{VALUE}};',
            ],
        ] );

        // Transition duration.
        $this->add_control( 'transition_duration', [
            'label'      => esc_html__( 'Transition Duration (ms)', 'mongoose-widgets' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'range'      => [
                'px' => [
                    'min'  => 0,
                    'max'  => 2000,
                    'step' => 50,
                ],
            ],
            'default'    => [
                'size' => 300,
                'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .mw-voh-video, {{WRAPPER}} .mw-voh-overlay' =>
                    'transition-duration: {{SIZE}}ms;',
            ],
        ] );

        $this->end_controls_section();

        /*
         * ── Style Tab — Overlay ──────────────────────────────────
         */
        $this->start_controls_section( 'section_overlay', [
            'label' => esc_html__( 'Overlay', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'overlay_enabled', [
            'label'        => esc_html__( 'Enable Overlay', 'mongoose-widgets' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'mongoose-widgets' ),
            'label_off'    => esc_html__( 'No', 'mongoose-widgets' ),
            'return_value' => 'yes',
            'default'      => '',
        ] );

        $this->add_control( 'overlay_color', [
            'label'     => esc_html__( 'Overlay Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.4)',
            'selectors' => [
                '{{WRAPPER}} .mw-voh-overlay' => 'background-color: {{VALUE}};',
            ],
            'condition' => [
                'overlay_enabled' => 'yes',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Resolve video URL.
        $video_url = '';
        if ( 'self_hosted' === $settings['video_source'] && ! empty( $settings['video_file']['url'] ) ) {
            $video_url = $settings['video_file']['url'];
        } elseif ( 'external_url' === $settings['video_source'] && ! empty( $settings['video_url']['url'] ) ) {
            $video_url = $settings['video_url']['url'];
        }

        $transition = ! empty( $settings['transition_duration']['size'] ) ? $settings['transition_duration']['size'] : 300;
        $has_overlay = 'yes' === $settings['overlay_enabled'];
        ?>
        <div class="mw-voh-container" data-transition="<?php echo esc_attr( $transition ); ?>">
            <?php if ( $video_url ) : ?>
                <video class="mw-voh-video" src="<?php echo esc_url( $video_url ); ?>" muted loop playsinline preload="metadata"></video>
            <?php endif; ?>
            <?php if ( $has_overlay ) : ?>
                <div class="mw-voh-overlay"></div>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        var videoUrl = '';
        if ( 'self_hosted' === settings.video_source && settings.video_file && settings.video_file.url ) {
            videoUrl = settings.video_file.url;
        } else if ( 'external_url' === settings.video_source && settings.video_url && settings.video_url.url ) {
            videoUrl = settings.video_url.url;
        }
        var transition = ( settings.transition_duration && settings.transition_duration.size ) ? settings.transition_duration.size : 300;
        var hasOverlay = 'yes' === settings.overlay_enabled;
        #>
        <div class="mw-voh-container" data-transition="{{ transition }}">
            <# if ( videoUrl ) { #>
                <video class="mw-voh-video" src="{{ videoUrl }}" muted loop playsinline preload="metadata"></video>
            <# } #>
            <# if ( hasOverlay ) { #>
                <div class="mw-voh-overlay"></div>
            <# } #>
        </div>
        <?php
    }
}
