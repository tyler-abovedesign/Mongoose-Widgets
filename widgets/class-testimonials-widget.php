<?php
/**
 * Testimonials — Elementor widget.
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

        $this->start_controls_section( 'section_testimonials', [
            'label' => esc_html__( 'Testimonials', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'testimonial_text', [
            'label'   => esc_html__( 'Testimonial', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => esc_html__( 'Enter testimonial text here.', 'mongoose-widgets' ),
            'rows'    => 6,
        ] );

        $repeater->add_control( 'avatar', [
            'label'   => esc_html__( 'Avatar', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => [
                'url' => \Elementor\Utils::get_placeholder_image_src(),
            ],
        ] );

        $repeater->add_control( 'name', [
            'label'   => esc_html__( 'Name', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'John Doe', 'mongoose-widgets' ),
        ] );

        $repeater->add_control( 'title', [
            'label'   => esc_html__( 'Title', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'Job Title | Company', 'mongoose-widgets' ),
        ] );

        $this->add_control( 'testimonials', [
            'label'       => esc_html__( 'Testimonial Items', 'mongoose-widgets' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'testimonial_text' => esc_html__( 'This is an amazing service. Highly recommended!', 'mongoose-widgets' ),
                    'name'             => esc_html__( 'Jane Smith', 'mongoose-widgets' ),
                    'title'            => esc_html__( 'Marketing Director | Acme Corp', 'mongoose-widgets' ),
                ],
                [
                    'testimonial_text' => esc_html__( 'Working with this team has been a fantastic experience.', 'mongoose-widgets' ),
                    'name'             => esc_html__( 'John Doe', 'mongoose-widgets' ),
                    'title'            => esc_html__( 'CEO | Tech Startup', 'mongoose-widgets' ),
                ],
                [
                    'testimonial_text' => esc_html__( 'Professional, creative, and always delivering on time.', 'mongoose-widgets' ),
                    'name'             => esc_html__( 'Sarah Johnson', 'mongoose-widgets' ),
                    'title'            => esc_html__( 'Product Manager | Global Inc', 'mongoose-widgets' ),
                ],
            ],
            'title_field' => '{{{ name }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['testimonials'] ) ) {
            return;
        }
        ?>
        <div class="mw-testimonials">
            <?php foreach ( $settings['testimonials'] as $index => $item ) : ?>
                <div class="mw-testimonials__card">
                    <div class="mw-testimonials__text">
                        <?php echo esc_html( $item['testimonial_text'] ); ?>
                    </div>
                    <div class="mw-testimonials__author">
                        <?php if ( ! empty( $item['avatar']['url'] ) ) : ?>
                            <img class="mw-testimonials__avatar" src="<?php echo esc_url( $item['avatar']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>">
                        <?php endif; ?>
                        <div class="mw-testimonials__info">
                            <div class="mw-testimonials__name"><?php echo esc_html( $item['name'] ); ?></div>
                            <div class="mw-testimonials__title"><?php echo esc_html( $item['title'] ); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <# if ( settings.testimonials.length ) { #>
        <div class="mw-testimonials">
            <# _.each( settings.testimonials, function( item, index ) { #>
                <div class="mw-testimonials__card">
                    <div class="mw-testimonials__text">
                        {{{ item.testimonial_text }}}
                    </div>
                    <div class="mw-testimonials__author">
                        <# if ( item.avatar && item.avatar.url ) { #>
                            <img class="mw-testimonials__avatar" src="{{ item.avatar.url }}" alt="{{ item.name }}">
                        <# } #>
                        <div class="mw-testimonials__info">
                            <div class="mw-testimonials__name">{{{ item.name }}}</div>
                            <div class="mw-testimonials__title">{{{ item.title }}}</div>
                        </div>
                    </div>
                </div>
            <# }); #>
        </div>
        <# } #>
        <?php
    }
}
