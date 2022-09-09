<?php
/**
 * CSS Generator
 * Write css programatically using PHP.
 *
 * @author Luiz Bills <luizpbills@gmail.comm>
 * @copyright 2018 Luiz Bills
 * @license MIT
*/
namespace luizbills\CSS_Generator;

class Generator {
	const VERSION = '4.0.0';

	protected $variables = [];
	protected $blocks = [];
	protected $options = null;
	protected $level;
	protected $compress_output;
	protected $cache_pretty = null;
	protected $cache_compressed = null;

	/**
	 * @param array $options
	 */
	public function __construct ( $options = [] ) {
		$default_options = [
			'indent_size'  => 4,
			'indent_style' => 'space', // or "tab"
		];
		$this->options = array_merge( $default_options, $options );
	}

	/**
	 * @param boolean $compressed
	 * @return string
	 */
	public function get_output ( $compressed = false ) {
		if ( ! $compressed ) {
			$cache = $this->cache_pretty;
			return $cache ? $cache : $this->generate( false );
		}
		$cache = $this->cache_compressed;
		return $cache ? $cache : $this->generate( true );
	}

	/**
	 * @param string $string
	 * @return Generator
	 */
	public function add_raw ( $string ) {
		$blocks[] = [
			'type' => 'content',
			'raw' => $string
		];
		$this->clear_cache();
		return $this;
	}

	/**
	 * @param string $string
	 * @return Generator
	 */
	public function add_comment ( $string ) {
		$blocks[] = [
			'type' => 'content',
			'commend' => "\/* $string */"
		];
		$this->clear_cache();
		return $this;
	}

	/**
	 * @param string|string[] $selectors
	 * @param string[] $selectors
	 * @return Generator
	 */
	public function add_rule ( $selectors, $declarations ) {
		$selectors = ! is_array( $selectors ) ? [ $selectors ] : $selectors;
		$block = [
			'type' => 'rule',
			'selectors' => [],
			'declarations' => $declarations,
		];

		foreach ( $selectors as $i => $selector ) {
			$block['selectors'][ $i ] = self::esc_selector( $selector );
		}
		$this->blocks[] = $block;

		$this->clear_cache();
		return $this;
	}

	/**
	 * @param string $name
	 * @param string $props
	 * @return Generator
	 */
	public function open_block ( $name, $props = '' ) {
		$this->blocks[] = [
			'type' => 'open',
			'name' => $name,
			'props' => $props
		];

		$this->clear_cache();
		return $this;
	}

	/**
	 * @return Generator
	 */
	public function close_block () {
		$this->blocks[] = [
			'type' => 'close',
		];
		$this->clear_cache();
		return $this;
	}

	/**
	 * @param string $name
	 * @param int|string value
	 * @return Generator
	 */
	public function root_variable ( $name, $value ) {
		$this->variables[ '--' . self::esc_selector( $name ) ] = trim( $value );
		$this->clear_cache();
		return $this;
	}

	public function clear_cache () {
		$this->cache_compressed = null;
		$this->cache_pretty = null;
	}

