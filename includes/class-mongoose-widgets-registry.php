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
        ];
    }
}
