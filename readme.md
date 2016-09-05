# Laravel 5.1 Helpers

* Register the service provider: `NZTim\Helpers\HelpersServiceProvider`

### Helper functions
* `autolink(string $content)` - autolink a string
* `flash($message, $type = 'danger')` - send flash messages to the view
* `markdown(string $content)` - renders Markdown GFM style including line breaks
* `sanitize(mixed $data)` - runs htmlspecialchars() and trim() on a string or an array of strings
* `cached_asset($path)` - returns asset path with hashed integer value, e.g. `main.css` -> `main.847389233.css`
  * Note: .htaccess rule is required, see below
  
### Blade directives
* `@autolink($string)` - sanitizes the string, autolinks and runs nl2br
* `@formerror` - echoes the error message with a Boostrap-compatible red background
* `@markdown` - sanitizes the string and then renders Markdown
* `@nl2br($string)` - sanitizes the string and then runs nl2br
* `@pagination($paginator)` - calls `render()`

 ### Validator extensions
 * `commonpwd` - ensures a password is not on a list of 10,000 common passwords

### .htaccess for `cached_asset()`
Add this before the Laravel rewrite rule
```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.+)\.(\d+)\.(bmp|css|cur|gif|ico|jpe?g|js|png|svgz?|webp|webmanifest)$ $1.$3 [L]
</IfModule>
```
