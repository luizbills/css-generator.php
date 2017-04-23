# CSS Generator

Write css programatically using PHP.

## Install

```php
include_once 'path/to/class-css-generator.php';
```

## Usage

```php
// if `true` don't outputs indentation/tabs (default is `false`)
$compacted_output = false;
$css = new CSS_Generator( $compacted_output );

// single selector
$css->add_rule( '.color-white', [ 'color' => '#fff' ] );

$css->open_media('screen and (min-width: 30em)');

// multiple selectors
$css->add_rule( [ 'html', 'body' ], [
	'background-color' => 'black',
	'color' => 'white'
] );

// `close_media` method is optional in some situations.
// It is called automatically before `open_media` and `get_output`.
$css->close_media();

echo $css->get_output();
```

output:
```css
.color-white{
	color:#fff;
}
@media screen and (min-width: 30em){
	html,
	body{
		background-color:black;
		color:white;
	}
}

```

Changing `$compacted_output` to `true` it'll outputs:
```css
.color-white{color:#fff;}@media screen and (min-width: 30em){html,body{background-color:black;color:white;}}
```

There is also a method `add_raw` to add any string to your css. Useful to comments or include a framework.
```php
$css = new CSS_Generator();

$css->add_rule( '.color-white', [ 'color' => '#fff' ] );
$css->add_raw('/* my comment */ a { text-decoration: none }');

echo $css->get_output();
```

output:
```css
.color-white{
	color:#fff;
}
/* my comment */ a { text-decoration: none }
```

## License
MIT License &copy; 2017 Agência Zoop
