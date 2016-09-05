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

/* https://github.com/h5bp/server-configs-apache/blob/02a601e5914a504e019c46b76bb21adab89cb686/src/.htaccess#L677-692
 * Requires htaccess rule:
 * <IfModule mod_rewrite.c>
 *   RewriteCond %{REQUEST_FILENAME} !-f
 *   RewriteRule ^(.+)\.(\d+)\.(css|cur|gif|ico|jpe?g|js|png|svgz?|webp)$ $1.$3 [L]
 * </IfModule>
 */
function cached_asset(string $asset) : string
{
    $realPath = public_path($asset);
    if (!file_exists($realPath)) {
        throw new InvalidArgumentException('File not found at ' . $realPath);
    }
    $hash = sprintf("%u",crc32(md5_file($realPath)));
    $extension = pathinfo($realPath, PATHINFO_EXTENSION);
    $stripped = substr($asset, 0, -(strlen($extension) + 1));
    $path = implode('.', [$stripped, $hash, $extension]);
    return asset($path);
}

