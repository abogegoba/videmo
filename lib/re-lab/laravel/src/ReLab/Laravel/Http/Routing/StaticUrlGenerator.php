<?php

namespace ReLab\Laravel\Http\Routing;

use Illuminate\Routing\UrlGenerator;

/**
 * Class StaticUrlGenerator
 *
 * @package ReLab\Laravel\Http\Routing
 */
class StaticUrlGenerator extends UrlGenerator
{
    /**
     * StaticUrlGenerator constructor.
     *
     * @param UrlGenerator $url
     */
    public function __construct(UrlGenerator $url)
    {
        parent::__construct($url->routes, $url->request);
    }

    /**
     * Generate a URL to an application asset.
     *
     * @param string $path
     * @param bool|null $secure
     *
     * @return string
     */
    public function asset($path, $secure = true)
    {
        $url = parent::asset($path, $secure);
        $url = str_replace(env("APP_URL"), env("ASSET_URL") . "/", $url);
        return $url;
    }
}