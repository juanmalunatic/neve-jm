<?php
/**
 * Template used for component rendering wrapper.
 *
 * Name:    Header Footer Grid
 *
 * @version 1.0.0
 * @package HFG
 */
namespace HFG;

use HFG\Core\Builder\Abstract_Builder;
use HFG\Core\Builder\Header as HeaderBuilder;

$interaction_type = row_setting( Abstract_Builder::LAYOUT_SETTING );
$classes          = [ 'header-menu-sidebar', 'menu-sidebar-panel', $interaction_type ];
$item_attributes  = apply_filters( 'neve_nav_toggle_data_attrs', '' );

?>
<div id="header-menu-sidebar" class="<?php echo esc_attr( join( ' ', $classes ) ); ?>">
	<div id="header-menu-sidebar-bg" class="header-menu-sidebar-bg">
		<div class="close-sidebar-panel navbar-toggle-wrapper">
			<button class="navbar-toggle active" <?php echo ( $item_attributes );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					aria-label="
				<?php
					esc_html_e( 'Navigation Menu', 'neve' );
				?>
				">
				<div class="bars">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</div>
				<span class="screen-reader-text">
				<?php
				esc_html_e( 'Toggle Navigation', 'neve' );
				?>
					</span>
			</button>
		</div>
		<div id="header-menu-sidebar-inner" class="header-menu-sidebar-inner">
			<!--div class="mobile-blog-name">
				<?php echo get_bloginfo( 'name' ); ?>
			</div-->
			<?php render_components( HeaderBuilder::BUILDER_NAME ); ?>
		</div>
	</div>
</div>
<div class="header-menu-sidebar-overlay"></div>

