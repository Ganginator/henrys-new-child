<?php

    if ( ! class_exists( 'houseofcoffee_Theme_Options' ) ) {

        class houseofcoffee_Theme_Options {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
                if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                    $this->initSettings();
                } else {
                    add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
                }

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }

            public function setSections() {

                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 * */
                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( '.', $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'houseofcoffee' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'houseofcoffee' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'houseofcoffee' ); ?>"/>
                    <?php endif; ?>

                    <h4><?php echo esc_html($this->theme->display( 'Name' )); ?></h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'houseofcoffee' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'houseofcoffee' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'houseofcoffee' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo esc_html($this->theme->display( 'Description' )); ?></p>
                        <?php
                            if ( $this->theme->parent() ) {
                                printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'houseofcoffee' ) . '</p>', __( 'http://codex.wordpress.org/Child_Themes', 'houseofcoffee' ), $this->theme->parent()->display( 'Name' ) );
                            }
                        ?>

                    </div>
                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                $sampleHTML = '';
                if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
                    Redux_Functions::initWpFilesystem();

                    global $wp_filesystem;

                    $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
                }

                // ACTUAL DECLARATION OF SECTIONS
				
                $this->sections[] = array(
                    'icon'   => 'fa fa-tachometer',
					'title'  => __( 'General', 'houseofcoffee' ),
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields' => array(

                        array (
							'title' => __('Favicon', 'houseofcoffee'),
							'subtitle' => __('<em>Upload your custom Favicon image. <br>.ico or .png file required.</em>', 'houseofcoffee'),
							'id' => 'favicon',
							'type' => 'media',
							'default' => array (
								'url' => get_template_directory_uri() . '/favicon.png',
							),
						),
						
                    ),
                );

                $this->sections[] = array(
                    'icon'   => 'fa fa-arrow-circle-up',
                    'title'  => __( 'Header', 'houseofcoffee' ),
                    'fields' => array(
						
						array(
                            'id'       => 'main_header_layout',
                            'type'     => 'image_select',
                            'compiler' => true,
                            'title'    => __( 'Header Layout', 'houseofcoffee' ),
                            'subtitle' => __( '<em>Select the Layout style for the Header.</em>', 'houseofcoffee' ),
                            'options'  => array(
                                '1' => array(
                                    'alt' => 'Layout 1',
                                    'img' => get_template_directory_uri() . '/images/theme_options/icons/header_1.png'
                                ),
                                '2' => array(
                                    'alt' => 'Layout 2',
                                    'img' => get_template_directory_uri() . '/images/theme_options/icons/header_2.png'
                                ),
                                '3' => array(
                                    'alt' => 'Layout 3',
                                    'img' => get_template_directory_uri() . '/images/theme_options/icons/header_3.png'
                                ),
                            ),
                            'default'  => '1'
                        ),
						
						array(
							'id'       => 'main_header_navigation_position_header_1',
							'type'     => 'button_set',
							'title'    => __( 'Navigation Alignment', 'houseofcoffee' ),
							'subtitle' => __( '<em>Set up the alignment for the Main Navigation.</em>', 'houseofcoffee' ),
							'options'  => array(
								'align_left'	=> '<i class="fa fa-align-left"></i> Left',
								'align_right' 	=> 'Right <i class="fa fa-align-right"></i>'
							),
							'default'  => 'align_left',
							'required' => array( 'main_header_layout', 'equals', array( '1' ) ),
						),
						
						array(
							'id'       => 'main_header_navigation_position_header_2',
							'type'     => 'button_set',
							'title'    => __( 'Navigation Position', 'houseofcoffee' ),
							'subtitle' => __( '<em>Specify the Main Header Navigation Position.</em>', 'houseofcoffee' ),
							'options'  => array(
								'1' => '&nbsp;&nbsp;&nbsp; <i class="fa fa-align-right"></i> &nbsp;Align to Logo&nbsp; <i class="fa fa-align-left"></i> &nbsp;&nbsp;&nbsp;',
								'2' => '<i class="fa fa-align-left"></i> &nbsp;&nbsp;&nbsp; Align to Edges &nbsp;&nbsp;&nbsp; <i class="fa fa-align-right"></i>',
							),
							'default'  => '1',
							'required' => array( 'main_header_layout', 'equals', array( '2' ) ),
						),
						
						array (
							'id' => 'main_nav_font_options',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;"><i class="fa fa-font"></i> Font Settings</h3>',
						),
						
						array(
							'title' => __('Main Header Font Size', 'houseofcoffee'),
							'subtitle' => __('<em>Drag the slider to set the Main Header Font Size.</em>', 'houseofcoffee'),
							'id' => 'main_header_font_size',
							'type' => 'slider',
							"default" => 13,
							"min" => 11,
							"step" => 1,
							"max" => 16,
							'display_value' => 'text'
						),
						
						array (
							'title' => __('Main Header Font Color', 'houseofcoffee'),
							'subtitle' => __('<em>The Main Header Font Color.</em>', 'houseofcoffee'),
							'id' => 'main_header_font_color',
							'type' => 'color',
							'default' => '#fff',
							'transparent' => false
						),
						
						array (
							'id' => 'header_size_spacing',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;"><i class="fa fa-sliders"></i> Spacing and Size</h3>',
						),
						
						array(
							'title' => __('Spacing Above the Logo', 'houseofcoffee'),
							'subtitle' => __('<em>Drag the slider to set the Spacing Above the Logo.</em>', 'houseofcoffee'),
							'id' => 'spacing_above_logo',
							'type' => 'slider',
							"default" => 15,
							"min" => 0,
							"step" => 1,
							"max" => 200,
							'display_value' => 'text'
						),
						
						array(
							'title' => __('Spacing Below the Logo', 'houseofcoffee'),
							'subtitle' => __('<em>Drag the slider to set the Spacing Below the Logo.</em>', 'houseofcoffee'),
							'id' => 'spacing_below_logo',
							'type' => 'slider',
							"default" => 15,
							"min" => 0,
							"step" => 1,
							"max" => 200,
							'display_value' => 'text'
						),						

						array(
							'id'       => 'header_width',
							'type'     => 'button_set',
							'title'    => __( 'Header Width', 'houseofcoffee' ),
							'subtitle' => __( '<em>Set up the width of the Header.</em>', 'houseofcoffee' ),
							'options'  => array(
								'full'	=> 'Full',
								'custom' 	=> 'Custom'
							),
							'default'  => 'custom',
						),
						
						array(
							'title' => __('Header Max Width', 'houseofcoffee'),
							'subtitle' => __('<em>Drag the slider to set the Header Max Width. (default: 1680)</em>', 'houseofcoffee'),
							'id' => 'header_max_width',
							'type' => 'slider',
							"default" => 1680,
							"min" => 960,
							"step" => 1,
							"max" => 1680,
							'display_value' => 'text',
							'required' => array( 'header_width', 'equals', array( 'custom' ) ),
						),	
						
						array (
							'id' => 'header_bg_options',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;"><i class="fa fa-eyedropper"></i> Header Background</h3>',
						),

						/*array (
							'title' => __('Header Background Color', 'houseofcoffee'),
							'subtitle' => __('<em>The Main Header background color.</em>', 'houseofcoffee'),
							'id' => 'main_header_background_color',
							'type' => 'color',
							'default' => '#333333',
							'transparent' => false,
						),*/
						
						array(
                            'id'       		=> 'main_header_background',
                            'type'     		=> 'background',
                            'title'    		=> "Header Background Color",
                            'subtitle' 		=> "<em>The Main Header background.</em>",
                            'default'  => array(
								'background-color' => '#333333',
							),
							'transparent' 	=> false,
                        ),						

                    ),
                );

                $this->sections[] = array(
                    'icon'       => 'fa fa-angle-right',
                    'title'      => __( 'Header Elements', 'houseofcoffee' ),
                    'subsection' => true,
                    'fields'     => array(
						
						array (
							'id' => 'wishlist_header_info',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;"><i class="fa fa-heart-o"></i> Wishlist Icon</h3>',
						),
						
						array (
							'title' => __('Main Header Wishlist', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable the Wishlist in the Header.</em>', 'houseofcoffee'),
							'id' => 'main_header_wishlist',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Main Header Wishlist Icon', 'houseofcoffee'),
							'subtitle' => __('<em>Upload your custom Wishlist Icon image (32x32 px).<br />Ignore if you want to use the default icon.</em>', 'houseofcoffee'),
							'id' => 'main_header_wishlist_icon',
							'type' => 'media',
							'required' => array( 'main_header_wishlist', 'equals', array( '1' ) ),
						),
						
						array (
							'id' => 'bag_header_info',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;"><i class="fa fa-shopping-cart"></i> Shopping Cart Icon</h3>',
						),
						
						array (
							'title' => __('Main Header Shopping Bag', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable the Shopping Bag in the Header.</em>', 'houseofcoffee'),
							'id' => 'main_header_shopping_bag',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Main Header Shopping Bag Icon', 'houseofcoffee'),
							'subtitle' => __('<em>Upload your custom Shopping Bag Icon image (32x32 px).<br />Ignore if you want to use the default icon.</em>', 'houseofcoffee'),
							'id' => 'main_header_shopping_bag_icon',
							'type' => 'media',
							'required' => array( 'main_header_shopping_bag', 'equals', array( '1' ) ),
						),
						
						array (
							'id' => 'search_header_info',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;"><i class="fa fa-search"></i> Search Icon</h3>',
						),
						
						array (
							'title' => __('Main Header Search bar', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable the Search Bar in the Header.</em>', 'houseofcoffee'),
							'id' => 'main_header_search_bar',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Main Header Search bar Icon', 'houseofcoffee'),
							'subtitle' => __('<em>Upload your custom Search bar Icon image (32x32 px).<br />Ignore if you want to use the default icon.</em>', 'houseofcoffee'),
							'id' => 'main_header_search_bar_icon',
							'type' => 'media',
							'required' => array( 'main_header_search_bar', 'equals', array( '1' ) ),
						),
						
						array (
							'id' => 'offcanvas_header_info',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;"><i class="fa fa-bars"></i> Off-Canvas Navigation / Hamburger Icon</h3>',
						),
						
						array (
							'title' => __('Main Header Off-Canvas Navigation', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable the Off-Canvas Navigation.</em>', 'houseofcoffee'),
							'id' => 'main_header_off_canvas',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 0,
						),
						
						array (
							'title' => __('Main Header Off-Canvas Icon', 'houseofcoffee'),
							'subtitle' => __('<em>Upload your custom Off-Canvas Icon image (32x32 px).<br />Ignore if you want to use the default icon.</em>', 'houseofcoffee'),
							'id' => 'main_header_off_canvas_icon',
							'type' => 'media',
							'required' => array( 'main_header_off_canvas', 'equals', array( '1' ) ),
						),
                        
                    )
                );
				
				$this->sections[] = array(
                    'icon'       => 'fa fa-angle-right',
                    'title'      => __( 'Logo', 'houseofcoffee' ),
                    'subsection' => true,
                    'fields'     => array(
					
						array (
							'title' => __('Your Logo', 'houseofcoffee'),
							'subtitle' => __('<em>Upload your logo image.</em>', 'houseofcoffee'),
							'id' => 'site_logo',
							'type' => 'media',
						),
						
						array (
							'title' => __('Alternative Logo', 'houseofcoffee'),
							'subtitle' => __('<em>The Alternative Logo is used on the <strong>Sticky Header</strong> and <strong>Mobile Devices</strong>.</em>', 'houseofcoffee'),
							'id' => 'sticky_header_logo',
							'type' => 'media'
						),
						
						array(
							'title' => __('Logo Container Min Width', 'houseofcoffee'),
							'subtitle' => __('<em>Drag the slider to set the logo container min width.</em>', 'houseofcoffee'),
							'id' => 'logo_min_height',
							'type' => 'slider',
							"default" => 300,
							"min" => 0,
							"step" => 1,
							"max" => 600,
							'display_value' => 'text',
							'required' => array( 'main_header_layout', 'equals', array( '2' ) ),
						),
						
						array(
							'title' => __('Logo Height', 'houseofcoffee'),
							'subtitle' => __('<em>Drag the slider to set the logo height <br/>(ignored if there\'s no uploaded logo).</em>', 'houseofcoffee'),
							'id' => 'logo_height',
							'type' => 'slider',
							"default" => 33,
							"min" => 0,
							"step" => 1,
							"max" => 300,
							'display_value' => 'text',
						),
                        
                    )
                );
				
				$this->sections[] = array(
                    'icon'       => 'fa fa-angle-right',
                    'title'      => __( 'Header Transparency', 'houseofcoffee' ),
                    'subsection' => true,
                    'fields'     => array(
					
						array (
							'title' => __('Header Transparency (Global)', 'houseofcoffee'),
							'subtitle' => __('<em>When enabled, it sets the header to be transparent on all aplicable pages.</em>', 'houseofcoffee'),
							'id' => 'main_header_transparency',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 0,
						),
						
						array(
							'id'       => 'main_header_transparency_scheme',
							'type'     => 'button_set',
							'title'    => __( 'Default Color Scheme (Global)', 'houseofcoffee' ),
							'subtitle' => __( '<em>Set a default color scheme for the transparent header to be inherited by all the pages. The color scheme refers to the elements in the header (navigation, icons, etc.). </em>', 'houseofcoffee' ),
							'options'  => array(
								'transparency_light'	=> '<i class="fa fa-circle-o"></i> Light',
								'transparency_dark' 	=> '<i class="fa fa-circle"></i> Dark',
							),
							'default'  => 'transparency_light',
						),
						
						array (
							'id' => 'light_scheme',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;"><i class="fa fa-circle-o"></i> Light Color Scheme</h3>',
						),						
						
						array (
							'title' => __('Transparent Header Light Color', 'houseofcoffee'),
							'subtitle' => __('<em>The Transparent Header Light Color.</em>', 'houseofcoffee'),
							'id' => 'main_header_transparent_light_color',
							'type' => 'color',
							'default' => '#fff',
							'transparent' => false
						),
						
						array (
							'title' => __('Logo for Light Transparent Header', 'houseofcoffee'),
							'subtitle' => __('<em>Upload your Logo for Light Transparent Header.</em>', 'houseofcoffee'),
							'id' => 'light_transparent_header_logo',
							'type' => 'media'
						),

						array (
							'id' => 'dark_scheme',
							'icon' => true,
							'type' => 'info',
							'raw' => '<h3 style="margin: 0;"><i class="fa fa-circle"></i> Dark Color Scheme</h3>',
						),	
						
						array (
							'title' => __('Transparent Header Dark Color', 'houseofcoffee'),
							'subtitle' => __('<em>The Transparent Header Dark Color.</em>', 'houseofcoffee'),
							'id' => 'main_header_transparent_dark_color',
							'type' => 'color',
							'default' => '#000',
							'transparent' => false
						),
						
						array (
							'title' => __('Logo for Dark Transparent Header', 'houseofcoffee'),
							'subtitle' => __('<em>Upload your Logo for Dark Transparent Header.</em>', 'houseofcoffee'),
							'id' => 'dark_transparent_header_logo',
							'type' => 'media'
						),						
                        
                    )
                );
				
				$this->sections[] = array(
                    'icon'       => 'fa fa-angle-right',
                    'title'      => __( 'Top Bar', 'houseofcoffee' ),
                    'subsection' => true,
                    'fields'     => array(
					
					array (
						'title' => __('Top Bar', 'houseofcoffee'),
						'subtitle' => __('<em>Enable / Disable the Top Bar.</em>', 'houseofcoffee'),
						'id' => 'top_bar_switch',
						'on' => __('Enabled', 'houseofcoffee'),
						'off' => __('Disabled', 'houseofcoffee'),
						'type' => 'switch',
						'default' => 0,
					),
					
					array (
						'title' => __('Top Bar Background Color', 'houseofcoffee'),
						'subtitle' => __('<em>The Top Bar background color.</em>', 'houseofcoffee'),
						'id' => 'top_bar_background_color',
						'type' => 'color',
						'default' => '#333333',
						'required' => array('top_bar_switch','=','1')
					),
					
					array (
						'title' => __('Top Bar Text Color', 'houseofcoffee'),
						'subtitle' => __('<em>Specify the Top Bar Typography.</em>', 'houseofcoffee'),
						'id' => 'top_bar_typography',
						'type' => 'color',
						'default' => '#fff',
						'transparent' => false,
						'required' => array('top_bar_switch','=','1')
					),
					
					array (
						'title' => __('Top Bar Text', 'houseofcoffee'),
						'subtitle' => __('<em>Type in your Top Bar info here.</em>', 'houseofcoffee'),
						'id' => 'top_bar_text',
						'type' => 'text',
						'default' => 'Free Shipping on All Orders Over $75!',
						'required' => array('top_bar_switch','=','1')
					),
					
					array(
						'id'       => 'top_bar_navigation_position',
						'type'     => 'button_set',
						'title'    => __( 'Top Bar Navigation Position', 'houseofcoffee' ),
						'subtitle' => __( '<em>Specify the Navigation Position in the Top Bar.</em>', 'houseofcoffee' ),
						//Must provide key => value pairs for radio options
						'options'  => array(
							'left' => 'Left',
							'right' => 'Right'
						),
						'default'  => 'right',
						'required' => array('top_bar_switch','=','1')
					),
					
					array (
						'title' => __('Top Bar Social Icons', 'houseofcoffee'),
						'subtitle' => __('<em>Enable / Disable the Top Bar Social Icons.</em>', 'houseofcoffee'),
						'id' => 'top_bar_social_icons',
						'on' => __('Enabled', 'houseofcoffee'),
						'off' => __('Disabled', 'houseofcoffee'),
						'type' => 'switch',
						'default' => 1,
						'required' => array('top_bar_switch','=','1')
					),
                        
                    )
                );

				$this->sections[] = array(
                    'icon'       => 'fa fa-angle-right',
                    'title'      => __( 'Sticky Header', 'houseofcoffee' ),
                    'subsection' => true,
                    'fields'     => array(
					
						array (
							'title' => __('Sticky Header', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable the Sticky Header.</em>', 'houseofcoffee'),
							'id' => 'sticky_header',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Sticky Header Background Color', 'houseofcoffee'),
							'subtitle' => __('<em>The Sticky Header background Color.</em>', 'houseofcoffee'),
							'id' => 'sticky_header_background_color',
							'type' => 'color',
							'default' => '#333333',
							'transparent' => false,
							'required' => array('sticky_header','=','1')
						),
						
						array (
							'title' => __('Sticky Header Color', 'houseofcoffee'),
							'subtitle' => __('<em>The Sticky Header Color.</em>', 'houseofcoffee'),
							'id' => 'sticky_header_color',
							'type' => 'color',
							'default' => '#fff',
							'transparent' => false,
							'required' => array('sticky_header','=','1')
						),
                        
                    )
                );

                $this->sections[] = array(
                    'icon'    => 'fa fa-arrow-circle-down',
                    'title'   => __( 'Footer', 'houseofcoffee' ),
                    'fields'  => array(
                        
						array (
							'title' => __('Footer Background Color', 'houseofcoffee'),
							'subtitle' => __('<em>The Top Bar background color.</em>', 'houseofcoffee'),
							'id' => 'footer_background_color',
							'type' => 'color',
							'default' => '#F4F4F4',
						),
						
						array (
							'title' => __('Footer Text', 'houseofcoffee'),
							'subtitle' => __('<em>Specify the Footer Text Color.</em>', 'houseofcoffee'),
							'id' => 'footer_texts_color',
							'type' => 'color',
							'transparent' => false,
							'default' => '#868686',
						),
						
						array (
							'title' => __('Footer Links', 'houseofcoffee'),
							'subtitle' => __('<em>Specify the Footer Links Color.</em>', 'houseofcoffee'),
							'id' => 'footer_links_color',
							'type' => 'color',
							'transparent' => false,
							'default' => '#333333',
						),
						
						array (
							'title' => __('Social Icons', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable the Social Icons.</em>', 'houseofcoffee'),
							'id' => 'footer_social_icons',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Footer Copyright Text', 'houseofcoffee'),
							'subtitle' => __('<em>Enter your copyright information here.</em>', 'houseofcoffee'),
							'id' => 'footer_copyright_text',
							'type' => 'text',
							'default' => '&copy; <a href=\'http://www.none.com/\'>none</a> - Elite ThemeForest Author.',
						),
						
                    )
                );
				
				$this->sections[] = array(
                    'icon'   => 'fa fa-list-alt',
                    'title'  => __( 'Blog', 'houseofcoffee' ),
                    'fields' => array(
                        
						array (
							'title' => __('Blog with Sidebar', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable the Sidebar on Blog.<em>', 'houseofcoffee'),
							'id' => 'sidebar_blog_listing',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 0,
						),
						
						/*array (
							'title' => __('Featured image on Blog Post', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable Featured image on Blog Post.<em>', 'houseofcoffee'),
							'id' => 'blog_post_featured_image',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),*/
						
                    )
                );

                $this->sections[] = array(
                    'icon'   => 'fa fa-shopping-cart',
                    'title'  => __( 'Shop', 'houseofcoffee' ),
                    'fields' => array(
                        
						array (
							'title' => __('Catalog Mode', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable the Catalog Mode.</em>', 'houseofcoffee'),
							'desc' => __('<em>When enabled, the feature Turns Off the shopping functionality of WooCommerce.</em>', 'houseofcoffee'),
							'id' => 'catalog_mode',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
						),
						
						array (
							'title' => __('Breadcrumbs', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable the Breadcrumbs.</em>', 'houseofcoffee'),
							'id' => 'breadcrumbs',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Number of Products per Column', 'houseofcoffee'),
							'subtitle' => __('<em>Drag the slider to set the number of products per column <br />to be listed on the shop page and catalog pages.</em>', 'houseofcoffee'),
							'id' => 'products_per_column',
							'min' => '2',
							'step' => '1',
							'max' => '6',
							'type' => 'slider',
							'default' => '6',
						),
						
						array (
							'title' => __('Number of Products per Page', 'houseofcoffee'),
							'subtitle' => __('<em>Drag the slider to set the number of products per page <br />to be listed on the shop page and catalog pages.</em>', 'houseofcoffee'),
							'id' => 'products_per_page',
							'min' => '1',
							'step' => '1',
							'max' => '48',
							'type' => 'slider',
							'edit' => '1',
							'default' => '18',
						),
						
						array (
							'title' => __('Sidebar Style', 'houseofcoffee'),
							'subtitle' => __('<em>Choose the Shop Sidebar Style.<em>', 'houseofcoffee'),
							'id' => 'sidebar_style',
							'on' => __('On Page', 'houseofcoffee'),
							'off' => __('Off-Canvas', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Second Image on Catalog Page (Hover)', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable the Second Image on Product Listing.</em>', 'houseofcoffee'),
							'id' => 'second_image_product_listing',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Ratings on Catalog Page', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable Ratings on Catalog Page.</em>', 'houseofcoffee'),
							'id' => 'ratings_catalog_page',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
                    )
                );

                $this->sections[] = array(
                    'icon'   => 'fa fa-archive',
                    'title'  => __( 'Product Page', 'houseofcoffee' ),
                    'fields' => array(
                        
						array (
							'title' => __('Product Gallery Zoom', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable Product Gallery Zoom.<em>', 'houseofcoffee'),
							'id' => 'product_gallery_zoom',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Related Products', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable Related Products.<em>', 'houseofcoffee'),
							'id' => 'related_products',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Sharing Options', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable Sharing Options.<em>', 'houseofcoffee'),
							'id' => 'sharing_options',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
						array (
							'title' => __('Review Tab', 'houseofcoffee'),
							'subtitle' => __('<em>Enable / Disable Review Tab.<em>', 'houseofcoffee'),
							'id' => 'review_tab',
							'on' => __('Enabled', 'houseofcoffee'),
							'off' => __('Disabled', 'houseofcoffee'),
							'type' => 'switch',
							'default' => 1,
						),
						
                    )
                );
				
				$this->sections[] = array(
                    'icon'   => 'fa fa-paint-brush',
                    'title'  => __( 'Styling', 'houseofcoffee' ),
                    'fields' => array(
                        
						array (
							'title' => __('Body Texts Color', 'houseofcoffee'),
							'subtitle' => __('<em>Body Texts Color of the site.</em>', 'houseofcoffee'),
							'id' => 'body_color',
							'type' => 'color',
							'transparent' => false,
							'default' => '#222222',
						),
						
						array (
							'title' => __('Headings Color', 'houseofcoffee'),
							'subtitle' => __('<em>Headings Color of the site.</em>', 'houseofcoffee'),
							'id' => 'headings_color',
							'type' => 'color',
							'transparent' => false,
							'default' => '#000000',
						),
						
						array (
							'title' => __('Main Theme Color', 'houseofcoffee'),
							'subtitle' => __('<em>The main color of the site.</em>', 'houseofcoffee'),
							'id' => 'main_color',
							'type' => 'color',
							'transparent' => false,
							'default' => '#EC7A5C',
						),
						
						array(
                            'id'       		=> 'main_background',
                            'type'     		=> 'background',
                            'title'    		=> "Body Background",
                            'subtitle' 		=> "<em>Body background with image, color, etc.</em>",
                            'default'  => array(
								'background-color' => '#fff',
							),
							'transparent' 	=> false,
                        ),
						
                    )
                );
				
				$this->sections[] = array(
                    'icon'   => 'fa fa-font',
                    'title'  => __( 'Typography', 'houseofcoffee' ),
                    'fields' => array(
                        
						array (
							'id' => 'source_fonts_info',
							'icon' => true,
							'type' => 'info',
							'raw' => __('<h3 style="margin: 0;"><i class="fa fa-font"></i> Font Sources</h3>', 'houseofcoffee'),
						),
						
						array(
							'title'    => __('Font Source', 'houseofcoffee'),
							'subtitle' => __('<em>Choose the Font Source</em>', 'houseofcoffee'),
							'id'       => 'font_source',
							'type'     => 'radio',
							'options'  => array(
								'1' => 'Standard + Google Webfonts',
								'2' => 'Google Custom',
								'3' => 'Adobe Typekit'
							),
							'default' => '1'
						),
						
						// Google Code
						array(
							'id'=>'font_google_code',
							'type' => 'text',
							'title' => __('Google Code', 'houseofcoffee'), 
							'subtitle' => __('<em>Paste the provided Google Code</em>', 'houseofcoffee'),
							'default' => '',
							'required' => array('font_source','=','2')
						),
						
						// Typekit ID
						array(
							'id'=>'font_typekit_kit_id',
							'type' => 'text',
							'title' => __('Typekit Kit ID', 'houseofcoffee'), 
							'subtitle' => __('<em>Paste the provided Typekit Kit ID.</em>', 'houseofcoffee'),
							'default' => '',
							'required' => array('font_source','=','3')
						),
						
						array (
							'id' => 'main_font_info',
							'icon' => true,
							'type' => 'info',
							'raw' => __('<h3 style="margin: 0;"><i class="fa fa-font"></i> Main Font</h3>', 'houseofcoffee'),
						),
						
						// Standard + Google Webfonts
						array (
							'title' => __('Font Face', 'houseofcoffee'),
							'subtitle' => __('<em>Pick the Main Font for your site.</em>', 'houseofcoffee'),
							'id' => 'main_font',
							'type' => 'typography',
							'line-height' => false,
							'text-align' => false,
							'font-style' => false,
							'font-weight' => false,
							'all_styles'=> true,
							'font-size' => false,
							'color' => false,
							'default' => array (
								'font-family' => 'Montserrat',
								'subsets' => '',
							),
							'required' => array('font_source','=','1')
						),
						
						// Google Custom						
						array (
							'title' => __('Google Font Face', 'houseofcoffee'),
							'subtitle' => __('<em>Enter your Google Font Name for the theme\'s Main Typography</em>', 'houseofcoffee'),
							'desc' => __('e.g.: open sans', 'houseofcoffee'),
							'id' => 'main_google_font_face',
							'type' => 'text',
							'default' => '',
							'required' => array('font_source','=','2')
						),
						
						// Adobe Typekit						
						array (
							'title' => __('Typekit Font Face', 'houseofcoffee'),
							'subtitle' => __('<em>Enter your Typekit Font Name for the theme\'s Main Typography</em>', 'houseofcoffee'),
							'desc' => __('e.g.: futura-pt', 'houseofcoffee'),
							'id' => 'main_typekit_font_face',
							'type' => 'text',
							'default' => '',
							'required' => array('font_source','=','3')
						),				
						
						
						array (
							'id' => 'secondary_font_info',
							'icon' => true,
							'type' => 'info',
							'raw' => __('<h3 style="margin: 0;"><i class="fa fa-font"></i> Secondary Font</h3>', 'houseofcoffee'),
						),
						
						// Standard + Google Webfonts
						array (
							'title' => __('Font Face', 'houseofcoffee'),
							'subtitle' => __('<em>Pick the Secondary Font for your site.</em>', 'houseofcoffee'),
							'id' => 'secondary_font',
							'type' => 'typography',
							'line-height' => false,
							'text-align' => false,
							'font-style' => false,
							'font-weight' => false,
							'all_styles'=> true,
							'font-size' => false,
							'color' => false,
							'default' => array (
								'font-family' => 'Pontano Sans',
								'subsets' => '',
							),
							'required' => array('font_source','=','1')
							
						),
						
						// Google Custom						
						array (
							'title' => __('Google Font Face', 'houseofcoffee'),
							'subtitle' => __('<em>Enter your Google Font Name for the theme\'s Secondary Typography</em>', 'houseofcoffee'),
							'desc' => __('e.g.: open sans', 'houseofcoffee'),
							'id' => 'secondary_google_font_face',
							'type' => 'text',
							'default' => '',
							'required' => array('font_source','=','2')
						),
						
						// Adobe Typekit						
						array (
							'title' => __('Typekit Font Face', 'houseofcoffee'),
							'subtitle' => __('<em>Enter your Typekit Font Name for the theme\'s Secondary Typography</em>', 'houseofcoffee'),
							'desc' => __('e.g.: futura-pt', 'houseofcoffee'),
							'id' => 'secondary_typekit_font_face',
							'type' => 'text',
							'default' => '',
							'required' => array('font_source','=','3')
						),
						
						
                    )
                );
				
				$this->sections[] = array(
                    'icon'   => 'fa fa-share-alt-square',
                    'title'  => __( 'Social Media', 'houseofcoffee' ),
                    'fields' => array(
                        
						array (
							'title' => __('<i class="fa fa-facebook"></i> Facebook', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Facebook profile URL here.</em>', 'houseofcoffee'),
							'id' => 'facebook_link',
							'type' => 'text',
							'default' => 'https://www.facebook.com/none',
						),
						
						array (
							'title' => __('<i class="fa fa-twitter"></i> Twitter', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Twitter profile URL here.</em>', 'houseofcoffee'),
							'id' => 'twitter_link',
							'type' => 'text',
							'default' => 'http://twitter.com/none',
						),
						
						array (
							'title' => __('<i class="fa fa-pinterest"></i> Pinterest', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Pinterest profile URL here.</em>', 'houseofcoffee'),
							'id' => 'pinterest_link',
							'type' => 'text',
							'default' => 'http://www.pinterest.com/',
						),
						
						array (
							'title' => __('<i class="fa fa-linkedin"></i> LinkedIn', 'houseofcoffee'),
							'subtitle' => __('<em>Type your LinkedIn profile URL here.</em>', 'houseofcoffee'),
							'id' => 'linkedin_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-google-plus"></i> Google+', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Google+ profile URL here.</em>', 'houseofcoffee'),
							'id' => 'googleplus_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-rss"></i> RSS', 'houseofcoffee'),
							'subtitle' => __('<em>Type your RSS Feed URL here.</em>', 'houseofcoffee'),
							'id' => 'rss_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-tumblr"></i> Tumblr', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Tumblr URL here.</em>', 'houseofcoffee'),
							'id' => 'tumblr_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-instagram"></i> Instagram', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Instagram profile URL here.</em>', 'houseofcoffee'),
							'id' => 'instagram_link',
							'type' => 'text',
							'default' => 'http://instagram.com/none',
						),
						
						array (
							'title' => __('<i class="fa fa-youtube-play"></i> Youtube', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Youtube profile URL here.</em>', 'houseofcoffee'),
							'id' => 'youtube_link',
							'type' => 'text',
							'default' => 'https://www.youtube.com/channel/UC88KP4HSF-TnVhPCJLe9P-g',
						),
						
						array (
							'title' => __('<i class="fa fa-vimeo-square"></i> Vimeo', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Vimeo profile URL here.</em>', 'houseofcoffee'),
							'id' => 'vimeo_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-behance"></i> Behance', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Behance profile URL here.</em>', 'houseofcoffee'),
							'id' => 'behance_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-dribbble"></i> Dribble', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Dribble profile URL here.</em>', 'houseofcoffee'),
							'id' => 'dribble_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-flickr"></i> Flickr', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Flickr profile URL here.</em>', 'houseofcoffee'),
							'id' => 'flickr_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-git"></i> Git', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Git profile URL here.</em>', 'houseofcoffee'),
							'id' => 'git_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-skype"></i> Skype', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Skype profile URL here.</em>', 'houseofcoffee'),
							'id' => 'skype_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-weibo"></i> Weibo', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Weibo profile URL here.</em>', 'houseofcoffee'),
							'id' => 'weibo_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-foursquare"></i> Foursquare', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Foursquare profile URL here.</em>', 'houseofcoffee'),
							'id' => 'foursquare_link',
							'type' => 'text',
						),
						
						array (
							'title' => __('<i class="fa fa-soundcloud"></i> Soundcloud', 'houseofcoffee'),
							'subtitle' => __('<em>Type your Soundcloud profile URL here.</em>', 'houseofcoffee'),
							'id' => 'soundcloud_link',
							'type' => 'text',
						),
						
                    )
                );
				
				$this->sections[] = array(
                    'icon'   => 'fa fa-code',
                    'title'  => __( 'Custom Code', 'houseofcoffee' ),
                    'fields' => array(
                        
						array (
							'title' => __('Custom CSS', 'houseofcoffee'),
							'subtitle' => __('<em>Paste your custom CSS code here.</em>', 'houseofcoffee'),
							'id' => 'custom_css',
							'type' => 'ace_editor',
							'mode' => 'css',
						),
						
						array (
							'title' => __('Header JavaScript Code', 'houseofcoffee'),
							'subtitle' => __('<em>Paste your custom JS code here. The code will be added to the header of your site.</em>', 'houseofcoffee'),
							'id' => 'header_js',
							'type' => 'ace_editor',
							'mode' => 'javascript',
						),
						
						array (
							'title' => __('Footer JavaScript Code', 'houseofcoffee'),
							'subtitle' => __('<em>Here is the place to paste your Google Analytics code or any other JS code you might want to add to be loaded in the footer of your website.</em>', 'houseofcoffee'),
							'id' => 'footer_js',
							'type' => 'ace_editor',
							'mode' => 'javascript',
						),
						
                    )
                );
				
				/*$this->sections[] = array(
                    'icon'   => 'fa fa-code',
                    'title'  => __( 'Theme Presets', 'houseofcoffee' ),
                    'fields' => array(
                        
						array (
							'id'       => 'theme_presets',
							'type'     => 'image_select', 
							'presets'  => true,
							'title'    => "Theme Presets",
							'subtitle' => "<em>Presets.</em>",
							'default'  => 0,
							'options'  => array(
								'1'      => array(
									'alt'   => 'Light', 
									'img'   => ReduxFramework::$_url . '../sample/presets/preset1.png', 
									'presets'   => array(
										'main_color'  => '#ff0000',
										'main_header_layout'  => 1,	
									)
								),
								'2'      => array(
									'alt'   => 'Dark', 
									'img'   => ReduxFramework::$_url . '../sample/presets/preset2.png', 
									'presets'   => array(
										'main_color'  => '#81d742',
										'main_header_layout'  => 2,
									)
								),
							),
						),
						
                    )
                );*/

                $this->sections[] = array(
                    'title'  => __( 'Import / Export', 'houseofcoffee' ),
                    'desc'   => __( 'Import and Export your Redux Framework settings from file, text or URL.', 'houseofcoffee' ),
                    'icon'   => 'fa fa-refresh',
                    'fields' => array(
                        array(
                            'id'         => 'opt-import-export',
                            'type'       => 'import_export',
                            'title'      => 'Import Export',
                            'subtitle'   => 'Save and restore your Redux options',
                            'full_width' => false,
                        ),
                    ),
                );

                /*$this->sections[] = array(
                    'type' => 'divide',
                );*/
				
				$theme_info = '<div class="redux-framework-section-desc">';
                $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __( '<strong>Theme URL:</strong> ', 'houseofcoffee' ) . '<a href="' . $this->theme->get( 'ThemeURI' ) . '" target="_blank">' . $this->theme->get( 'ThemeURI' ) . '</a></p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __( '<strong>Author:</strong> ', 'houseofcoffee' ) . $this->theme->get( 'Author' ) . '</p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __( '<strong>Version:</strong> ', 'houseofcoffee' ) . $this->theme->get( 'Version' ) . '</p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get( 'Description' ) . '</p>';
                $tabs = $this->theme->get( 'Tags' );
                if ( ! empty( $tabs ) ) {
                    $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __( '<strong>Tags:</strong> ', 'houseofcoffee' ) . implode( ', ', $tabs ) . '</p>';
                }
                $theme_info .= '</div>';

                /*$this->sections[] = array(
                    'icon'   => 'fa fa-info-circle',
                    'title'  => __( 'Theme Information', 'houseofcoffee' ),
                    'desc'   => __( '<p class="description">This is the Description. Again HTML is allowed</p>', 'houseofcoffee' ),
                    'fields' => array(
                        array(
                            'id'      => 'opt-raw-info',
                            'type'    => 'raw',
                            'content' => $item_info,
                        )
                    ),
                );*/
				
				/*if ( file_exists( dirname( __FILE__ ) . '/readme.html' ) ) {
                    $this->sections['theme_docs'] = array(
                        'icon'   => 'el-icon-list-alt',
                        'title'  => __( 'Documentation', 'houseofcoffee' ),
                        'fields' => array(
                            array(
                                'id'       => 'theme_documentaion',
                                'type'     => 'raw',
                                'markdown' => true,
                                'content'  => file_get_contents( dirname( __FILE__ ) . '/readme.html' )
                            ),
                        ),
                    );
                }*/
            }

            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             * */
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'             => 'houseofcoffee_theme_options',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'         => $theme->get( 'Name' ),
                    // Name that appears at the top of your panel
                    'display_version'      => $theme->get( 'Version' ),
                    // Version that appears at the top of your panel
                    'menu_type'            => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'       => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'           => __( 'Theme Options', 'houseofcoffee' ),
                    'page_title'           => __( 'Theme Options', 'houseofcoffee' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'       => 'AIzaSyDGJehqeZnxz4hABrNgi9KrBTG7ev6rIgY',
                    // Set it you want google fonts to update weekly. A google_api_key value is required.
                    'google_update_weekly' => false,
                    // Must be defined to add google fonts to the typography module
                    'async_typography'     => true,
                    // Use a asynchronous font on the front end or font string
                    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                    'admin_bar'            => true,
                    // Show the panel pages on the admin bar
                    'admin_bar_icon'     => 'dashicons-portfolio',
                    // Choose an icon for the admin bar menu
                    'admin_bar_priority' => 50,
                    // Choose an priority for the admin bar menu
                    'global_variable'      => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'             => false,
                    // Show the time the page took to load, etc
                    'update_notice'        => true,
                    // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                    'customizer'           => false,
                    // Enable basic customizer support
                    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                    // OPTIONAL -> Give you extra features
                    'page_priority'        => 3,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'          => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'     => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'            => '',
                    // Specify a custom URL to an icon
                    'last_tab'             => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'            => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'            => 'theme_options',
                    // Page slug used to denote the panel
                    'save_defaults'        => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'         => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'         => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export'   => true,
                    // Shows the Import/Export panel when not used as a field.

                    // CAREFUL -> These options are for advanced use only
                    'transient_time'       => 60 * MINUTE_IN_SECONDS,
                    'output'               => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'           => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    'footer_credit'     => '&nbsp;',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'             => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'          => false,
                    // REMOVE

                    // HINTS
                    'hints'                => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
                            'shadow'  => true,
                            'rounded' => false,
                            'style'   => '',
                        ),
                        'tip_position'  => array(
                            'my' => 'top left',
                            'at' => 'bottom right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    )
                );

                // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
                $this->args['admin_bar_links'][] = array(
                    'id'    => 'houseofcoffee-docs',
                    'href'   => 'http://support.none.com/hc/en-us/categories/200308912-houseofcoffee',
                    'title' => __( 'Documentation', 'houseofcoffee' ),
                );

                $this->args['admin_bar_links'][] = array(
                    'id'    => 'houseofcoffee-support',
                    'href'   => 'http://support.none.com/hc/en-us/requests/new',
                    'title' => __( 'Support', 'houseofcoffee' ),
                );

                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                $this->args['share_icons'][] = array(
                    'url'   => 'https://www.facebook.com/none',
                    'title' => 'Like us on Facebook',
                    'icon'  => 'el-icon-facebook'
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'https://twitter.com/none',
                    'title' => 'Follow us on Twitter',
                    'icon'  => 'el-icon-twitter'
                );
                $this->args['share_icons'][] = array(
                    'url'   => 'https://plus.google.com/+none/posts',
                    'title' => 'Find us on Google+',
                    'icon'  => 'el-icon-googleplus'
                );

                // Panel Intro text -> before the form
                if ( ! isset( $this->args['global_variable'] ) || $this->args['global_variable'] !== false ) {
                    if ( ! empty( $this->args['global_variable'] ) ) {
                        $v = $this->args['global_variable'];
                    } else {
                        $v = str_replace( '-', '_', $this->args['opt_name'] );
                    }
                    $this->args['intro_text'] = "";
                } else {
                    $this->args['intro_text'] = "";
                }

                // Add content after the form.
                $this->args['footer_text'] = "";
            }

            public function validate_callback_function( $field, $value, $existing_value ) {
                $error = true;
                $value = 'just testing';

                /*
              do your validation

              if(something) {
                $value = $value;
              } elseif(something else) {
                $error = true;
                $value = $existing_value;
                
              }
             */

                $return['value'] = $value;
                $field['msg']    = 'your custom error message';
                if ( $error == true ) {
                    $return['error'] = $field;
                }

                return $return;
            }

            public function class_field_callback( $field, $value ) {
                print_r( $field );
                echo '<br/>CLASS CALLBACK';
                print_r( $value );
            }

        }

        global $reduxConfig;
        $reduxConfig = new houseofcoffee_Theme_Options();
    } else {
        echo "The class named houseofcoffee_Theme_Options has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = true;
            $value = 'just testing';

            /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            
          }
         */

            $return['value'] = $value;
            $field['msg']    = 'your custom error message';
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;
