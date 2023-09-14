<?php
/**
 * This template can be overridden by copying it to `yourtheme[-child]/transmax-core/elementor/templates/wgl-countdown.php`.
 */
namespace WGL_Extensions\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

/**
 * WGL Elementor Countdown Template
 *
 *
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WGLCountDown
{
	private static $instance ;

	public static function get_instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function render($self, $atts)
	{
		extract($atts);

		wp_enqueue_script(
			'jquery-countdown',
			get_template_directory_uri() . '/js/jquery.countdown.min.js',
			[],
			false,
			false
		);

		// Module unique id
		$cd_attr = ' id=' . uniqid("countdown_");

		$cd_class = $show_separating ? ' has-dots' : '';

		$f = ! $hide_day ? 'd' : '';
		$f .= ! $hide_hours ? 'H' : '';
		$f .= ! $hide_minutes ? 'M' : '';
		$f .= ! $hide_seconds ? 'S' : '';

		// Countdown data attribute http://keith-wood.name/countdown.html
		$data['format'] = !empty($f) ? esc_attr($f) : '';

		$data['year'] = esc_attr($countdown_year);
		$data['month'] = esc_attr($countdown_month);
		$data['day'] = esc_attr($countdown_day);
		$data['hours'] = esc_attr($countdown_hours);
		$data['minutes'] = esc_attr($countdown_min);

		$data['labels'][]  = esc_html__('Years', 'transmax-core');
		$data['labels'][]  = esc_html__('Months', 'transmax-core');
		$data['labels'][]  = esc_html__('Weeks', 'transmax-core');
		$data['labels'][]  = esc_html__('Days', 'transmax-core');
		$data['labels'][]  = esc_html__('Hours', 'transmax-core');
		$data['labels'][]  = esc_html__('Minutes', 'transmax-core');
		$data['labels'][]  = esc_html__('Seconds', 'transmax-core');
		$data['labels1'][] = esc_html__('Year', 'transmax-core');
		$data['labels1'][] = esc_html__('Month', 'transmax-core');
		$data['labels1'][] = esc_html__('Week', 'transmax-core');
		$data['labels1'][] = esc_html__('Day', 'transmax-core');
		$data['labels1'][] = esc_html__('Hour', 'transmax-core');
		$data['labels1'][] = esc_html__('Minute', 'transmax-core');
		$data['labels1'][] = esc_html__('Second', 'transmax-core');

		$attrs = json_encode($data, true);
		$output = '<div'.$cd_attr.' class="wgl-countdown'.esc_attr($cd_class).'" data-atts="'.esc_js($attrs).'"></div>';
		echo \WGL_Framework::render_html($output);

	}

}