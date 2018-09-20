<?php
/**
 * CSS Generator
 * Write css programatically using PHP.
 *
 * @version 3.0.0
 * @author Luiz Bills <luizpbills@gmail.comm>
 * @copyright 2018 Luiz Bills
 * @license MIT
*/
require __DIR__ . '/vendor/autoload.php';
use MatthiasMullie\Minify;

class CSS_Generator {
	protected $raw = '';
	protected $block_level = 0;
	protected $linebreak = "\n";
	protected $options = null;
	protected $default_options = [
		'indentation'  => '    ', // 4 spaces
	];

	public function __construct ( $options = [] ) {
		$this->options = array_merge( $this->default_options, $options );
	}

	public function get_output ( $compress = false ) {
		$this->close_blocks();

		if ( $compress ) {
			$this->minify();
		}

		return $this->raw;
	}

	protected function minify () {
		$minifier = new Minify\CSS( $this->raw );
		$this->raw = $minifier->minify();
	}

	public function add_raw ( $string ) {
		$this->raw .= $string;
	}

	public function add_rule ( $selectors, $declarations_array ) {
		$declarations = [];
		$selector_indentation = str_repeat( $this->options['indentation'], $this->block_level );
		$declaration_indentation = str_repeat( $this->options['indentation'], $this->block_level + 1 );
		
		if ( ! is_array( $selectors ) ) {
			$selectors = [ $selectors ];
		}

		foreach ( $selectors as $key => $value ) {
			$selectors[ $key ] = $selector_indentation . trim( $value );
		}

		foreach ( $declarations_array as $key => $value ) {
			$declarations[] = $declaration_indentation . trim( $key ) . ': ' . trim( $value ) . ';' . $this->linebreak;
		}

		$this->raw .= implode( ',' . $this->linebreak, $selectors ) . ' {';
		$this->raw .= $this->linebreak . implode( '', $declarations );
		$this->raw .= $selector_indentation . '}' . $this->linebreak;
	}

	public function open_block ( $type, $props = '' ) {
		$block_indentation = str_repeat( $this->options['indentation'], $this->block_level );
		$this->raw .= $block_indentation . '@' . $type . ' ' . trim( $props ) . ' {' . $this->linebreak;
		$this->block_level++;
	}

	public function close_block () {
		if ( $this->block_level > 0 ) {
			$this->block_level--;
			$block_indentation = str_repeat( $this->options['indentation'], $this->block_level );
			$this->raw .= $block_indentation . '}' . $this->linebreak;
		}
	}

	public function close_blocks () {
		while ( $this->block_level > 0 ) {
			$this->close_block();
		}
	}
}
