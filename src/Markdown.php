<?php namespace NZTim\Helpers;

use Parsedown;

class Markdown
{
    protected static $instance;
    protected $parsedown;

    public static function instance() : Markdown
    {
        if (is_null(static::$instance)) {
            static::$instance = new static(new Parsedown());
        }
        return static::$instance;
    }

    protected function __construct(Parsedown $parsedown)
    {
        $this->parsedown = $parsedown;
        $this->parsedown->setBreaksEnabled(true);
    }

    public function render(string $content) : string
    {
        return $this->parsedown->text($content);
    }
}
