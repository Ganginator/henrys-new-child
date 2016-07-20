<?php

// remove elements
vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_text");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_archives");
vc_remove_element("vc_wp_rss");
vc_remove_element("vc_posts_slider");
vc_remove_element("vc_posts_grid");
vc_remove_element("vc_carousel");
vc_remove_element("vc_cta_button");
vc_remove_element("vc_cta_button2");
vc_remove_element("vc_button");
vc_remove_element("vc_flickr");
vc_remove_element("vc_gallery");
vc_remove_element("vc_images_carousel");
vc_remove_element("vc_tour");
vc_remove_element("vc_gmaps");
vc_remove_element("vc_message");

/*vc_remove_element("product");
vc_remove_element("add_to_cart");
vc_remove_element("add_to_cart_url");
vc_remove_element("product_page");*/




// [vc_row]
vc_add_param("vc_row", array(
	"type" => "dropdown",
	"class" => "hide_in_vc_editor",
	"admin_label" => true,
	"heading" => "Row Type",
	"param_name" => "type",
	"value" => array(
		"Full Width" => "full_width",
		"Boxed" => "boxed"
	)
));

vc_add_param("vc_row", array(
	"type" => "textfield",
	"class" => "hide_in_vc_editor",
	"admin_label" => true,
	"heading" => "Height",
	"param_name" => "height",
	"value" => "",
	"description" => ""
));

vc_add_param("vc_row", array(
	"type" => "dropdown",
	"class" => "hide_in_vc_editor",
	"admin_label" => true,
	"heading" => "Background Style",
	"param_name" => "background_style",
	"value" => array(
		"Normal" => "normal",
		"Parallax" => "parallax"
	)
));

vc_add_param("vc_row", array(
	"type" => "dropdown",
	"class" => "hide_in_vc_editor",
	"admin_label" => true,
	"heading" => "Columns Height",
	"param_name" => "columns_height",
	"value" => array(
		"Normal" => "normal_height",
		"Fit Columns Height" => "adjust_cols_height"
	)
));





// [vc_row_inner]
vc_add_param("vc_row_inner", array(
	"type" => "dropdown",
	"class" => "hide_in_vc_editor",
	"admin_label" => true,
	"heading" => "Row Type",
	"param_name" => "type",
	"value" => array(
		"Full Width" => "full_width",
		"Boxed" => "boxed"
	)
));







// [vc_separator]
vc_remove_param("vc_separator", "color");
vc_remove_param("vc_separator", "el_width");
vc_remove_param("vc_separator", "style");




// [vc_text_separator]
vc_remove_param("vc_text_separator", "style");

vc_add_param("vc_text_separator", array(
	"type" => "textfield",
	"class" => "hide_in_vc_editor",
	"admin_label" => false,
	"heading" => "Font Size",
	"param_name" => "font_size",
	"value" => "",
	"description" => "ex.: 18px"
));

vc_add_param("vc_text_separator", array(
	"type" => "dropdown",
	"class" => "hide_in_vc_editor",
	"admin_label" => false,
	"heading" => "Border",
	"param_name" => "style",
	"value" => array(
		"No Border" => "no_border",
		"Border" => "border"
	)
));




// [vc_button2]
$setting = array (
  'name' => 'Button'
);
vc_map_update('vc_button2', $setting);

vc_remove_param("vc_button2", "style");
vc_remove_param("vc_button2", "color");

vc_add_param("vc_button2", array(
	"type" => "dropdown",
	"class" => "hide_in_vc_editor",
	"admin_label" => true,
	"heading" => "Style",
	"param_name" => "style",
	"value" => array(
		"Square" => "square",
		"Square Outlined" => "square_outlined",
		"Rouded" => "rounded",
		"Rouded Outlined" => "rounded_outlined",
		"Link" => "link"
	)
));

vc_add_param("vc_button2", array(
	"type" => "dropdown",
	"class" => "hide_in_vc_editor",
	"admin_label" => true,
	"heading" => "Align",
	"param_name" => "align",
	"value" => array(
		"Left" => "left",
		"Center" => "center",
		"Right" => "right"
	)
));

vc_add_param("vc_button2", array(
	"type"			=> "colorpicker",
	"holder"		=> "div",
	"class" => "hide_in_vc_editor",
	"admin_label" => true,
	"heading"		=> "Text Color",
	"param_name"	=> "text_color",
	"value"			=> "#fff",
));

vc_add_param("vc_button2", array(
	"type"			=> "colorpicker",
	"holder"		=> "div",
	"class" => "hide_in_vc_editor",
	"admin_label" => true,
	"heading"		=> "Background Color",
	"param_name"	=> "bg_color",
	"value"			=> "#000",
	"dependency" 	=> Array('element' => "style", 'value' => array('square','rounded'))
));




// [vc_widget_sidebar]
$setting = array (
  'category' => 'Content'
);
//vc_map_update('vc_widget_sidebar', $setting);

vc_remove_param("vc_widget_sidebar", "title");




// [vc_raw_html]
$setting = array (
  'category' => 'Content'
);
//vc_map_update('vc_raw_html', $setting);




// [vc_raw_js]
$setting = array (
  'category' => 'Content'
);
//vc_map_update('vc_raw_js', $setting);




// [vc_single_image]
vc_remove_param("vc_single_image", "title");
vc_remove_param("vc_single_image", "img_size");

vc_add_param("vc_single_image", array(
	"type" => "textfield",
	"class" => "hide_in_vc_editor",
	"admin_label" => false,
	"heading" => "Image size",
	"param_name" => "img_size",
	"value" => "full",
	"description" => 'Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.'
));





// [vc_gmaps]
//vc_remove_param("vc_gmaps", "title");




// [vc_accordion]
vc_remove_param("vc_accordion", "title");




// [vc_progress_bar]
vc_remove_param("vc_progress_bar", "title");
vc_remove_param("vc_progress_bar", "options");



// [vc_pie]
vc_add_param("vc_pie", array(
	"type" => "dropdown",
	"class" => "hide_in_vc_editor",
	"admin_label" => true,
	"heading" => "Type",
	"param_name" => "pie_type",
	"value" => array(
		"With Pie" => "with_pie",
		"Without Pie" => "without_pie"
	)
));



// [vc_tabs]
vc_remove_param("vc_tabs", "title");




// [contact-form-7]
if (function_exists('wpcf7')) {
	vc_remove_param("contact-form-7", "title");
}

// [rev_slider_vc]
if (function_exists('rev_slider_shortcode')) {
	vc_remove_param("rev_slider_vc", "title");
}