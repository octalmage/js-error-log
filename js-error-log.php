<?php
/**
 * Plugin Name:     JavaScript Error Log
 * Description:     Catch and log JavaScript errors locally.
 * Version:         0.0.2
 * Author:          Jason Stallings
 * Author URI:      https://jason.stallin.gs
 * Text Domain:     js-error-log
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class JS_Error_Log {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
		add_action( 'wp_ajax_js_log_error', array( $this, 'js_log_error' ) );
		add_action( 'wp_ajax_nopriv_js_log_error', array( $this, 'js_log_error' ) );
	}

	public function enqueue_script() {
		wp_enqueue_script( 'js-error-log', plugins_url( 'js-error-log.js', __FILE__ ) );
	}

	public function js_log_error() {
		if ( isset( $_REQUEST['msg'] ) && isset( $_REQUEST['line'] ) && isset( $_REQUEST['url'] ) ) {
			$error = filter_input_array( INPUT_POST, array(
				'msg' => FILTER_SANITIZE_STRING,
				'url' => FILTER_SANITIZE_STRING,
				'line' => FILTER_SANITIZE_STRING,
			));

			error_log( 'JavaScript Error: ' . html_entity_decode( $error['msg'], ENT_QUOTES ) . ', file: ' . $error['url'] . ':' . $error['line'] );
			wp_send_json( $error );
		}
		wp_die();
	}
}

new JS_Error_Log();