	/**
	 * @param string $selector
	 * @return string
	 */
	public static function esc_selector ( $selector ) {
		$length = mb_strlen( $selector );
		$result = '';
		$e = 'UTF-8'; // enconding
		$index = -1;
		$first_char = mb_substr( $selector, 0, 1, $e );
		$first_char_code = mb_ord( $first_char, $e );

		if (
			// If the character is the first character and is a `-` (U+002D), and
			// there is no second character, […]
			1 === $length &&
			45 === $first_char_code
		) {
			return '\\' . $selector;
		}

		if ( '.' === $first_char || '#' === $first_char ) {
			$result .= $first_char;
			$selector = mb_substr( $selector, 1, null, $e );
		}

		while ( ++$index < $length ) {
			$char = mb_substr( $selector, $index, 1, $e );
			if ( '' === $char ) continue;

			$char_code = mb_ord( $char, $e );

			// If the character is NULL
			if ( 0 === $char_code || 65533 === $char_code ) {
				$result .= '\uFFFD';
				continue;
			}

			if (
				// If the character is in the range [\1-\1F] (U+0001 to U+001F)
				( $char_code >= 1 && $char_code <= 31 ) ||
				// or is U+007F, […]
				127 === $char_code ||
				// If the character is the first character and is in the range [0-9]
				// (U+0030 to U+0039), […]
				( 0 === $index && $char_code >= 48 && $char_code <= 57 ) ||
				// If the character is the second character and is in the range [0-9]
				// (U+0030 to U+0039) and the first character is a `-` (U+002D), […]
				(
					1 === $index &&
					$char_code >= 48 && $char_code <= 57 &&
					45 === $first_char_code
				)
			) {
				// https://drafts.csswg.org/cssom/#escape-a-character-as-code-point
				$result .= "\\" . dechex( $char_code ) . ' ';
				continue;
			}

			// If the character is not handled by one of the above rules and is
			// greater than or equal to U+0080, is `-` (U+002D) or `_` (U+005F), or
			// is in one of the ranges [0-9] (U+0030 to U+0039), [A-Z] (U+0041 to
			// U+005A), or [a-z] (U+0061 to U+007A), […]
			if (
				$char_code >= 128 || $char_code == 45 || $char_code == 95 ||
				$char_code >= 48 && $char_code <= 57 ||
				$char_code >= 65 && $char_code <= 90 ||
				$char_code >= 97 && $char_code <= 122
			) {
				// the character itself
				$result .= $char;
				continue;
			}

			// Otherwise, the escaped character.
			// https://drafts.csswg.org/cssom/#escape-a-character
			$result .= '\\' . $char;
		}

		return $result;
	}

	/**
	 * @param boolean $compressed
	 * @return void
	 */
	protected function generate ( $compressed = false ) {
		$this->level = 0;
		$this->compress_output = $compressed;

		$br = $this->compress_output ? '' : "\n";
		$s = $this->compress_output ? '' : ' ';
		$open = $s . '{' . $br;
		$close = '}' . $br;
		$output = '';
		$has_variables = count( $this->variables ) > 0;

		if ( $has_variables ) {
			$output .= ':root' . $open;
			$this->level++;
			foreach ( $this->variables as $name => $value ) {
				$output .= $this->tab();
				$output .= "$name:$s$value;$br";
			}
			$output .= $close . $br;
			$this->level--;
		}

		foreach ( $this->blocks as $block ) {
			switch ( $block['type'] ) {
				case 'comment':
					$output .= $this->tab();
				case 'raw':
					$output .= $block['content'];
					break;
				case 'rule':
					$output .= $this->tab();
					$output .= implode( ",$br", $block['selectors'] ) . $open;
					$this->level++;
					foreach ( $block['declarations'] as $key => $value ) {
						$output .= $this->tab();
						$output .= trim( $key ) . ":$s" . trim( $value ) . ";$br";
					}
					$this->level--;
					$output .= $this->tab() . $close;
					break;
				case 'open':
					$output .= $this->tab();
					$output .= '@' . $block['name'];
					$output .= $block['props'] ? " {$block['props']}" : '';
					$output .= "$open";
					$this->level++;
					break;
				case 'close':
					$this->level--;
					$output .= $this->tab() . $close;
					break;
				default:
					break;
			}
		}

		while ( $this->level > 0 ) {
			error_log( 'level = ' . $this->level );
			$this->level--;
			$output .= $this->tab() . $close;
		}

		if ( $compressed ) {
			$this->cache_compressed = $output;
		} else {
			$this->cache_pretty = $output;
		}

		return $output;
	}

	/**
	 * @return string
	 */
	protected function tab () {
		if ( ! $this->compress_output && $this->level > 0 ) {
			$indent = 'tab' === $this->options['indent_style'] ? "\t" : ' ';
			$size = intval( $this->options['indent_size'] );
			return str_repeat( $indent, max( $size, 2 ) * $this->level );
		}
		return '';
	}
}
