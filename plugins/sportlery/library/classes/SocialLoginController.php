<?php

namespace Sportlery\Library\Classes;

use Auth;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Redirect;
use Laravel\Socialite\Facades\Socialite;
use RainLab\User\Models\User;
use Sportlery\Library\Models\SocialLogin;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return $this->getSocialiteDriver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            /** @var \Laravel\Socialite\Two\User $providerUser */
            $providerUser = $this->getSocialiteDriver($provider)->user();
        } catch (\Exception $e) {
            return Redirect::to('/login');
        }

        $socialLogin = SocialLogin::where('provider_user_id', $providerUser->getId())->where('provider', $provider)->first();

        if ($socialLogin) {
            Auth::login($socialLogin->user);
            return Redirect::to('/');
        }

        $socialLogin = new SocialLogin();
        $socialLogin->provider_user_id = $providerUser->getId();
        $socialLogin->provider = $provider;
        $socialLogin->access_token = $providerUser->token;
        $socialLogin->refresh_token = $providerUser->refreshToken;

        $user = User::where('email', $providerUser->getEmail())->first();

        $firstName = null;
        $lastName = null;
        $city = null;

        if ($provider === 'google') {
            $firstName = array_get($providerUser->user, 'name.givenName');
            $lastName = array_get($providerUser->user, 'name.familyName');
            $city = array_filter(array_get($providerUser->user, 'placesLived', []), function($lived) {
                return array_get($lived, 'primary', false);
            });
            $city = array_get(reset($city), 'value');
        } elseif ($provider === 'facebook') {
            $firstName = array_get($providerUser->user, 'first_name');
            $lastName = array_get($providerUser->user, 'last_name');
            $city = array_get($providerUser->user, 'location.name');
        }

        if (!$user) {
            $password = Str::random(16);
            $user = new User([
                'email' => $providerUser->getEmail(),
                'username' => $providerUser->getEmail(),
                'name' => $firstName,
                'surname' => $lastName,
                'password' => $password,
                'password_confirmation' => $password,
                'city' => $city,
            ]);
            // Activate the user.
            $user->activation_code = null;
            $user->is_activated = true;
            $user->activated_at = $user->freshTimestamp();
            $user->save();
        } else {
            if (!trim($user->name)) {
                $user->name = $firstName;
            }
            if (!trim($user->surname)) {
                $user->surname = $lastName;
            }
            $user->save();
        }

        $socialLogin->user()->associate($user);
        $socialLogin->save();

        Auth::login($user);

        return Redirect::to('/');
    }

    /**
     * @param $provider
     * @return mixed
     */
    private function getSocialiteDriver($provider)
    {
        $driver = Socialite::driver($provider);

        if ($provider === 'facebook') {
            $driver->scopes(['user_location']);
            $driver->fields([
                'name', 'first_name', 'last_name', 'email', 'gender', 'verified', 'link', 'location'
            ]);
        }

        return $driver;
    }
}
