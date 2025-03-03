<?php

use PopupBox\Core\Routes;
use PopupBox\Http\Middleware\AuthJWT;
use PopupBox\Config;
use PopupBox\Services\Api\v1\API_PopupBox;
use PopupBox\Services\Api\v1\API_Token;

// list route api
$version = Config::get('api_version', 'v1');
$base_url = Config::get('base_url') . "/$version";
Routes::set_base_url($base_url);

Routes::add_route('GET', '/popup', API_PopupBox::class . '@index', [AuthJWT::class]);
Routes::add_route('GET', '/popup/{id}', API_PopupBox::class . '@show', [AuthJWT::class]);
Routes::add_route('POST', '/popup', API_PopupBox::class . '@create', [AuthJWT::class]);
Routes::add_route('PUT', '/popup/{id}', API_PopupBox::class . '@update', [AuthJWT::class]);
Routes::add_route('DELETE', '/popup/{id}', API_PopupBox::class . '@delete', [AuthJWT::class]);
Routes::add_route('PUT', '/popup_enable/{id}', API_PopupBox::class . '@update_enable', [AuthJWT::class]);

Routes::register_routes();