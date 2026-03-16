<?php
/**
 * FAQ — Elementor widget.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mongoose_FAQ_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'mongoose-faq';
    }

    public function get_title() {
        return esc_html__( 'FAQ', 'mongoose-widgets' );
    }

    public function get_icon() {
        return 'eicon-accordion';
    }

    public function get_categories() {
        return [ 'mongoose-widgets' ];
    }

    public function get_keywords() {
        return [ 'faq', 'accordion', 'question', 'answer', 'toggle' ];
    }

    public function get_style_depends() {
        return [ 'mongoose-faq' ];
    }

    public function get_script_depends() {
        return [ 'mongoose-faq' ];
    }

    protected function register_controls() {

        $this->start_controls_section( 'section_faq', [
            'label' => esc_html__( 'FAQ Items', 'mongoose-widgets' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'question', [
            'label'   => esc_html__( 'Question', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => esc_html__( 'How do I know if my brand is ready for paid ads?', 'mongoose-widgets' ),
            'label_block' => true,
        ] );

        $repeater->add_control( 'answer', [
            'label'   => esc_html__( 'Answer', 'mongoose-widgets' ),
            'type'    => \Elementor\Controls_Manager::WYSIWYG,
            'default' => esc_html__( 'If you\'ve got a solid product, a clear offer, and a way to convert traffic (even if it\'s scrappy), you\'re ready. You don\'t need everything perfect—you need a plan and the right support. We\'ll help you figure out the rest.', 'mongoose-widgets' ),
        ] );

        $this->add_control( 'faq_items', [
            'label'       => esc_html__( 'Items', 'mongoose-widgets' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'question' => esc_html__( 'How do I know if my brand is ready for paid ads?', 'mongoose-widgets' ),
                    'answer'   => esc_html__( 'If you\'ve got a solid product, a clear offer, and a way to convert traffic, you\'re ready.', 'mongoose-widgets' ),
                ],
                [
                    'question' => esc_html__( 'What platforms should I be running ads on?', 'mongoose-widgets' ),
                    'answer'   => esc_html__( 'It depends on your audience, but we typically recommend starting with Meta and Google.', 'mongoose-widgets' ),
                ],
                [
                    'question' => esc_html__( 'How long does it take to see ROI from paid ads?', 'mongoose-widgets' ),
                    'answer'   => esc_html__( 'Most brands see meaningful results within the first 60–90 days of a well-structured campaign.', 'mongoose-widgets' ),
                ],
            ],
            'title_field' => '{{{ question }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['faq_items'] ) ) {
            return;
        }
        ?>
        <div class="mw-faq">
            <?php foreach ( $settings['faq_items'] as $index => $item ) : ?>
                <div class="mw-faq__item" data-mw-faq-item>
                    <button class="mw-faq__header" type="button" aria-expanded="false" data-mw-faq-toggle>
                        <span class="mw-faq__number"><?php echo esc_html( $index + 1 ); ?></span>
                        <span class="mw-faq__question"><?php echo esc_html( $item['question'] ); ?></span>
                        <span class="mw-faq__chevron" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </button>
                    <div class="mw-faq__answer" data-mw-faq-answer hidden>
                        <div class="mw-faq__answer-inner">
                            <?php echo wp_kses_post( $item['answer'] ); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
