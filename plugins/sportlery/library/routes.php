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
