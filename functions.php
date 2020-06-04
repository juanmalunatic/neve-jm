<?php

// -----------------------------------------------------------
// Default Neve hooks / fx
// -----------------------------------------------------------

if (!defined('ABSPATH')) {
	exit;
}
if (!function_exists('neve_child_load_css')) :
	/**
	 * Load CSS file.
	 */
	function neve_child_load_css()
	{
		wp_enqueue_style('neve-child-style', trailingslashit(get_stylesheet_directory_uri()) . 'style.css', array('neve-style'), NEVE_VERSION);
	}
endif;
add_action('wp_enqueue_scripts', 'neve_child_load_css', 20);

// -----------------------------------------------------------
// Custom Widgets. TO-DO convert to plugins.
// -----------------------------------------------------------

class jm_widget__cpt_projects extends WP_Widget
{

	function __construct()
	{
		parent::__construct(
			// Base ID of your widget
			'jm_widget__cpt_projects',
			// Widget name that will appear in UI
			'Projects (CPT)',
			// Other data
			[
				'description' => 'Show latest projects ordered by release date.',
			]
		);
	}

	public function widget($args, $instance)
	{
		//TO-DO customize how many we show
		//TO-DO decide if want to show tech
		$projects = $this->getProjects($instance['display']);
?>
		<div id="recent-projects" class="widget widget_recent_entries">
			<p class="widget-title">Recent Projects</p>
			<ul>
				<?php foreach ($projects as $project) : ?>
					<li>
						<a href="<?= $project['link'] ?>"> <?= $project['title'] ?> </a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php
	}

	public function form($instance)
	{
		$defaults = ['display' => '3'];
		$display = $instance['display'];
	?>
		<p>
			<label for="<?php echo $this->get_field_id('display'); ?>">
				Projects to display (positive int)
			</label>
			<input class="widefat" type="text" id="<?= $this->get_field_id('display'); ?>" name="<?= $this->get_field_name('display'); ?>" value="<?= esc_attr($display); ?>">
		</p>
<?php

	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['display'] = strip_tags($new_instance['display']);
		return $instance;
	}

	// Fetch the actual listings
	public function getProjects($numberOfListings = 3)
	{
		$the_query = new WP_Query();
		$args = [
			'post_type' => 'project',
			'posts_per_page' => $numberOfListings,
			'orderby' => [
				'meta_value' => 'DESC',
				'date' => 'DESC'
			],
			'meta_key' => 'released',
		];
		$the_query->query($args);

		// Handle results
		if ($the_query->found_posts > 0) {
			$posts = $the_query->posts;
			$data = [];

			foreach ($posts as $post) {
				$data[] = [
					'title' => $post->post_title,
					'link' => '/proyect/' . $post->post_name,
					//'tech' => $this->getTech($post->ID), // this is kind of heavy
				];
			}
			return $data;
		} else {
			return [];
		}
	}

	public function getTech($postId)
	{
		$arr = [];
		$terms = wp_get_object_terms($postId, 'tech', ['orderby' => 'term_order']);
		foreach ((array) $terms as $term) {
			$arr[] = $term->name;
		}
		return implode(", ", $arr);
	}
}

// Register all custom widgets
function jm_load_widgets()
{
	register_widget('jm_widget__cpt_projects');
}
add_action('widgets_init', 'jm_load_widgets');
