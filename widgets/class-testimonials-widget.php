<?php
/**
 * Testimonials — Elementor widget.
 *
 * Displays testimonials from the mongoose_testimonial CPT.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mongoose_Testimonials_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mongoose-testimonials';
    }

    public function get_title() {
        return esc_html__( 'Testimonials', 'mongoose-widgets' );
    }

    public function get_icon() {
        return 'eicon-testimonial';
    }

    public function get_categories() {
        return [ 'mongoose-widgets' ];
    }

    public function get_keywords() {
        return [ 'testimonial', 'review', 'quote', 'feedback' ];
    }

    public function get_style_depends() {
        return [ 'mongoose-testimonials' ];
    }

    protected function register_controls() {

        $this->start_controls_section( 'section_query', [
            'label' => esc_html__( 'Query', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'posts_per_page', [
            'label'   => esc_html__( 'Number of Testimonials', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
            'min'     => 1,
            'max'     => 12,
        ] );

        $this->add_control( 'orderby', [
            'label'   => esc_html__( 'Order By', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'date',
            'options' => [
                'date'       => esc_html__( 'Date', 'mongoose-widgets' ),
                'title'      => esc_html__( 'Title', 'mongoose-widgets' ),
                'menu_order' => esc_html__( 'Menu Order', 'mongoose-widgets' ),
                'rand'       => esc_html__( 'Random', 'mongoose-widgets' ),
            ],
        ] );

        $this->add_control( 'order', [
            'label'   => esc_html__( 'Order', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'DESC',
            'options' => [
                'ASC'  => esc_html__( 'Ascending', 'mongoose-widgets' ),
                'DESC' => esc_html__( 'Descending', 'mongoose-widgets' ),
            ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $query = new \WP_Query( [
            'post_type'      => 'mongoose_testimonial',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby'        => $settings['orderby'],
            'order'          => $settings['order'],
            'post_status'    => 'publish',
        ] );

        if ( ! $query->have_posts() ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<p style="color:#999;text-align:center;">' . esc_html__( 'No testimonials found. Add testimonials in the Testimonials post type.', 'mongoose-widgets' ) . '</p>';
            }
            return;
        }
        ?>
        <div class="mw-testimonials">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <?php
                $name       = get_the_title();
                $text       = get_field( 'testimonial_text' );
                $avatar     = get_field( 'testimonial_avatar' );
                $person_title = get_field( 'testimonial_person_title' );
                ?>
                <div class="mw-testimonials__card">
                    <div class="mw-testimonials__text">
                        <?php echo esc_html( $text ); ?>
                    </div>
                    <div class="mw-testimonials__author">
                        <?php if ( ! empty( $avatar ) ) : ?>
                            <img class="mw-testimonials__avatar" src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $name ); ?>">
                        <?php endif; ?>
                        <div class="mw-testimonials__info">
                            <div class="mw-testimonials__name"><?php echo esc_html( $name ); ?></div>
                            <?php if ( ! empty( $person_title ) ) : ?>
                                <div class="mw-testimonials__title"><?php echo esc_html( $person_title ); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
