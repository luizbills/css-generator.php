<?php
/**
 * CSS Generator
 * Write css programatically using PHP.
 *
 * @author Luiz Bills <luizpbills@gmail.comm>
 * @copyright 2022 Luiz Bills
 * @license MIT
*/

namespace luizbills\CSS_Generator;

/**
 * @phpstan-type GeneratorConfig array{indent_style?: string, indent_size?: int}
 */
class Generator {
	const VERSION = '4.0.0';

	/**
	 * Stores the global variables
	 *
	 * @var array<string, string|int>
	 */
	protected array $variables = [];

	/**
	 * Store the declared CSS blocks and comments
	 *
	 * @var array<int, array<mixed>>
	 */
	protected array $blocks = [];

	/**
	 * Store the config options
	 *
	 * @var array<string, string|int>
	 */
	protected array $options = [];

	/**
	 * Store the indentation level
	 *
	 * @var int
	 */
	protected int $indent_level = 0;

	/**
	 * @var bool
	 */
	protected bool $compress_output;

	/**
	 * Stores the last generated CSS in prettry format (with tabs, spaces and line breaks).
	 *
	 * @var string|null
	 */
	protected $cache_pretty;

	/**
	 * Stores the last generated CSS in minified format
	 *
	 * @var string|null
	 */
	protected $cache_compressed;

	/**
	 * Class constructor
	 *
	 * @param GeneratorConfig $options
	 */
	public function __construct ( $options = [] ) {
		$default_options = [
			'indent_size'  => 4, // only works with indent_style = space
			'indent_style' => 'space', // or "tab"
		];
		$this->options = array_merge( $default_options, $options );
	}

	/**
	 * Returns the generated CSS
	 *
	 * @param boolean $compressed
	 * @return string
	 */
	public function get_output ( $compressed = false ) {
		$result = '';
		if ( ! $compressed ) {
			$result = $this->cache_pretty = $this->generate( false );
		} else {
			$result = $this->cache_compressed = $this->generate( true );
		}
		return $result;
	}

	/**
	 * Print anything (be careful)
	 *
	 * @param string $string
	 * @return $this
	 */
	public function add_raw ( $string ) {
		$this->blocks[] = [
			'type' => 'raw',
			'raw' => $string
		];
		$this->clear_cache();
		return $this;
	}

	/**
	 * Print a code comment
	 *
	 * @param string $string
	 * @return $this
	 */
	public function add_comment ( $string ) {
		$this->blocks[] = [
			'type' => 'comment',
			'comment' => $string
		];
		$this->clear_cache();
		return $this;
	}

	/**
	 * Declares a CSS rule
	 *
	 * @param string|string[] $selectors
	 * @param array<string, string|numeric> $declarations
	 * @return $this
	 */
	public function add_rule ( $selectors, $declarations ) {
		$selectors = ! is_array( $selectors ) ? [ $selectors ] : $selectors;
		$this->blocks[] = [
			'type' => 'rule',
			'selectors' => $selectors,
			'declarations' => $declarations,
		];
		$this->clear_cache();
		return $this;
	}

	/**
	 * Declares a global variable (in :root)
	 *
	 * @param string $name The variable name
	 * @param string|numeric-string $value The variable value
	 * @return $this
	 */
	public function root_variable ( $name, $value ) {
		$this->variables[ trim( $name ) ] = trim( strval( $value ) );
		$this->clear_cache();
		return $this;
	}

	/**
	 * Opens a block like @media, @supports, etc.
	 *
	 * @param string $name
	 * @param string $props
	 * @return $this
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
	 * Closes the last opened block
	 *
	 * @return $this
	 */
	public function close_block () {
		$this->blocks[] = [
			'type' => 'close',
		];
		$this->clear_cache();
		return $this;
	}

	/**
	 * @return void
	 */
	public function clear_cache () {
		$this->cache_compressed = null;
		$this->cache_pretty = null;
	}

	/**
	 * Delete all declared blocks and resets the instance.
	 *
	 * @return void
	 */
	public function reset () {
		$this->clear_cache();
		$this->blocks = [];
	}

	/**
	 * Alias for self::escape
	 *
	 * @param string $selector
	 * @return string
	 */
	public function esc ( $selector ) {
		return self::escape( $selector );
	}

	/**
	 * Returns one unit of indentation
	 *
	 * @return string
	 */
	public function get_indent_unit () {
		if ( 'space' === $this->options['indent_style'] ) {
			$size = intval( $this->options['indent_size'] );
			return str_repeat( ' ', max( $size, 2 ) );
		}
		return "\t";
	}

	/**
	 * Returns the current indentation (if not generating a minified code).
	 *
	 * @return string
	 */
	protected function tab () {
		if ( ! $this->compress_output ) {
			if ( $this->indent_level > 0 ) {
				return str_repeat( $this->get_indent_unit(), $this->indent_level );
			}
		}
		return '';
	}

