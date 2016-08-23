<?php namespace NZTim\Helpers;

use Blade;
use Illuminate\Support\ServiceProvider;
use Validator;

class HelpersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Blade directives ---------------------------------------------------
        Blade::directive('nl2br', function($string) {
            return "<?php echo nl2br(sanitize($string)); ?>";
        });
        Blade::directive('autolink', function($string) {
            return "<?php echo nl2br(autolink(sanitize($string))); ?>";
        });
        Blade::directive('pagination', function($paginator) {
            return "<?php echo with($paginator)->render(); ?>";
        });
        Blade::directive('formerror', function($label) {
            return '<?php echo $errors->first(' . $label . ', \'<div class="alert alert-danger">:message</div>\'); ?>';
        });
        Blade::directive('markdown', function($string) {
            return "<?php echo markdown(sanitize($string)); ?>";
        });

        // Common passwords validator, based on https://github.com/unicodeveloper/laravel-password
        $validate = function($attribute, $value, $parameters, $validator) {
            $path = realpath(__DIR__.'/../config/common-passwords.txt');
            return !collect(explode("\n", str_replace("\r\n", "\n", file_get_contents($path))))->contains($value);
        };
        Validator::extend('commonpwd', $validate, 'This password is too common, please try a different one.');
    }

    public function register()
    {
        //
    }
}
