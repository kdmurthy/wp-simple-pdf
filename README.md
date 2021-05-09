# WpSimplePDF - Simple bindings for using `mpdf`

`wp-simple-pdf` is a WordPress plugin that provides integration with [mpdf](https://mpdf.github.io/) - a PHP library which generates PDF files from UTF-8 encoded HTML.

## Description

`wp-simple-pdf` provides binding to `mpdf` using well defined integration points. The plugin provides some
functions and filters using which you can integrate `mpdf` into your WordPress installation.

`wp-simple-pdf` doesn't provide any UI - you need to write code to make use of the plugin.

## Using

Use `\WpSimplePDF\SimplePDF::url` method to create a link and display in anywhere you require either on the front-end or back-end.

## `wp-simple-pdf` API

### Functions

```php
	\WPSimplePDF\SimplePDF::url( $post = 0, $args = array(), $echo = false )
```
> Returns the URL for a given post.
>
> ### Parameters
>
> **$post**
>   (int|WP_Post)(Optional) - Post ID or WP_Post object. Defaults to global $post.
>
> **$args**
>   (array) (Optional) Extra query arguments to add to the URL. Defaults to array() 
>
> **$echo**
>   (bool) (Optional) Whether to echo or return the url. Default value: false
>
> ### Return
> (void|string) Void if $echo argument is true, the url if $echo is false.

> ### More Information
>
> This function returns the URL after adding a query argument `output=pdf`. Doesn't perform any security checks
> to see whether the user has permissions etc.


```php
	\WPSimplePDF\SimplePDF::post_row_action( $post = 0, $args = array(), $echo = false )
```
> Returns or outputs the HTML markup for a `<a>` tag.
>
> ### Parameters
>
> **$post**
>   (int|WP_Post)(Optional) - Post ID or WP_Post object. Defaults to global $post.
>
> **$args**
>   (array) (Optional) Extra query arguments to add to the URL. Defaults to array() 
>
> **$echo**
>   (bool) (Optional) Whether to echo or return the url. Default value: false
>
> ### Return
> (void|string) Void if $echo argument is true, the html if $echo is false.


```php
	\WPSimplePDF\SimplePDF::output_pdf( $html, $args = array() )
```
> Uses `mpdf` to output the PDF. `$args` can be used to control the process.
>
> ### Parameters
>
> **$html**
>   (string) - The HTML markup to output.
>
> **$args**
>   (array) (Optional) Arguments to the `\Mpdf\Mpdf` constructor. Defaults to:
>
```php
	array(
			'mode'              => '',
			'format'            => 'A4',
			'default_font_size' => 0,
			'default_font'      => '',
			'margin_left'       => 15,
			'margin_right'      => 15,
			'margin_top'        => 16,
			'margin_bottom'     => 16,
			'margin_header'     => 9,
			'margin_footer'     => 9,
			'orientation'       => 'P',
		);
```
> ### Return
> (void)


### Filters

```php
	apply_filters( 'simplepdf_pdf_print_template', string $template )
```
> Filters the template used to generate PDF.
>
> ### Parameters
>
> **$template**
>   (string) - The template used.
>
>
> ### Return
> (string) The new template.

> ### More Information
>
> `simple_pdf_print_template` is a filter applied to the default template. `wp-simple-pdf` uses either `single.php`
> or `index.php` from the active theme as default template. In most cases, you need to use this filter to provide
> a proper `template` file for PDF generation.
>

```php
	apply_filters( 'simplepdf_output_pdf_args', $args )
```
> Filters the arguments passed to `Mpdf` constructor.
>
> ### Parameters
>
> **$args**
>   (array) - The arguments used.
>
> ### More Information
>
> `simplepdf_output_pdf_args` filter is used to filter the arguments passed `Mpdf` constructor. All aspects of
> the PDF generation can be modified by setting values in this array. For more information look at [Configuration v7+](https://mpdf.github.io/configuration/configuration-v7-x.html) in [MPDF](https://mpdf.github.io/) manual.