	/**
	 * Build the CSS code
	 *
	 * @param boolean $compressed
	 * @return string
	 */
	protected function generate ( $compressed = false ) {
		$this->indent_level = 0;
		$this->compress_output = $compressed;

		$br = $this->compress_output ? '' : "\n";
		$s = $this->compress_output ? '' : ' ';
		$open = $s . '{' . $br;
		$close = '}' . $br;
		$output = '';
		$has_variables = count( $this->variables ) > 0;

		if ( $has_variables ) {
			$output .= ':root' . $open;
			$this->indent_level++;
			foreach ( $this->variables as $name => $value ) {
				$output .= $this->tab();
				$output .= "--$name:$s$value;$br";
			}
			$output .= $close . $br;
			$this->indent_level--;
		}

		foreach ( $this->blocks as $block ) {
			switch ( $block['type'] ) {
				case 'comment':
					if ( ! $compressed ) {
						$output .= $this->tab();
						$output .= "/* {$block['comment']} */$br";
					}
					break;
				case 'raw':
					$output .= $block['raw'];
					break;
				case 'rule':
					$output .= $this->tab();
					$selectors = array_map( 'trim', $block['selectors'] );
					$output .= implode( ",$br" . $this->tab(), $selectors );
					$output .= $open;
					$this->indent_level++;
					foreach ( $block['declarations'] as $key => $value ) {
						$output .= $this->tab();
						$output .= trim( $key ) . ":$s" . trim( $value ) . ";$br";
					}
					$this->indent_level--;
					$output .= $this->tab() . $close;
					break;
				case 'open':
					$output .= $this->tab();
					$output .= '@' . $block['name'];
					$output .= '' !== $block['props'] ? " {$block['props']}" : '';
					$output .= "$open";
					$this->indent_level++;
					break;
				case 'close':
					$this->indent_level--;
					$output .= $this->tab() . $close;
					break;
				default:
					break;
			}
		}

		while ( $this->indent_level > 0 ) {
			error_log( 'level = ' . $this->indent_level );
			$this->indent_level--;
			$output .= $this->tab() . $close;
		}

		return $output;
	}

	/**
	 * Escapes a CSS rule selector. Based on https://github.com/mathiasbynens/CSS.escape
	 *
	 * @static
	 * @param string $selector
	 * @return string
	 */
	public static function escape ( $selector ) {
		if ( '' === $selector ) return $selector;

		$length = mb_strlen( $selector );
		$result = '';
		$index = -1;
		$first_char = mb_substr( $selector, 0, 1 );
		$first_char_code = ord( $first_char );

		if (
			// If the character is the first character and is a `-` (U+002D), and
			// there is no second character, […]
			1 === $length &&
			0x2D === $first_char_code
		) {
			return '\\' . $selector;
		}

		while ( ++$index < $length ) {
			$char = mb_substr( $selector, $index, 1 );
			if ( '' === $char ) continue;

			$char_code = ord( $char );

			// If the character is NULL
			if ( 0 === $char_code ) {
				$result .= "\u{FFFD}";
				continue;
			}

			if (
				// If the character is in the range [\1-\1F] (U+0001 to U+001F) or is U+007F, […]
				$char_code <= 0x1F || 0x7F === $char_code ||
				// If the character is the first character and is in the range [0-9]
				// (U+0030 to U+0039), […]
				( 0 === $index && $char_code >= 0x30 && $char_code <= 0x39 ) ||
				// If the character is the second character and is in the range [0-9]
				// (U+0030 to U+0039) and the first character is a `-` (U+002D), […]
				(
					1 === $index &&
					$char_code >= 0x0030 && $char_code <= 0x0039 &&
					0x002D === $first_char_code
				)
			) {
				// https://drafts.csswg.org/cssom/#escape-a-character-as-code-point
				$result .= '\\' . dechex( $char_code ) . ' ';
				continue;
			}

			if (
				// If the character is not handled by one of the above rules and is
				// greater than or equal to U+0080, is `-` (U+002D) or `_` (U+005F), or
				$char_code >= 0x0080 || 0x002D === $char_code || 0x005F === $char_code ||
				// is in one of the ranges [0-9] (U+0030 to U+0039), [A-Z] (U+0041 to U+005A)
				$char_code >= 0x0030 && $char_code <= 0x0039 ||
				$char_code >= 0x0041 && $char_code <= 0x005A ||
				// , or [a-z] (U+0061 to U+007A), […]
				$char_code >= 0x0061 && $char_code <= 0x007A
			) {
				// the character itself
				$result .= $char;
				continue;
			}

			// Otherwise, the escaped character.
			// https://drafts.csswg.org/cssom/#escape-a-character
			$result .= "\\" . $char;
		}

		return $result;
	}
}
