<?php

use luizbills\CSS_Generator\Generator as CSS_Generator;

class RawTest extends \Codeception\Test\Unit {

    // protected function _before () {
    // }

    // protected function _after () {
    // }

    // tests
    public function test_pretty () {
        $compressed = false;
        $css = new CSS_Generator();

        $css->add_raw( 'abc' );
        $css->add_comment( 'hi' );
        $css->add_raw( "xyz\n" );

        $expected = "abc\n/* hi */\nxyz\n\n";
        $actual = $css->get_output( $compressed );

        $this->assertEquals( $expected, $actual );
    }

    public function test_compressed () {
        $compressed = true;
        $css = new CSS_Generator();

        $css->add_raw( 'abc' );
        $css->add_comment( 'hi' );
        $css->add_raw( "xyz\n" );

        $expected = "abc/* hi */xyz\n";
        $actual = $css->get_output( $compressed );

        $this->assertEquals( $expected, $actual );
    }
}