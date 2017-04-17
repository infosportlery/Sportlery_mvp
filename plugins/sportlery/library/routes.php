<?php

Route::pattern('provider', 'facebook|google');

Route::get('login/{provider}', [
    'as' => 'login.social',
    'uses' => 'Sportlery\Library\Classes\SocialLoginController@redirectToProvider',
]);

Route::get('login/{provider}/callback', [
    'as' => 'login.social.callback',
    'uses' => 'Sportlery\Library\Classes\SocialLoginController@handleProviderCallback',
]);
