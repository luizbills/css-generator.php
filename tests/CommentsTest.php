<?php

use luizbills\CSS_Generator\Generator as CSS_Generator;

class CommentsTest extends \Codeception\Test\Unit {

    /**
     * @return void
     */
    public function test_pretty () {
        $compressed = false;
        $css = new CSS_Generator();

        $css->add_comment( 'a' );
        $css->add_comment( 'b' );

        $expected = "/* a */\n/* b */\n";
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }

    /**
     * @return void
     */
    public function test_compressed () {
        $compressed = true;
        $css = new CSS_Generator();

        $css->add_comment( 'a' );
        $css->add_comment( 'b' );

        $expected = "/* a *//* b */";
        $actual = $css->get_output( $compressed );

        self::assertEquals( $expected, $actual );
    }
}