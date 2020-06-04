<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! function_exists( 'neve_child_load_css' ) ):
	/**
	 * Load CSS file.
	 */
	function neve_child_load_css() {
		wp_enqueue_style( 'neve-child-style', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'neve-style' ), NEVE_VERSION );
	}
endif;
add_action( 'wp_enqueue_scripts', 'neve_child_load_css', 20 );


// Custom Widgets. TO-DO convert to plugins.
class widget__cpt_projects extends WP_Widget
{

	function __construct()
	{
		parent::__construct(
			// Base ID of your widget
			'jm_widget__cpt_projects',
			// Widget name that will appear in UI
			'Projects ordered by date (CPT)',
			// Other data
			[
				'description' => 'Show latest projects.',
			]
		);
	}

	public function widget($args, $instance)
	{
		echo "The widget works!";
	}
}

// Register all custom widgets
function jm_load_widgets()
{
	register_widget('jm_widget__cpt_projects');
}
add_action('widgets_init', 'jm_load_widgets');