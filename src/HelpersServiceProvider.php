<?php namespace NZTim\Helpers;

use Blade;
use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{
    public function boot()
    {
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
    }

    public function register()
    {
        //
    }
}
