<?php

use luizbills\CSS_Generator\Generator as CSS_Generator;

class RawTest extends \Codeception\Test\Unit {

    /**
     * @return void
     */
    public function test_pretty () {
        $compressed = false;
        $css = new CSS_Generator();

        $css->add_raw( 'abc' );
        $css->add_comment( 'hi' );
        $css->add_raw( "xyz\n" );

        $expected = "abc/* hi */\nxyz\n";
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     */
    public function test_compressed () {
        $compressed = true;
        $css = new CSS_Generator();

        $css->add_raw( 'abc' );
        $css->add_comment( 'hi' );
        $css->add_raw( "xyz\n" );

        $expected = "abcxyz\n";
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }
}