<?php

use luizbills\CSS_Generator\Generator as CSS_Generator;

class RulesTest extends \Codeception\Test\Unit {

    /**
     * @return void
     */
    public function test_pretty () {
        $compressed = false;
        $css = new CSS_Generator();

        $this->build_css( $css );

        $expected = file_get_contents( __DIR__ . '/_test.css' );
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     */
    public function test_compressed () {
        $compressed = true;
        $css = new CSS_Generator();

        $this->build_css( $css );

        $expected = file_get_contents( __DIR__ . '/_test.min.css' );
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }

    /**
     * @param luizbills\CSS_Generator\Generator $css
     * @return void
     */
    protected function build_css ( $css ) {
        $css->add_rule( 'body', [
            'margin' => '50px',
        ] );

        $css->add_rule( [ 'a', 'a:visited' ], [
            'color' => 'var(--accent)',
            'text-decoration' => 'line-through'
        ] );

        $css->open_block( 'supports', '(display: grid)' );

        $css->add_rule( '.'  . $css->esc( 'd:grid' ), [
            'display' => 'grid',
            'grid-template-columns' => '200px 200px'
        ] );

        $css->close_block();

        $css->open_block( 'media', 'print' );

        $css->add_rule( [ 'a', 'a:visited' ], [
            'color' => 'blue',
        ] );

        $css->close_block();

        $css->add_comment( 'Emoji and symbols' );
        $css->add_rule( '.'  . $css->esc( '❤️@★' ), [
            'color' => 'red',
        ] );

        $css->add_comment( 'class 123' );
        $css->add_rule( '.'  . $css->esc( '123' ) . '::before  ', [
            'content' => '"123"',
        ] );

        // should put root variables at top
        $css->root_variable( 'accent', 'violet' );

        file_put_contents( __DIR__ . '/_generated.css', $css->get_output( false ) );
    }
}