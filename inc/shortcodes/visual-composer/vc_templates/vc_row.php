<?php

$output = $el_class = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $margin_bottom = $css = '';
extract(shortcode_atts(array(
    'el_class'        => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'font_color'      => '',
    'padding'         => '',
    'margin_bottom'   => '',
    'css' => '',
	"type" => 'full_width',
	"height" => '',
	"background_style" => '',
	"columns_height" => '',
), $atts));

wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style('js_composer_custom_css');

$el_class = $this->getExtraClass($el_class);

//$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'row '.get_row_css_class().$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'row vc_row wpb_row ' . ( $this->settings( 'base' ) === 'vc_row_inner' ? 'vc_inner ' : '' ) . get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$style = $this->buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);

$height_class = "";
if ($height != "") { $height_class = 'style="min-height:'.$height.'"'; }

if ($background_style == 'parallax'){
	$background_style_class = ' parallax';
	$stellar_attributes = ' data-stellar-background-ratio="0.5"';
} else {
	$background_style_class = ' no_parallax';
	$stellar_attributes = ' ';
}

$columns_height_class = " " . $columns_height;

if ($type == 'full_width') {
	
	$output .= '<div class="'.$css_class.$background_style_class.$columns_height_class.'"'.$style.$stellar_attributes.'><div '.$height_class.'>';
	$output .= wpb_js_remove_wpautop($content);
	$output .= '</div></div>'.$this->endBlockComment('row');
	
} else if ($type == 'boxed'){
	
	$output .= '<div class="boxed-row">';
	$output .= '<div class="'.$css_class.$columns_height_class.'"'.$style.$stellar_attributes.'>';
	$output .= wpb_js_remove_wpautop($content);
	$output .= '</div>'.$this->endBlockComment('row');
	$output .= '</div>';
	
}

echo $output;