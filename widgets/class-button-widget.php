<?php
/**
 * Button — Elementor widget.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mongoose_Button_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mongoose-button';
    }

    public function get_title() {
        return esc_html__( 'Button', 'mongoose-widgets' );
    }

    public function get_icon() {
        return 'eicon-button';
    }

    public function get_categories() {
        return [ 'mongoose-widgets' ];
    }

    public function get_keywords() {
        return [ 'button', 'link', 'cta', 'glow' ];
    }

    public function get_style_depends() {
        return [ 'mongoose-button' ];
    }

    protected function register_controls() {

        $this->start_controls_section( 'section_content', [
            'label' => esc_html__( 'Content', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'button_text', [
            'label'   => esc_html__( 'Text', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'Click Here', 'mongoose-widgets' ),
        ] );

        $this->add_control( 'button_icon_left', [
            'label' => esc_html__( 'Icon Left', 'mongoose-widgets' ),
            'type'  => \Elementor\Controls_Manager::ICONS,
        ] );

        $this->add_control( 'button_icon_right', [
            'label' => esc_html__( 'Icon Right', 'mongoose-widgets' ),
            'type'  => \Elementor\Controls_Manager::ICONS,
        ] );

        $this->add_control( 'button_icon_size', [
            'label'      => esc_html__( 'Icon Size', 'mongoose-widgets' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
                'px' => [
                    'min' => 8,
                    'max' => 64,
                ],
            ],
            'default'    => [
                'size' => 16,
                'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .mw-btn .mw-btn__icon' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .mw-btn svg.mw-btn__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'button_icon_color', [
            'label'     => esc_html__( 'Icon Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#FFFFFF',
            'selectors' => [
                '{{WRAPPER}} .mw-btn .mw-btn__icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .mw-btn svg.mw-btn__icon' => 'fill: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'button_link', [
            'label'       => esc_html__( 'Link', 'mongoose-widgets' ),
            'type'        => \Elementor\Controls_Manager::URL,
            'placeholder' => 'https://example.com',
        ] );

        $this->add_control( 'button_style', [
            'label'   => esc_html__( 'Style', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'blue-glow',
            'options' => [
                'blue-glow' => esc_html__( 'Blue Glow', 'mongoose-widgets' ),
            ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $style_class = 'mw-btn--' . $settings['button_style'];

        $this->add_render_attribute( 'button', 'class', [ 'mw-btn', $style_class ] );

        if ( ! empty( $settings['button_link']['url'] ) ) {
            $this->add_link_attributes( 'button', $settings['button_link'] );
        }

        $tag = ! empty( $settings['button_link']['url'] ) ? 'a' : 'span';
        ?>
        <<?php echo $tag; ?> <?php $this->print_render_attribute_string( 'button' ); ?>>
            <?php if ( ! empty( $settings['button_icon_left']['value'] ) ) : ?>
                <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon_left'], [ 'aria-hidden' => 'true', 'class' => 'mw-btn__icon mw-btn__icon--left' ] ); ?>
            <?php endif; ?>
            <?php if ( ! empty( $settings['button_text'] ) ) : ?>
                <span class="mw-btn__text"><?php echo esc_html( $settings['button_text'] ); ?></span>
            <?php endif; ?>
            <?php if ( ! empty( $settings['button_icon_right']['value'] ) ) : ?>
                <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon_right'], [ 'aria-hidden' => 'true', 'class' => 'mw-btn__icon mw-btn__icon--right' ] ); ?>
            <?php endif; ?>
        </<?php echo $tag; ?>>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        var tag = ( settings.button_link && settings.button_link.url ) ? 'a' : 'span';
        var styleClass = 'mw-btn--' + settings.button_style;
        var href = ( tag === 'a' ) ? ' href="' + _.escape( settings.button_link.url ) + '"' : '';
        #>
        <{{{ tag }}}{{{ href }}} class="mw-btn {{ styleClass }}">
            <# if ( settings.button_icon_left && settings.button_icon_left.value ) { #>
                <i class="{{ settings.button_icon_left.value }} mw-btn__icon mw-btn__icon--left" aria-hidden="true"></i>
            <# } #>
            <# if ( settings.button_text ) { #>
                <span class="mw-btn__text">{{{ settings.button_text }}}</span>
            <# } #>
            <# if ( settings.button_icon_right && settings.button_icon_right.value ) { #>
                <i class="{{ settings.button_icon_right.value }} mw-btn__icon mw-btn__icon--right" aria-hidden="true"></i>
            <# } #>
        </{{{ tag }}}>
        <?php
    }
}
