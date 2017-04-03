<?php

Route::get('logout', function() {
    $user = Auth::getUser();

    Auth::logout();

    if ($user) {
        Event::fire('rainlab.user.logout', [$user]);
    }

    $url = Cms::url('/');
    Flash::success(Lang::get('rainlab.user::lang.session.logout'));

    return Redirect::to($url);
});

Route::pattern('provider', 'facebook|google');

Route::get('login/{provider}', [
    'as' => 'login.social',
    'uses' => 'Sportlery\Library\Classes\SocialLoginController@redirectToProvider',
]);

Route::get('login/{provider}/callback', [
    'as' => 'login.social.callback',
    'uses' => 'Sportlery\Library\Classes\SocialLoginController@handleProviderCallback',
]);
