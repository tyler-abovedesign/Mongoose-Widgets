<?php
/**
 * Checkbox Hover Widget
 *
 * Displays a list of checkbox items (with optional descriptions) inside a
 * bordered container. The widget is hidden until a specified CSS selector
 * is hovered. Supports two layouts:
 *   - single   : all items inside one shared bordered container (flex-wrap grid)
 *   - stacked  : each item in its own bordered container, stacked vertically
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mongoose_Checkbox_Hover_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mongoose-checkbox-hover';
    }

    public function get_title() {
        return esc_html__( 'Checkbox Hover', 'mongoose-widgets' );
    }

    public function get_icon() {
        return 'eicon-checkbox';
    }

    public function get_categories() {
        return [ 'mongoose-widgets' ];
    }

    public function get_keywords() {
        return [ 'checkbox', 'hover', 'list', 'check', 'features', 'reveal' ];
    }

    public function get_style_depends() {
        return [ 'mongoose-checkbox-hover' ];
    }

    public function get_script_depends() {
        return [ 'mongoose-checkbox-hover' ];
    }

    // -------------------------------------------------------------------------
    // Controls
    // -------------------------------------------------------------------------

    protected function register_controls() {

        // ── Content ─────────────────────────────────────────────────────────

        $this->start_controls_section( 'section_content', [
            'label' => esc_html__( 'Content', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'layout_type', [
            'label'   => esc_html__( 'Layout', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'single',
            'options' => [
                'single'  => esc_html__( 'Single Container', 'mongoose-widgets' ),
                'stacked' => esc_html__( 'Stacked Containers', 'mongoose-widgets' ),
            ],
        ] );

        $this->add_control( 'trigger_selector', [
            'label'       => esc_html__( 'Hover Trigger Selector', 'mongoose-widgets' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' => '.my-element or #my-id',
            'description' => esc_html__( 'CSS selector (class or ID) whose hover reveals this widget. Leave blank to always show.', 'mongoose-widgets' ),
        ] );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'item_label', [
            'label'       => esc_html__( 'Label', 'mongoose-widgets' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'default'     => esc_html__( 'Feature', 'mongoose-widgets' ),
            'label_block' => true,
        ] );

        $repeater->add_control( 'item_description', [
            'label'       => esc_html__( 'Description', 'mongoose-widgets' ),
            'type'        => \Elementor\Controls_Manager::TEXTAREA,
            'default'     => '',
            'label_block' => true,
            'description' => esc_html__( 'Used in Stacked layout. Appears inline after the label.', 'mongoose-widgets' ),
        ] );

        $this->add_control( 'items', [
            'label'       => esc_html__( 'Checkbox Items', 'mongoose-widgets' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'item_label' => 'Google Ads',    'item_description' => '' ],
                [ 'item_label' => 'Yahoo! Ads',    'item_description' => '' ],
                [ 'item_label' => 'YouTube Ads',   'item_description' => '' ],
                [ 'item_label' => 'TikTok Shop Ads', 'item_description' => '' ],
                [ 'item_label' => 'Apple Search Ads', 'item_description' => '' ],
            ],
            'title_field' => '{{{ item_label }}}',
        ] );

        $this->end_controls_section();

        // ── Style – Container ────────────────────────────────────────────────

        $this->start_controls_section( 'section_style_container', [
            'label' => esc_html__( 'Container', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'background_color', [
            'label'     => esc_html__( 'Background Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .mw-ch-container' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .mw-ch-item'      => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'border_color', [
            'label'     => esc_html__( 'Border Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .mw-ch-container' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .mw-ch-item'      => 'border-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'border_width', [
            'label'      => esc_html__( 'Border Width', 'mongoose-widgets' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
            'default'    => [ 'size' => 1, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .mw-ch-container' => 'border-width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .mw-ch-item'      => 'border-width: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'border_radius', [
            'label'      => esc_html__( 'Border Radius', 'mongoose-widgets' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'default'    => [ 'size' => 10, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .mw-ch-container' => 'border-radius: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .mw-ch-item'      => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'container_padding', [
            'label'      => esc_html__( 'Padding', 'mongoose-widgets' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'selectors'  => [
                '{{WRAPPER}} .mw-ch-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .mw-ch-item'      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'items_gap', [
            'label'      => esc_html__( 'Gap Between Items', 'mongoose-widgets' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'default'    => [ 'size' => 16, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .mw-ch-container' => 'gap: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .mw-ch-list'      => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ── Style – Checkbox Icon ────────────────────────────────────────────

        $this->start_controls_section( 'section_style_icon', [
            'label' => esc_html__( 'Checkbox Icon', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'check_color', [
            'label'     => esc_html__( 'Check Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ff2d5b',
            'selectors' => [
                '{{WRAPPER}} .mw-ch-icon' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'check_size', [
            'label'      => esc_html__( 'Icon Size', 'mongoose-widgets' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 10, 'max' => 60 ] ],
            'default'    => [ 'size' => 18, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .mw-ch-icon' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();

        // ── Style – Text ─────────────────────────────────────────────────────

        $this->start_controls_section( 'section_style_text', [
            'label' => esc_html__( 'Text', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'label_color', [
            'label'     => esc_html__( 'Label Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .mw-ch-label' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'label_typography',
            'selector' => '{{WRAPPER}} .mw-ch-label',
        ] );

        $this->add_control( 'description_color', [
            'label'     => esc_html__( 'Description Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .mw-ch-description' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'description_typography',
            'selector' => '{{WRAPPER}} .mw-ch-description',
        ] );

        $this->end_controls_section();

        // ── Style – Reveal Transition ────────────────────────────────────────

        $this->start_controls_section( 'section_style_reveal', [
            'label' => esc_html__( 'Reveal Transition', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'transition_duration', [
            'label'      => esc_html__( 'Duration (ms)', 'mongoose-widgets' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 1000, 'step' => 50 ] ],
            'default'    => [ 'size' => 300 ],
            'selectors'  => [
                '{{WRAPPER}} .mw-ch-wrapper' => 'transition-duration: {{SIZE}}ms;',
            ],
        ] );

        $this->end_controls_section();
    }

    // -------------------------------------------------------------------------
    // Render
    // -------------------------------------------------------------------------

    protected function render() {
        $settings = $this->get_settings_for_display();
        $layout   = ! empty( $settings['layout_type'] ) ? $settings['layout_type'] : 'single';
        $selector = ! empty( $settings['trigger_selector'] ) ? trim( $settings['trigger_selector'] ) : '';
        $items    = ! empty( $settings['items'] ) ? $settings['items'] : [];

        $trigger_attr = $selector ? ' data-trigger="' . esc_attr( $selector ) . '"' : '';
        ?>
        <div class="mw-ch-wrapper mw-ch-layout-<?php echo esc_attr( $layout ); ?>"<?php echo $trigger_attr; ?>>

            <?php if ( 'single' === $layout ) : ?>

                <div class="mw-ch-container">
                    <?php foreach ( $items as $item ) : ?>
                        <div class="mw-ch-entry">
                            <span class="mw-ch-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                            <span class="mw-ch-label"><?php echo esc_html( $item['item_label'] ); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else : ?>

                <div class="mw-ch-list">
                    <?php foreach ( $items as $item ) : ?>
                        <div class="mw-ch-item">
                            <span class="mw-ch-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                            <p class="mw-ch-body">
                                <?php if ( ! empty( $item['item_label'] ) ) : ?>
                                    <span class="mw-ch-label"><?php echo esc_html( $item['item_label'] ); ?></span>
                                <?php endif; ?>
                                <?php if ( ! empty( $item['item_description'] ) ) : ?>
                                    <span class="mw-ch-description"><?php echo esc_html( $item['item_description'] ); ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <#
        var layout   = settings.layout_type || 'single';
        var selector = settings.trigger_selector ? settings.trigger_selector.trim() : '';
        var items    = settings.items || [];
        var triggerAttr = selector ? 'data-trigger="' + _.escape( selector ) + '"' : '';
        #>
        <div class="mw-ch-wrapper mw-ch-layout-{{ layout }}" {{{ triggerAttr }}}>

            <# if ( layout === 'single' ) { #>

                <div class="mw-ch-container">
                    <# _.each( items, function( item ) { #>
                        <div class="mw-ch-entry">
                            <span class="mw-ch-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                            <span class="mw-ch-label">{{{ item.item_label }}}</span>
                        </div>
                    <# } ); #>
                </div>

            <# } else { #>

                <div class="mw-ch-list">
                    <# _.each( items, function( item ) { #>
                        <div class="mw-ch-item">
                            <span class="mw-ch-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                            <p class="mw-ch-body">
                                <# if ( item.item_label ) { #>
                                    <span class="mw-ch-label">{{{ item.item_label }}}</span>
                                <# } #>
                                <# if ( item.item_description ) { #>
                                    <span class="mw-ch-description">{{{ item.item_description }}}</span>
                                <# } #>
                            </p>
                        </div>
                    <# } ); #>
                </div>

            <# } #>

        </div>
        <?php
    }
}
