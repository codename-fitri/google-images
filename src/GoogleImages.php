<?php

declare(strict_types=1);

namespace Fitri\GoogleImages;

use DiDom\Document;
use Campo\UserAgent as UA;

function images($keyword, $options = [])
{
    $url = "https://www.google.com/search?q=" . urlencode($keyword) . "&source=lnms&tbm=isch&tbs=";
    $ua = UA::random([
        'os_type' => ['Windows', 'OS X'],
        'device_type' => 'Desktop'
    ]);
    $options  = [
        'http' => [
            'method'     =>"GET",
            'user_agent' =>  $ua,
        ],
        'ssl' => [
            "verify_peer"      => false,
            "verify_peer_name" => false,
        ],
    ];
    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $htmldom = new Document;
    $htmldom->loadHtml($response);
    $datasets = $htmldom->find('.rg_di > .rg_meta');
    return array_map(function ($dataset) {
        $jsondata = $dataset->text();
        $data = json_decode($jsondata);
        return [
            'imageId' => $data->id,
            'visibleUrl' => $data->isu,
            'height' => $data->oh,
            'width' => $data->ow,
            'url' => $data->ou,
            'content' => $data->pt,
            'originalContextUrl' => $data->ru,
            'title' => $data->s,
            'tbWidth' => $data->tw,
            'tbHeight' => $data->th,
            'tbUrl' => $data->tu,
            'filetype' => $data->ity
        ];
    }, $datasets);
}
