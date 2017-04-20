<?php

namespace Sportlery\Library\Classes;

use Auth;
use Cms;
use Cms\Classes\Router;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Redirect;
use Laravel\Socialite\Facades\Socialite;
use RainLab\User\Models\User;
use Sportlery\Library\Models\SocialLogin;

class SocialLoginController extends Controller
{
    /**
     * The CMS page to redirect to after the user has been logged in.
     *
     * @var string
     */
    private $redirectPage = 'settings';

    /**
     * The OctoberCMS router used to generate URLs to CMS pages.
     *
     * @var \Cms\Classes\Router
     */
    private $cmsRouter;

    /**
     * Create a new social login controller instance.
     *
     * @param \Cms\Classes\Router $router
     */
    public function __construct(Router $router)
    {
        $this->cmsRouter = $router;
    }

    /**
     * Redirect the user to the social network's login page.
     *
     * @param  string  $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        return $this->getSocialiteDriver($provider)->redirect();
    }

    /**
     * Handle creating a new user / logging in the user after they get redirected back
     * from the social network's login page.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        try {
            $providerUser = $this->getSocialiteDriver($provider)->user();
        } catch (\Exception $e) {
            return $this->redirectToCmsPage('login');
        }

        $socialLogin = SocialLogin::where('provider_user_id', $providerUser->getId())
                                  ->where('provider', $provider)
                                  ->first();

        if ($socialLogin) {
            // The user has already logged in using this social network before,
            // so we can just login and redirect them.
            Auth::login($socialLogin->user);

            if ($socialLogin->user->hasCompletedProfile()) {
                $this->redirectPage = 'home';
            }

            return $this->redirectToCmsPage($this->redirectPage);
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
            $city = explode(',', array_get(reset($city), 'value', ''));
            $city = trim(reset($city));
        } elseif ($provider === 'facebook') {
            $firstName = array_get($providerUser->user, 'first_name');
            $lastName = array_get($providerUser->user, 'last_name');
            $city = explode(',', array_get($providerUser->user, 'location.name', ''));
            $city = trim(reset($city));
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

            // Activate the user. This will also send them a welcome email.
            $user->attemptActivation($user->getActivationCode());
        } else {
            if (!trim($user->name)) {
                // Only overwrite the first name if it is currently empty.
                $user->name = $firstName;
            }

            if (!trim($user->surname)) {
                // Only overwrite the last name if it is currently empty.
                $user->surname = $lastName;
            }

            $user->save();
        }

        $socialLogin->user()->associate($user);
        $socialLogin->save();

        Auth::login($user);

        return $this->redirectToCmsPage($this->redirectPage);
    }

    /**
     * Create a new Socialite driver for the given provider.
     *
     * @param  string  $provider
     * @return \Laravel\Socialite\Two\AbstractProvider
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

    /**
     * Create a redirect to the CMS page with the given file name.
     * This will look for pages in the /themes/sportlery/pages folder.
     *
     * @param  string  $fileName
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToCmsPage($fileName)
    {
        $url = $this->cmsRouter->findByFile($fileName);

        return Redirect::to(Cms::url($url));
    }
}
