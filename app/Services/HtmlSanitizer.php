<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizer
{
    private HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,br,strong,em,b,i,u,a[href|title|target|rel],ul,ol,li,h2,h3,h4,h5,h6,blockquote,span,sub,sup');
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('HTML.TargetBlank', true);
        $config->set('AutoFormat.Linkify', true);

        $this->purifier = new HTMLPurifier($config);
    }

    public function clean(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        return $this->purifier->purify($html);
    }
}
