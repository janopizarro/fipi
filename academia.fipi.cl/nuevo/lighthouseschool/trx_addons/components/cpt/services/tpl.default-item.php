<?php
/**
 * The style "default" of the Services
 *
 * @package WordPress
 * @subpackage ThemeREX Addons
 * @since v1.4
 */

$args = get_query_var('trx_addons_args_sc_services');

$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
$link = get_permalink();
if (empty($args['id'])) $args['id'] = 'sc_services_'.str_replace('.', '', mt_rand());
if (empty($args['featured'])) $args['featured'] = 'image';
if (empty($args['featured_position'])) $args['featured_position'] = 'top';
$svg_present = false;

if (!empty($args['slider'])) {
	?><div class="slider-slide swiper-slide"><?php
} else if ((int)$args['columns'] > 1) {
	?><div class="<?php echo esc_attr(trx_addons_get_column_class(1, $args['columns'])); ?>"><?php
}
?>
<div class="sc_services_item<?php
	echo isset($args['hide_excerpt']) && (int)$args['hide_excerpt'] > 0 ? ' without_content' : ' with_content';
	echo esc_html($args['featured']=='image'
					? ' with_image' 
					: ($args['featured']=='icon' 
						? ' with_icon' 
						: ' with_number'));
	echo ' sc_services_item_featured_'.esc_attr($args['featured_position']);
?>">
	<?php
	// Featured image or icon
	if ( has_post_thumbnail() && $args['featured']=='image') {
		trx_addons_get_template_part('templates/tpl.featured.php',
										'trx_addons_args_featured',
										apply_filters('trx_addons_filter_args_featured', array(
															'class' => 'sc_services_item_thumb',
															'hover' => 'zoomin',
															'thumb_size' => apply_filters('trx_addons_filter_thumb_size', trx_addons_get_thumb_size((int)$args['columns'] > 2 ? 'medium' : 'big'), 'services-default')
															),
														'services-default'
														)
									);
	} else if ($args['featured']=='icon' && !empty($meta['icon'])) {
		$svg = $img = '';
		if (trx_addons_is_url($meta['icon'])) {
			$img = $meta['icon'];
			$meta['icon'] = basename($meta['icon']);
		} else if (!empty($args['icons_animation']) && (int)$args['icons_animation'] > 0 && ($svg = trx_addons_get_file_dir('css/icons.svg/'.trx_addons_esc($meta['icon']).'.svg')) != '')
			$svg_present = true;
		?><a href="<?php echo esc_url($link); ?>"
			 id="<?php echo esc_attr($args['id'].'_'.trim($meta['icon'])); ?>"
			 class="sc_services_item_icon <?php
					echo !empty($svg) 
							? 'sc_icon_type_svg'
							: (!empty($img) 
								? 'sc_icon_type_img'
								: esc_attr($meta['icon'])
								);
					?>"<?php
			 if (!empty($meta['icon_color'])) {
				 echo ' style="color:'.esc_attr($meta['icon_color']).'"';
			 }
		?>><?php
			if (!empty($svg)) {
				trx_addons_show_layout(trx_addons_get_svg_from_file($svg));
			} else if (!empty($img)) {
				$attr = trx_addons_getimagesize($img);
				?><img class="sc_icon_as_image" src="<?php echo esc_url($img); ?>" <?php echo (!empty($attr[3]) ? ' '.trim($attr[3]) : ''); ?>><?php
			}
		?></a><?php
	} else {
		?><span class="sc_services_item_number"><?php
			$number = get_query_var('trx_addons_args_item_number');
			printf("%d", $number);
		?></span><?php
	}
	?>	
	<div class="sc_services_item_info">
		<div class="sc_services_item_header">
            <?php
            if ($args['type']=='numbered') {
                ?><span class="sc_services_item_number"><?php
                $number = get_query_var('trx_addons_args_item_number');
                printf("%d", $number);
                ?></span><?php
            }
            ?>
			<h4 class="sc_services_item_title"><a href="<?php echo esc_url($link); ?>"><?php the_title(); ?></a></h4>
			<div class="sc_services_item_subtitle"><?php trx_addons_show_layout(trx_addons_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_SERVICES_TAXONOMY));?></div>
		</div>
		<?php if (!isset($args['hide_excerpt']) || (int) $args['hide_excerpt']==0) { ?>
			<div class="sc_services_item_content"><?php the_excerpt(); ?></div>
			<div class="sc_services_item_button sc_item_button"><a href="<?php echo esc_url($link); ?>" class="sc_button"><?php esc_html_e('details', 'lighthouseschool'); ?></a></div>
		<?php } ?>
        <?php if ($args['type']=='blinked') { ?>
            <div class="sc_services_item_button sc_item_button"><a href="<?php echo esc_url($link); ?>" class="sc_button color_style_dark icon-arrow_down"></a></div>
		<?php } ?>
	</div>
</div>
<?php
if (!empty($args['slider']) || (int)$args['columns'] > 1) {
	?></div><?php
}
if (trx_addons_is_on(trx_addons_get_option('debug_mode')) && $svg_present) {
	wp_enqueue_script( 'vivus', trx_addons_get_file_url(TRX_ADDONS_PLUGIN_SHORTCODES . 'icons/vivus.js'), array('jquery'), null, true );
	wp_enqueue_script( 'trx-addons-sc-icons', trx_addons_get_file_url(TRX_ADDONS_PLUGIN_SHORTCODES . 'icons/icons.js'), array('jquery'), null, true );
}
?>