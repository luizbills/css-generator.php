<?php

use luizbills\CSS_Generator\Generator as css;

class EscSelectorTest extends \Codeception\Test\Unit {

    // protected function _before () {
    // }

    // protected function _after () {
    // }

    public function test () {
        $this->assertEquals(css::esc_selector("\0"), "\u{FFFD}" );
        $this->assertEquals(css::esc_selector("a\0"), "a\u{FFFD}" );
        $this->assertEquals(css::esc_selector("\0b"), "\u{FFFD}b" );
        $this->assertEquals(css::esc_selector("a\0b"), "a\u{FFFD}b" );

        $this->assertEquals(css::esc_selector("\u{FFFD}"), "\u{FFFD}" );
        $this->assertEquals(css::esc_selector("a\u{FFFD}"), "a\u{FFFD}" );
        $this->assertEquals(css::esc_selector("\u{FFFD}b"), "\u{FFFD}b" );
        $this->assertEquals(css::esc_selector("a\u{FFFD}b"), "a\u{FFFD}b" );

        $this->assertEquals(css::esc_selector(""), "" );

        $this->assertEquals(css::esc_selector("\x01\x02\x1E\x1F"), "\\1 \\2 \\1e \\1f " );

        $this->assertEquals(css::esc_selector("0a"), "\\30 a" );
        $this->assertEquals(css::esc_selector("1a"), "\\31 a" );
        $this->assertEquals(css::esc_selector("2a"), "\\32 a" );
        $this->assertEquals(css::esc_selector("3a"), "\\33 a" );
        $this->assertEquals(css::esc_selector("4a"), "\\34 a" );
        $this->assertEquals(css::esc_selector("5a"), "\\35 a" );
        $this->assertEquals(css::esc_selector("6a"), "\\36 a" );
        $this->assertEquals(css::esc_selector("7a"), "\\37 a" );
        $this->assertEquals(css::esc_selector("8a"), "\\38 a" );
        $this->assertEquals(css::esc_selector("9a"), "\\39 a" );

        $this->assertEquals(css::esc_selector("a0b"), "a0b" );
        $this->assertEquals(css::esc_selector("a1b"), "a1b" );
        $this->assertEquals(css::esc_selector("a2b"), "a2b" );
        $this->assertEquals(css::esc_selector("a3b"), "a3b" );
        $this->assertEquals(css::esc_selector("a4b"), "a4b" );
        $this->assertEquals(css::esc_selector("a5b"), "a5b" );
        $this->assertEquals(css::esc_selector("a6b"), "a6b" );
        $this->assertEquals(css::esc_selector("a7b"), "a7b" );
        $this->assertEquals(css::esc_selector("a8b"), "a8b" );
        $this->assertEquals(css::esc_selector("a9b"), "a9b" );

        $this->assertEquals(css::esc_selector("-0a"), "-\\30 a" );
        $this->assertEquals(css::esc_selector("-1a"), "-\\31 a" );
        $this->assertEquals(css::esc_selector("-2a"), "-\\32 a" );
        $this->assertEquals(css::esc_selector("-3a"), "-\\33 a" );
        $this->assertEquals(css::esc_selector("-4a"), "-\\34 a" );
        $this->assertEquals(css::esc_selector("-5a"), "-\\35 a" );
        $this->assertEquals(css::esc_selector("-6a"), "-\\36 a" );
        $this->assertEquals(css::esc_selector("-7a"), "-\\37 a" );
        $this->assertEquals(css::esc_selector("-8a"), "-\\38 a" );
        $this->assertEquals(css::esc_selector("-9a"), "-\\39 a" );

        $this->assertEquals(css::esc_selector("-"), "\\-" );
        $this->assertEquals(css::esc_selector("-a"), "-a" );
        $this->assertEquals(css::esc_selector("--"), "--" );
        $this->assertEquals(css::esc_selector("--a"), "--a" );

        $this->assertEquals(css::esc_selector("--a"), "--a" );
        $this->assertEquals(css::esc_selector(".class"), "\.class" );
        $this->assertEquals(css::esc_selector("#id"), "\#id" );

        $this->assertEquals(css::esc_selector("\x80\x2D\x5F\xA9"), "\x80\x2D\x5F\xA9" );
        $this->assertEquals(css::esc_selector("\x7F\x80\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8A\x8B\x8C\x8D\x8E\x8F\x90\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9A\x9B\x9C\x9D\x9E\x9F"), "\\7f \x80\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8A\x8B\x8C\x8D\x8E\x8F\x90\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9A\x9B\x9C\x9D\x9E\x9F" );
        $this->assertEquals(css::esc_selector("\xA0\xA1\xA2"), "\xA0\xA1\xA2" );
        $this->assertEquals(css::esc_selector("a0123456789b"), "a0123456789b" );
        $this->assertEquals(css::esc_selector("abcdefghijklmnopqrstuvwxyz"), "abcdefghijklmnopqrstuvwxyz" );
        $this->assertEquals(css::esc_selector("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), "ABCDEFGHIJKLMNOPQRSTUVWXYZ" );

        $this->assertEquals(css::esc_selector("\x20\x21\x78\x79"), "\\ \\!xy" );

        // astral symbol (U+1D306 TETRAGRAM FOR CENTRE)
        $this->assertEquals(css::esc_selector("\u{D834}\u{DF06}"), "\u{D834}\u{DF06}" );
        // lone surrogates
        $this->assertEquals(css::esc_selector("\u{DF06}"), "\u{DF06}" );
        $this->assertEquals(css::esc_selector("\u{D834}"), "\u{D834}" );
    }
}