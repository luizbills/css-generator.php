<?php
/**
 * CSS Generator
 * Write css programatically using PHP.
 *
 * @version 2.0.0
 * @author Luiz Bills <luizpbills@gmail.comm>
 * @copyright 2018 Luiz Bills
 * @license MIT
*/
require __DIR__ . '/vendor/autoload.php';

use MatthiasMullie\Minify;

class CSS_Generator {

	protected $output = '';
	protected $linebreak = '';
	protected $indentation = null;
	protected $media_is_open = false;

	protected $settings = null;
	protected $default_settings = [
		'indentation'  => "\t",
		'linebreak'    => PHP_EOL,
	];

	public function __construct ( $settings = [] ) {
		$this->settings = array_merge( $this->default_settings, $settings );
	}

	public function get_output ( $compress = false ) {
		$this->close_media();

		if ( $compress ) {
			$this->minify();
		}

		return $this->output;
	}

	protected function minify () {
		$minifier = new Minify\CSS( $this->output );
		$this->output = $minifier->minify();
	}

	public function add_raw ( $string ) {
		$this->output .= $string;
	}

	public function add_rule ( $selectors, $declarations_array ) {
		$declarations = [];
		$selector_indentation = '';
		$declaration_indentation = '';
		$linebreak = $this->settings['indentation'];
		$indentation = $this->settings['linebreak'];

		if ( ! empty( $linebreak ) ) {
			$selector_indentation = $this->media_is_open ? $indentation : '';
			$declaration_indentation = $this->media_is_open ? str_repeat( $indentation, 2 ) : $indentation;
		}

		if ( ! is_array( $selectors ) ) {
			$selectors = [ $selectors ];
		}

		foreach ( $selectors as $key => $value ) {
			$selectors[ $key ] = $selector_indentation . trim( $value );
		}

		foreach ( $declarations_array as $key => $value ) {
			$declarations[] = $declaration_indentation . trim( $key ) . ':' . trim( $value ) . ';' . $linebreak;
		}

		$this->output .= implode( ',' . $linebreak, $selectors ) . '{' . $linebreak
			. implode( false, $declarations )
			. $selector_indentation . '}' . $linebreak;
	}

	public function open_media ( $breakpoint ) {
		$this->close_media();
		$this->media_is_open = true;
		$this->output .= '@media ' . trim( $breakpoint ) . '{' . $this->settings['linebreak'];
	}

	public function close_media () {
		if ( $this->media_is_open ) {
			$this->media_is_open = false;
			$this->output .= '}' . $this->settings['linebreak'];
		}
	}

	public function reset () {
		$this->output = '';
	}
}
