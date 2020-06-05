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


// -----------------------------------------------------------
// Custom function hooks to add ACF data to Project types
// -----------------------------------------------------------


function project_metadata_render () {
	global $post;

	// Only apply to Project type
	if ($post->post_type === "project") {

		// Get data
		$postID = $post->ID;
		$pairs = [
			"Link:"     => project_metadata_get_external_link($postID),
			"Tech:"     => project_metadata_get_tech_links($postID),
			"Date:" => project_metadata_get_released($postID),
		];

		// Populate template
		$pairsHtml = project_metadata_fill_tpl($pairs);

		echo <<< HTML
			<div id="project-metadata">
				$pairsHtml
			</div>
		HTML;

	};
}

function project_metadata_fill_tpl ($pairs) {
	$output = "";
	// Iterate and switch based on data type
	foreach ($pairs as $label => $value) {
		// If value is empty, skip the field
		if (empty($value)) continue;

		if (is_array($value)) {
			// Right now, we're only getting an array if it's Tech
			$content = project_metadata_tpl_tech($value);
		} else {
			$content = $value;
		}
		$output .= project_metadata_tpl_pair($label, $content);
	}
	return $output;
}

function project_metadata_tpl_pair ($label, $value) {
	return <<<HTML
		<!--div class="pair"-->
			<div class="pair-label">
				$label
			</div>
			<div class="pair-value">
				$value
			</div>
		<!--/div-->
	HTML;
}

function project_metadata_tpl_tech ($values) {
	$tpl = [];
	foreach ($values as $tech) {
		$name = $tech['name'];
		$link = $tech['link'];
		$tpl []= <<<HTML
			<a href="$link"> $name </a>
		HTML;
	}
	return implode(", ", $tpl);
}

function project_metadata_get_tech_links ($postID) {
	$terms = wp_get_object_terms($postID, 'tech', ['orderby' => 'term_order']);
	$data = [];
	/** @var WP_Term $term  */
	foreach ($terms as $term) {
		$term_data = [
			"name" => $term->name,
			"link" => get_term_link($term->term_id, 'tech'),
		];

		$data[]= $term_data;
	}
	return $data;
}

function project_metadata_get_external_link ($postID) {
	$link = get_post_meta($postID, 'external_link')[0];
	return <<<HTML
		<a href="$link">$link</a>
	HTML;
}

function project_metadata_get_released($postID) {
	$raw = get_post_meta($postID, 'released')[0];
	$date = DateTime::createFromFormat('Ymd', $raw);
	return $date->format('F Y');
}

add_action('neve_before_content', 'project_metadata_render');
