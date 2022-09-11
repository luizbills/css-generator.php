<?php

use luizbills\CSS_Generator\Generator as CSS_Generator;

class EscSelectorTest extends \Codeception\Test\Unit {

    public function test () {
        $css = new CSS_Generator();

        $this->assertEquals($css->esc("\0"), "\u{FFFD}" );
        $this->assertEquals($css->esc("a\0"), "a\u{FFFD}" );
        $this->assertEquals($css->esc("\0b"), "\u{FFFD}b" );
        $this->assertEquals($css->esc("a\0b"), "a\u{FFFD}b" );

        $this->assertEquals($css->esc("\u{FFFD}"), "\u{FFFD}" );
        $this->assertEquals($css->esc("a\u{FFFD}"), "a\u{FFFD}" );
        $this->assertEquals($css->esc("\u{FFFD}b"), "\u{FFFD}b" );
        $this->assertEquals($css->esc("a\u{FFFD}b"), "a\u{FFFD}b" );

        $this->assertEquals($css->esc(""), "" );

        $this->assertEquals($css->esc("\x01\x02\x1E\x1F"), "\\1 \\2 \\1e \\1f " );

        $this->assertEquals($css->esc("0a"), "\\30 a" );
        $this->assertEquals($css->esc("1a"), "\\31 a" );
        $this->assertEquals($css->esc("2a"), "\\32 a" );
        $this->assertEquals($css->esc("3a"), "\\33 a" );
        $this->assertEquals($css->esc("4a"), "\\34 a" );
        $this->assertEquals($css->esc("5a"), "\\35 a" );
        $this->assertEquals($css->esc("6a"), "\\36 a" );
        $this->assertEquals($css->esc("7a"), "\\37 a" );
        $this->assertEquals($css->esc("8a"), "\\38 a" );
        $this->assertEquals($css->esc("9a"), "\\39 a" );

        $this->assertEquals($css->esc("a0b"), "a0b" );
        $this->assertEquals($css->esc("a1b"), "a1b" );
        $this->assertEquals($css->esc("a2b"), "a2b" );
        $this->assertEquals($css->esc("a3b"), "a3b" );
        $this->assertEquals($css->esc("a4b"), "a4b" );
        $this->assertEquals($css->esc("a5b"), "a5b" );
        $this->assertEquals($css->esc("a6b"), "a6b" );
        $this->assertEquals($css->esc("a7b"), "a7b" );
        $this->assertEquals($css->esc("a8b"), "a8b" );
        $this->assertEquals($css->esc("a9b"), "a9b" );

        $this->assertEquals($css->esc("-0a"), "-\\30 a" );
        $this->assertEquals($css->esc("-1a"), "-\\31 a" );
        $this->assertEquals($css->esc("-2a"), "-\\32 a" );
        $this->assertEquals($css->esc("-3a"), "-\\33 a" );
        $this->assertEquals($css->esc("-4a"), "-\\34 a" );
        $this->assertEquals($css->esc("-5a"), "-\\35 a" );
        $this->assertEquals($css->esc("-6a"), "-\\36 a" );
        $this->assertEquals($css->esc("-7a"), "-\\37 a" );
        $this->assertEquals($css->esc("-8a"), "-\\38 a" );
        $this->assertEquals($css->esc("-9a"), "-\\39 a" );

        $this->assertEquals($css->esc("-"), "\\-" );
        $this->assertEquals($css->esc("-a"), "-a" );
        $this->assertEquals($css->esc("--"), "--" );
        $this->assertEquals($css->esc("--a"), "--a" );

        $this->assertEquals($css->esc("--a"), "--a" );
        $this->assertEquals($css->esc(".class"), "\.class" );
        $this->assertEquals($css->esc("#id"), "\#id" );

        $this->assertEquals($css->esc("\x80\x2D\x5F\xA9"), "\x80\x2D\x5F\xA9" );
        $this->assertEquals($css->esc("\x7F\x80\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8A\x8B\x8C\x8D\x8E\x8F\x90\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9A\x9B\x9C\x9D\x9E\x9F"), "\\7f \x80\x81\x82\x83\x84\x85\x86\x87\x88\x89\x8A\x8B\x8C\x8D\x8E\x8F\x90\x91\x92\x93\x94\x95\x96\x97\x98\x99\x9A\x9B\x9C\x9D\x9E\x9F" );
        $this->assertEquals($css->esc("\xA0\xA1\xA2"), "\xA0\xA1\xA2" );
        $this->assertEquals($css->esc("a0123456789b"), "a0123456789b" );
        $this->assertEquals($css->esc("abcdefghijklmnopqrstuvwxyz"), "abcdefghijklmnopqrstuvwxyz" );
        $this->assertEquals($css->esc("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), "ABCDEFGHIJKLMNOPQRSTUVWXYZ" );

        $this->assertEquals($css->esc("\x20\x21\x78\x79"), "\\ \\!xy" );

        // astral symbol (U+1D306 TETRAGRAM FOR CENTRE)
        $this->assertEquals($css->esc("\u{D834}\u{DF06}"), "\u{D834}\u{DF06}" );
        // lone surrogates
        $this->assertEquals($css->esc("\u{DF06}"), "\u{DF06}" );
        $this->assertEquals($css->esc("\u{D834}"), "\u{D834}" );
    }
}