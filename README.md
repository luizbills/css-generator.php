# CSS Generator

Write css programatically using PHP.

## Install

```php
include_once 'path/to/class-css-generator.php';
```

## Usage

```php
$css = new CSS_Generator();

// single selector
$css->add_rule( '.color-white', array(
	'color' => '#fff',
) );

$css->open_media('screen and (min-width: 30em)');

// multiple selectors
$css->add_rule( array( 'html', 'body' ), array(
		'background-color' => 'black',
		'color' => 'white'
	)
);

// `close_media` method is optional in some situations.
// It is called automatically before `open_media` and `get_output`.
$css->close_media();

// write your css
file_puts_content( 'path/to/file.css', $css->get_output() );
```

content of `file.css`:
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

If you pass a `true` on constructor, the generated css will be minified. Example:
```php
$css = new CSS_Generator(true);

$css->open_media('screen and (min-width: 30em)');

$css->add_rule( array( 'html', 'body' ), array(
		'background-color' => 'black',
		'color' => 'white'
	)
);
```

outputs:
```css
@media screen and (min-width: 30em){html,body{background-color:black;color:white;}}
```

There is also a method `add_raw` to add any string to your css.
```php
$css = new CSS_Generator();

$css->add_raw('/* my comment */ a { text-decoration: none }');
```

outputs:
```css
/* my comment */ a { text-decoration: none }
```

## License
MIT License &copy; 2017 Agência Zoop
