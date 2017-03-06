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

Route::post('friends/add', [
    'as' => 'friends.add', 'uses' => 'Sportlery\Library\Classes\FriendshipsController@addFriend'
]);
Route::post('friends/unfriend', [
    'as' => 'friends.unfriend', 'uses' => 'Sportlery\Library\Classes\FriendshipsController@unfriend'
]);
Route::post('friends/block', [
    'as' => 'friends.block', 'uses' => 'Sportlery\Library\Classes\FriendshipsController@block'
]);
Route::post('friends/unblock', [
    'as' => 'friends.unblock', 'uses' => 'Sportlery\Library\Classes\FriendshipsController@unblock'
]);
