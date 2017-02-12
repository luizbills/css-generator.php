# CSS Generator

Write css programatically using PHP.

## Install

```php
include_once 'path/to/class-css-generator.php';
```

## Usage

write this:
```php
$css = new CSS_Generator();

for ($i = 1; $i <= 4; $i++) {
	// single selector
	$css->add_rule( '.color-' . $i, array(
		'color' => 'rgb(200, 200,' . ($i * 20) . ')',
	) );
}

$css->open_media('screen and (min-width: 30em)');

// multiple selectors
$css->add_rule( array( 'html', 'body' ), array(
		'background-color' => 'black',
		'color' => 'white'
	)
);

$css->close_media();
```

to output this:
```css
.color-1{
	color:rgb(200, 200,20);
}
.color-2{
	color:rgb(200, 200,40);
}
.color-3{
	color:rgb(200, 200,60);
}
.color-4{
	color:rgb(200, 200,80);
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

## License
MIT License &copy; 2017 Agência Zoop
