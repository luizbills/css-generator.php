<?php
/**
 * CSS Generator
 * Write css programatically using PHP.
 *
 * @version 1.3.0
 * @author Agência Zoop <hello@agenciazoop.com>
 * @copyright 2017 Agência Zoop
 * @license MIT
*/
class CSS_Generator {

	protected $output = '';
	protected $linebreak = '';
	protected $indentation = null;
	private $media_is_open = false;

	public function __construct ( $compact = false, $indentation = "\t" ) {
		if ( ! $compact ) {
			$this->linebreak = PHP_EOL;
			$this->indentation = $indentation;
		}
	}

	public function get_output ( $close_media = true ) {
		$this->close_media();
		return $this->output;
	}

	public function add_raw ( $string ) {
		$this->output .= $string;
	}

	public function add_rule ( $selectors, $declarations_array ) {
		$declarations = [];
		$selector_indentation = '';
		$declaration_indentation = '';

		if ( ! empty( $this->linebreak ) ) {
			$selector_indentation = $this->media_is_open ? $this->indentation : '';
			$declaration_indentation = $this->media_is_open ? str_repeat( $this->indentation, 2 ) : $this->indentation;
		}

		if ( ! is_array( $selectors ) ) {
			$selectors = [ $selectors ];
		}

		foreach ( $selectors as $key => $value ) {
			$selectors[ $key ] = $selector_indentation . trim( $value );
		}

		foreach ( $declarations_array as $key => $value ) {
			$declarations[] = $declaration_indentation . trim( $key ) . ':' . trim( $value ) . ';' . $this->linebreak;
		}

		$this->output .= implode( ',' . $this->linebreak, $selectors ) . '{' . $this->linebreak
			. implode( false, $declarations )
			. $selector_indentation . '}' . $this->linebreak;
	}

	public function open_media ( $breakpoint ) {
		$this->close_media();
		$this->media_is_open = true;
		$this->output .= '@media ' . trim( $breakpoint ) . '{' . $this->linebreak;
	}

	public function close_media () {
		if ( $this->media_is_open ) {
			$this->media_is_open = false;
			$this->output .= '}' . $this->linebreak;
		}
	}
	
	public function reset () {
		$this->output = '';
	}
}
