<?php
extract( shortcode_atts( array(
	'accent_color' => '',
	'el_class' => ''
), $atts ) );

echo '<div class="vc_content_hr '.$el_class.'" style="border-top-color:'.$accent_color.'"></div>';