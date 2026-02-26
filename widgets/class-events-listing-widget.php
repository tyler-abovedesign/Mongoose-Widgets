<?php
/**
 * Events Listing — Elementor widget.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mongoose_Events_Listing_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mongoose-events-listing';
    }

    public function get_title() {
        return esc_html__( 'Events Listing', 'mongoose-widgets' );
    }

    public function get_icon() {
        return 'eicon-calendar';
    }

    public function get_categories() {
        return [ 'mongoose-widgets' ];
    }

    public function get_keywords() {
        return [ 'events', 'calendar', 'listing', 'filter', 'schedule' ];
    }

    public function get_style_depends() {
        return [ 'mongoose-events-listing' ];
    }

    public function get_script_depends() {
        return [ 'mongoose-events-listing' ];
    }

    protected function register_controls() {

        /*
         * ── Content Tab ──────────────────────────────────────────
         */
        $this->start_controls_section( 'section_content', [
            'label' => esc_html__( 'Content', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'events_per_page', [
            'label'   => esc_html__( 'Events Per Page', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 12,
            'min'     => 1,
            'max'     => 50,
        ] );

        $this->add_control( 'show_past_events', [
            'label'        => esc_html__( 'Show Past Events', 'mongoose-widgets' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'mongoose-widgets' ),
            'label_off'    => esc_html__( 'No', 'mongoose-widgets' ),
            'return_value' => 'yes',
            'default'      => '',
        ] );

        $this->end_controls_section();

        /*
         * ── Style Tab — Filter Buttons ──────────────────────────
         */
        $this->start_controls_section( 'section_style_filters', [
            'label' => esc_html__( 'Filter Buttons', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'filter_bg_color', [
            'label'     => esc_html__( 'Default Background', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#0d0e23',
            'selectors' => [
                '{{WRAPPER}} .mw-el-filter-btn' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'filter_active_bg_color', [
            'label'     => esc_html__( 'Active Background', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#1eaaf0',
            'selectors' => [
                '{{WRAPPER}} .mw-el-filter-btn.mw-el-filter-btn--active' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'filter_text_color', [
            'label'     => esc_html__( 'Text Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mw-el-filter-btn' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'filter_border_radius', [
            'label'      => esc_html__( 'Border Radius', 'mongoose-widgets' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
                'px' => [ 'min' => 0, 'max' => 50 ],
            ],
            'default'    => [ 'size' => 24, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .mw-el-filter-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'filter_typography',
            'selector' => '{{WRAPPER}} .mw-el-filter-btn',
        ] );

        $this->end_controls_section();

        /*
         * ── Style Tab — Event Cards ─────────────────────────────
         */
        $this->start_controls_section( 'section_style_cards', [
            'label' => esc_html__( 'Event Cards', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'card_bg_color', [
            'label'     => esc_html__( 'Card Background', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#141414',
            'selectors' => [
                '{{WRAPPER}} .mw-el-card' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'card_text_color', [
            'label'     => esc_html__( 'Text Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .mw-el-card' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'card_border_radius', [
            'label'      => esc_html__( 'Border Radius', 'mongoose-widgets' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
                'px' => [ 'min' => 0, 'max' => 30 ],
            ],
            'default'    => [ 'size' => 12, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .mw-el-card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'badge_heading', [
            'label'     => esc_html__( 'Badge', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_control( 'badge_text_color', [
            'label'     => esc_html__( 'Text & Icon Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ff007f',
            'selectors' => [
                '{{WRAPPER}} .mw-el-card__badge'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .mw-el-card__badge i'  => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'badge_bg_color', [
            'label'     => esc_html__( 'Background Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(0, 0, 0, 0.45)',
            'selectors' => [
                '{{WRAPPER}} .mw-el-card__badge' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'badge_border_color', [
            'label'     => esc_html__( 'Border Color', 'mongoose-widgets' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(255, 255, 255, 0.15)',
            'selectors' => [
                '{{WRAPPER}} .mw-el-card__badge' => 'border-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        // Guard: ensure CPT exists.
        if ( ! post_type_exists( 'mongoose_event' ) ) {
            echo '<p style="padding:20px;text-align:center;color:#999;">Please activate the <strong>Mongoose Events</strong> plugin to use this widget.</p>';
            return;
        }

        $settings  = $this->get_settings_for_display();
        $per_page  = ! empty( $settings['events_per_page'] ) ? absint( $settings['events_per_page'] ) : 12;
        $show_past = 'yes' === $settings['show_past_events'];

        // Get event type terms.
        $terms = get_terms( [
            'taxonomy'   => 'event_type',
            'hide_empty' => true,
        ] );
        if ( is_wp_error( $terms ) ) {
            $terms = [];
        }

        // Initial query.
        $query_args = [
            'post_type'      => 'mongoose_event',
            'posts_per_page' => $per_page,
            'meta_key'       => 'event_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => [],
        ];

        if ( ! $show_past ) {
            $query_args['meta_query'][] = [
                'key'     => 'event_date',
                'value'   => gmdate( 'Ymd' ),
                'compare' => '>=',
                'type'    => 'NUMERIC',
            ];
        }

        $query = new \WP_Query( $query_args );

        // Localize JS data.
        wp_localize_script( 'mongoose-events-listing', 'mongooseEventsListing', [
            'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'mongoose_events_nonce' ),
            'perPage'  => $per_page,
            'showPast' => $show_past ? '1' : '0',
        ] );
        ?>
        <div class="mw-el-wrap">
            <?php if ( ! empty( $terms ) ) : ?>
                <div class="mw-el-filters">
                    <button type="button" class="mw-el-filter-btn mw-el-filter-btn--active" data-type="all">All</button>
                    <?php foreach ( $terms as $term ) : ?>
                        <button type="button" class="mw-el-filter-btn" data-type="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="mw-el-body">
                <div class="mw-el-cards">
                    <?php
                    if ( $query->have_posts() ) {
                        while ( $query->have_posts() ) {
                            $query->the_post();
                            if ( class_exists( 'Mongoose_Events_AJAX' ) ) {
                                echo Mongoose_Events_AJAX::render_event_card( get_the_ID() );
                            }
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<p class="mw-el-no-results">No events found.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <div class="mw-el-wrap">
            <p style="padding:40px;text-align:center;color:#999;">Events Listing — preview will appear on the frontend.</p>
        </div>
        <?php
    }

}
