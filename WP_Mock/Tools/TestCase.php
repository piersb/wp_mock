<?php

namespace WP_Mock\Tools;

use WP_Mock;

class TestCase extends \PHPUnit_Framework_TestCase {

	protected $mockedStaticMethods = [ ];

	/**
	 * @var array
	 */
	protected $__default_post = array();

	/**
	 * @var array
	 */
	protected $__default_get = array();

	/**
	 * @var array
	 */
	protected $__default_request = array();

	/**
	 * @var bool|callable
	 */
	protected $__contentFilterCallback = false;

	protected $testFiles = array();

	public function setUp() {
		WP_Mock::setUp();

		$_GET     = (array) $this->__default_get;
		$_POST    = (array) $this->__default_post;
		$_REQUEST = (array) $this->__default_request;

		$this->__contentFilterCallback = false;

		$annotations = $this->getAnnotations();
		if (
			! isset( $annotations['stripTabsAndNewlinesFromOutput'] ) ||
			$annotations['stripTabsAndNewlinesFromOutput'][0] !== 'disabled' ||
			(
				is_numeric( $annotations['stripTabsAndNewlinesFromOutput'][0] ) &&
				(int) $annotations['stripTabsAndNewlinesFromOutput'][0] !== 0
			)
		) {
			$this->__contentFilterCallback = array( $this, 'stripTabsAndNewlines' );
			$this->setOutputCallback( $this->__contentFilterCallback );
		}

		$this->cleanGlobals();
	}

	public function tearDown() {
		WP_Mock::tearDown();

		$this->cleanGlobals();

		$this->mockedStaticMethods = [ ];

		$_GET     = array();
		$_POST    = array();
		$_REQUEST = array();
	}

	public function stripTabsAndNewlines( $content ) {
		return str_replace( array( "\t", "\r", "\n" ), '', $content );
	}

	protected function cleanGlobals() {
		$common_globals = array(
			'post',
			'wp_query',
		);
		foreach ( $common_globals as $var ) {
			if ( isset( $GLOBALS[$var] ) ) {
				unset( $GLOBALS[$var] );
			}
		}

	}

}

