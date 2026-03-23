<?php
/**
 * Widget configuration registry.
 *
 * To add a new widget, append an entry to the array returned by get_widgets()
 * and create the corresponding widget class file.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Mongoose_Widgets_Registry {

    /**
     * Return the master list of available widgets.
     *
     * @return array Keyed by widget slug.
     */
    public static function get_widgets() {
        return [
            'video-on-hover' => [
                'label'       => 'Video on Hover',
                'description' => 'Displays an image that transitions to a playing video on hover.',
                'icon'        => 'eicon-video-camera',
                'file'        => MONGOOSE_WIDGETS_PATH . 'widgets/class-video-on-hover-widget.php',
                'class'       => 'Mongoose_Video_On_Hover_Widget',
                'css'         => [
                    'handle' => 'mongoose-video-on-hover',
                    'src'    => MONGOOSE_WIDGETS_URL . 'assets/css/video-on-hover.css',
                ],
                'js'          => [
                    'handle' => 'mongoose-video-on-hover',
                    'src'    => MONGOOSE_WIDGETS_URL . 'assets/js/video-on-hover.js',
                ],
            ],
            'button' => [
                'label'       => 'Button',
                'description' => 'A styled button with configurable text and icon.',
                'icon'        => 'eicon-button',
                'file'        => MONGOOSE_WIDGETS_PATH . 'widgets/class-button-widget.php',
                'class'       => 'Mongoose_Button_Widget',
                'css'         => [
                    'handle' => 'mongoose-button',
                    'src'    => MONGOOSE_WIDGETS_URL . 'assets/css/button.css',
                ],
            ],
            'testimonials' => [
                'label'       => 'Testimonials',
                'description' => 'Displays testimonial cards with quote, avatar, name, and title.',
                'icon'        => 'eicon-testimonial',
                'file'        => MONGOOSE_WIDGETS_PATH . 'widgets/class-testimonials-widget.php',
                'class'       => 'Mongoose_Testimonials_Widget',
                'css'         => [
                    'handle' => 'mongoose-testimonials',
                    'src'    => MONGOOSE_WIDGETS_URL . 'assets/css/testimonials.css',
                ],
            ],
            'faq' => [
                'label'       => 'FAQ',
                'description' => 'Displays FAQ items in a 3-column accordion grid with numbered headings.',
                'icon'        => 'eicon-accordion',
                'file'        => MONGOOSE_WIDGETS_PATH . 'widgets/class-faq-widget.php',
                'class'       => 'Mongoose_FAQ_Widget',
                'css'         => [
                    'handle' => 'mongoose-faq',
                    'src'    => MONGOOSE_WIDGETS_URL . 'assets/css/faq.css',
                ],
                'js'          => [
                    'handle' => 'mongoose-faq',
                    'src'    => MONGOOSE_WIDGETS_URL . 'assets/js/faq.js',
                ],
            ],
            'events-listing' => [
                'label'       => 'Events Listing',
                'description' => 'Displays a filterable list of events with type and month filters.',
                'icon'        => 'eicon-calendar',
                'file'        => MONGOOSE_WIDGETS_PATH . 'widgets/class-events-listing-widget.php',
                'class'       => 'Mongoose_Events_Listing_Widget',
                'css'         => [
                    'handle' => 'mongoose-events-listing',
                    'src'    => MONGOOSE_WIDGETS_URL . 'assets/css/events-listing.css',
                ],
                'js'          => [
                    'handle' => 'mongoose-events-listing',
                    'src'    => MONGOOSE_WIDGETS_URL . 'assets/js/events-listing.js',
                ],
            ],
            'checkbox-hover' => [
                'label'       => 'Checkbox Hover',
                'description' => 'Shows a bordered list of checkbox items revealed on hover of a chosen selector. Supports a single shared container or individual stacked containers.',
                'icon'        => 'eicon-checkbox',
                'file'        => MONGOOSE_WIDGETS_PATH . 'widgets/class-checkbox-hover-widget.php',
                'class'       => 'Mongoose_Checkbox_Hover_Widget',
                'css'         => [
                    'handle' => 'mongoose-checkbox-hover',
                    'src'    => MONGOOSE_WIDGETS_URL . 'assets/css/checkbox-hover.css',
                ],
                'js'          => [
                    'handle' => 'mongoose-checkbox-hover',
                    'src'    => MONGOOSE_WIDGETS_URL . 'assets/js/checkbox-hover.js',
                ],
            ],
        ];
    }
}
