<?php

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://themecentury.com/
 * @since      1.0.0
 *
 * @package    Master_Accordion
 * @subpackage Master_Accordion/public/partials
 */
?>
	<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

$mran_args       	= array();
$mran_templates  	= mran_sanitize_accordion_templates();
$mran_style      	= mran_accordion_styles();
$mran_active_item	= 1;
$mran_content_type 	= mran_sanitize_accordion_content_type();

$mran_tab_icon = '';
$mran_title_color = '';
$mran_title_background = '';
$mran_content_color = '';
$mran_content_background = '';

$args       	= apply_filters( 'mran_tab_args', $mran_args );

$content_type   = apply_filters( 'mran_tab_content_type', $mran_content_type );

$templates  	= apply_filters( 'mran_tab_templates', $mran_templates );
$style      	= apply_filters( 'mran_tab_styles', $mran_style );
$active_item    = absint(apply_filters( 'mran_tab_activeitem', $mran_active_item ) );

$tab_icon		= apply_filters( 'mran_tab_icon', $mran_tab_icon);
$title_color	= apply_filters( 'mran_title_color', $mran_title_color);
$title_background	= apply_filters( 'mran_title_background', $mran_title_background);
$content_color	= apply_filters( 'mran_content_color', $mran_content_color);
$content_background	= apply_filters( 'mran_content_background', $mran_content_background);

$query      = new WP_Query( $args );
if ( $query->have_posts() ):
	?>
	<div class="mran-tab-template mran-tab-shortcode mran-tab-<?php echo esc_attr($templates); ?>">
		<div class="mran-tab <?php echo esc_attr($style); ?>">
			<ul class="mran-tab-list">
				<?php 
				$current_item = 0;
				while ( $query->have_posts() ):$query->the_post(); 
					$current_item++;
					$tab_class = ($current_item==$active_item) ? ' current ' : '';
					?>
					<li class="mran-tab-item-wrap">
						<div class="mran-tab-title <?php echo esc_attr($tab_class); ?>" style="background:<?php echo sanitize_hex_color($title_background); ?>; color:<?php echo sanitize_hex_color($title_color); ?>;">
							<?php if(!empty($tab_icon)): ?>
								<i
									class="mran-toggle-icon fa <?php echo esc_attr($tab_icon); ?>" 
									style="color:<?php echo sanitize_hex_color($title_color); ?>;"
								></i>
							<?php endif; ?>
							<a class="mran-post-link" href="#post_tab_<?php echo get_the_ID(); ?>"><?php the_title(); ?></a>
						</div>
					</li>
				<?php endwhile; ?>
			</ul>
			<div class="mran-tab-content-wraper">
				<?php 
				$current_item = 0;
				while ( $query->have_posts() ):$query->the_post(); 
					$current_item++;
					$tab_class = ($current_item==$active_item) ? ' current ' : '';
					?>
					<div class="mran-tab-content <?php echo esc_attr($tab_class); ?>" id="post_tab_<?php echo get_the_ID(); ?>">
						<?php
							if($content_type=='content'){
								the_content();
							}else{
								the_excerpt();
							}
						?>
					</div>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
	<?php
endif;
wp_reset_query();
wp_reset_postdata();