<?php

/**
 * Creates the Panel of share buttons that appear on the side of the screen and
 * floats as the user scrolls the page based on options and settings.
 *
 * @package   SocialWarfare\Functions
 * @copyright Copyright (c) 2018, Warfare Plugins, LLC
 * @license   GPL-3.0+
 * @since     1.0.0
 *
 */
class SWP_Buttons_Panel_Side extends SWP_Buttons_Panel {


	/**
	 * Creates the fully qualified markup for floating button panel.
	 *
	 * @since  3.0.0 | 01 MAR 2018 | Created
	 * @since  3.0.8 | 22 MAY 2018 | Added the $blacklist and in_array conditional.
	 * @param  boolean $echo Whether or not to immediately echo the HTML.
	 * @return string  $html The qualified markup for the panel.
	 *
	 */
	public function render_html() {


		/**
		 * We are only generating the floating buttons panel if it is set to
		 * left or right. If it is set to none, top, or bottom, we don't need it.
		 *
		 */
		$blacklist = array( 'none', 'top', 'bottom' );
		if ( in_array( $this->get_option('float_location'), $blacklist ) || is_preview() ) {
			return '';
		}


		/**
		 * Bail out if we're not on a single.php or if the floating buttons
		 * are turned off.
		 *
		 */
		if( !is_singular() || 'none' === $this->get_float_location() ) {
			return '';
		}


		/**
		 * The floating button doesn't look good if you have a ton of buttons
		 * enabled. As such, we have an option to limit the number of buttons
		 * displayed. This is the maximum.
		 *
		 */
		$max_buttons = $this->get_option( 'float_button_count' );
		if( false == $max_buttons || 0 == $max_buttons ) {
			$max_buttons = 5;
		}


		/**
		 * Fetch the generated html for the total shares and for the individual
		 * buttons.
		 *
		 */
		$total_shares_html = $this->generate_total_shares_html();
		$buttons_html      = $this->generate_individual_buttons_html( (int) $max_buttons );


		/**
		 * Generate the html for the wrapper container that will hold all of the
		 * buttons.
		 *
		 */
		$container = '<div class="' . $this->generate_css_classes() . '" '.
		    ' data-panel-position="' . $this->get_option('location_post') .
			' scale-' . $this->get_option('float_size') * 100 .
			'" data-float="' . $this->get_option('float_location') .
			'" data-count="' . count($this->networks) .
			'" data-float-color="' . $this->get_option('float_background_color') .
			'" data-min-width="' . $this->get_option('float_screen_width') .
			'" data-transition="' . $this->get_option('transition') .
			'" data-float-mobile="' . $this->get_mobile_float_location() .'">';


		/**
		 * Append the total shares html to the left or to the right of the
		 * buttons html depending on the user's settings.
		 *
		 */
		if ($this->get_option('totals_alignment') === 'totals_left') {
			$buttons_html = $total_shares_html . $buttons_html;
		} else {
			$buttons_html .= $total_shares_html;
		}


		/**
		 * Place the html for the buttons inside of the wrapper container, close
		 * up the wrapper container and then return the html to the caller.
		 *
		 */
		$html = $container . $buttons_html . '</div>';
		$this->html = $html;

		return $html;

	}


	/**
	 * Generate the CSS classes for the parent wrapper container.
	 *
	 * @since  3.4.0 | 21 SEP 2018 | Created
	 * @param  void
	 * @return string A string of CSS classes.
	 *
	 */
	protected function generate_css_classes() {

		$classes = 'swp_social_panelSide swp_floating_panel swp_social_panel';
		$classes .= ' swp_' . $this->get_option('float_button_shape');
		$classes .= $this->get_colors(true);
		$classes .= $this->get_option('transition');


		/**
		 * This controls whether the floating panel is going to be displayed on
		 * the left side of the screen or the right side of the screen.
		 *
		 */
		if ( 'none' != $this->get_float_location() ) {
			$classes .= " swp_float_" . $this->get_option('float_location');
		}


		/**
		 * This determines if the floating buttons will be snug against the top
		 * of the screen, the bottom of the screen or centered vertically.
		 *
		 */
		if ($this->get_option('float_alignment')  ) {
			$classes .= " swp_side_" . $this->get_option('float_alignment');
		}


		/**
		 * This determines if the user has set the size of the panel. If so, the
		 * CSS will use the transform:scale() to make it that size.
		 *
		 */
		if ( isset($this->options['float_size']) ) {
			$size     = $this->get_option('float_size') * 100;
			$side     = $this->get_option('float_location');
			$position = $this->get_option('float_alignment');
			$classes .= " scale-${size} float-position-${position}-${side}";
		}

		return $classes;
	}
}
