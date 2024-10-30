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

$mran_dropdown_icon = 'fa-toggle-off';
$mran_active_dp_icon = 'fa-toggle-on';
$mran_title_color = '';
$mran_title_background = '';
$mran_content_color = '';
$mran_content_background = '';

$args       	= apply_filters( 'mran_accordion_args', $mran_args );
$templates  	= apply_filters( 'mran_accordion_templates', $mran_templates );
$style      	= apply_filters( 'mran_accordion_styles', $mran_style );
$active_item    = absint(apply_filters( 'mran_accordion_activeitem', $mran_active_item ) );
$content_type   = apply_filters( 'mran_accordion_content_type', $mran_content_type );

$dropdown_icon	= apply_filters( 'mran_dropdown_icon', $mran_dropdown_icon);
$active_dp_icon	= apply_filters( 'mran_active_dp_icon', $mran_active_dp_icon);
$title_color	= apply_filters( 'mran_title_color', $mran_title_color);
$title_background	= apply_filters( 'mran_title_background', $mran_title_background);
$content_color	= apply_filters( 'mran_content_color', $mran_content_color);
$content_background	= apply_filters( 'mran_content_background', $mran_content_background);
$query      = new WP_Query( $args );
if ( $query->have_posts() ):
	?>
	<div class="mran-accordion-template mran-shortcode mran-<?php echo $templates; ?>">
		<div class="mran-accordion <?php echo $style; ?>">
			<ul class="mran-accordion-list">
				<?php 
				$current_item = 0;
				$current_icon = $dropdown_icon;
				while ( $query->have_posts() ):$query->the_post();
					$mran_post_slug = get_post_field( 'post_name', get_the_ID() ); 
					$current_item++;
					$current_icon = ($current_item==$active_item) ? $active_dp_icon : $dropdown_icon;
					$mran_active_class = ($current_item==$active_item) ? ' current ' : '';
					$mran_active_css = ($current_item==$active_item) ? ' display:block; ' : '';
					?>
					<li class="mran-accordion-item-wrap">
						<div class="mran-accordion-title <?php echo esc_attr($mran_active_class); ?>" style="background:<?php echo sanitize_hex_color($title_background); ?>; color:<?php echo sanitize_hex_color($title_color); ?>;">
							<span data-href="#mran_<?php echo $mran_post_slug.get_the_ID(); ?>"><?php the_title(); ?></span>
							<?php if(!empty($dropdown_icon)): ?>
								<i
									class="mran-toggle-icon fa <?php echo esc_attr($current_icon); ?>" 
									data-dropdown-icon="<?php echo esc_attr($dropdown_icon); ?>" 
									data-active-dp-icon="<?php echo esc_attr($active_dp_icon); ?>" 
									style="color:<?php echo sanitize_hex_color($title_color); ?>;"
								></i>
							<?php endif; ?>
						</div>
						<div class="mran-content <?php echo esc_attr($mran_active_class); ?>" id="mran_<?php echo $mran_post_slug.get_the_ID(); ?>"  style="background:<?php echo sanitize_hex_color($content_background); ?>; color:<?php echo sanitize_hex_color($content_color); ?>; <?php echo esc_attr($mran_active_css); ?>">
							<div class="mran-content-wraper">
								<?php
								if($content_type=='content'){
									the_content();
								}else{
									the_excerpt();
								}
								?>
							</div>
						</div>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
	</div>
	<?php
endif;
wp_reset_query();
wp_reset_postdata();
