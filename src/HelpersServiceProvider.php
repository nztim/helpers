<?php namespace NZTim\Helpers;

use Blade;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Validator as LaravelValidator;
use Parsedown;
use Validator;

class HelpersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ====================================================================
        // Blade directives ---------------------------------------------------
        Blade::directive('nl2br', function($string) {
            return "<?php echo nl2br(sanitize($string)); ?>";
        });
        Blade::directive('autolink', function($string) {
            return "<?php echo nl2br(autolink(sanitize($string))); ?>";
        });
        Blade::directive('pagination', function($paginator) {
            return "<?php echo with($paginator)->appends(Request::except('page'))->render(); ?>";
        });
        Blade::directive('formerror', function($label) {
            return '<?php echo $errors->first(' . $label . ', \'<div class="alert alert-danger">:message</div>\'); ?>';
        });
        // Parsedown is set in the container to sanitize the output.
        // If you pre-encode, then code blocks are double-encoded, references:
        // https://github.com/erusev/parsedown/issues/50
        // https://github.com/erusev/parsedown/wiki/Tutorial:-Get-Started
        Blade::directive('markdown', function($string) {
            return "<?php echo markdown($string); ?>";
        });

        // ====================================================================
        // Validation ---------------------------------------------------------
        // Common passwords validator, based on https://github.com/unicodeveloper/laravel-password
        $validate = function($attribute, $value, $parameters, $validator) {
            $path = realpath(__DIR__.'/../config/common-passwords.txt');
            return !collect(explode("\n", str_replace("\r\n", "\n", file_get_contents($path))))->contains($value);
        };
        Validator::extend('commonpwd', $validate, 'This password is too common, please try a different one.');

        // File extension validator -------------------------------------------
        $validate = function($attribute, $value, $parameters, $validator) {
            /** @var UploadedFile $value */
            return in_array(strtolower($value->getClientOriginalExtension()), $parameters);
        };
        Validator::extend('fileext', $validate, 'Invalid file extension');

        // Date after or equal validator ---------------------------------
        $validate = function($attribute, $value, $parameters, $validator) { /** @var LaravelValidator $validator */
            $referenceDate = array_get($validator->getData(), $parameters[0], date('Y-m-d'));
            return strtotime($value) >= strtotime($referenceDate);
        };
        Validator::extend('after_or_equal', $validate, 'Invalid date');

        // ====================================================================
        // Commands -----------------------------------------------------------
        $this->commands([EnvCheckCommand::class]);
    }

    public function register()
    {
        $this->app->singleton(Parsedown::class, function () {
            return Parsedown::instance()->setMarkupEscaped(true)->setBreaksEnabled(true);
        });
    }
}
