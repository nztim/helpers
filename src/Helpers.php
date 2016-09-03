<?php

function flash($message, $type = 'danger')
{
    session()->flash('flash_message', $message);
    session()->flash('flash_type', $type);
}

function sanitize($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize($value);
        }
        return $data;
    }
    return htmlspecialchars(trim($data), ENT_HTML5, 'UTF-8', false);
}

function autolink($content)
{
    return preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a target="_blank" rel="nofollow" href="$1">$1</a>', $content);
}

function markdown(string $content) : string
{
    return NZTim\Helpers\Markdown::instance()->render($content);
}

function active(string $uri)
{
    return request()->is($uri) ? 'active' : '';
}

function excerpt(string $content, int $maxLength = 150) : string
{
    $excerpt = str_replace(["\r", "\n"], " ", $content);
    if (strlen($excerpt) > $maxLength) {
        $excerpt = substr($excerpt, 0, $maxLength);
        $cutoff = strrpos($excerpt, ' ');
        $excerpt = substr($excerpt, 0, $cutoff);
        $excerpt .= '&hellip;';
    }
    return $excerpt;
}

function countrySelect() : array
{
    return include(__DIR__.DIRECTORY_SEPARATOR.'countries.php');
}
