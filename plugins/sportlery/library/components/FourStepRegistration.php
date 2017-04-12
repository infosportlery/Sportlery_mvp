<?php

namespace Sportlery\Library\Components;

use Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use October\Rain\Exception\ValidationException;
use RainLab\Location\Models\Setting;
use Redirect;
use Cms\Classes\ComponentBase;
use Sportlery\Library\Models\Sport;

class FourStepRegistration extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'Four step registration',
            'description' => 'Add the four step registration modal.',
        ];
    }

    public function init()
    {
        $user = Auth::getUser();
        if (!$user) {
            return;
        }
        $component = $this->addComponent(
            'NetSTI\Uploader\Components\ImageUploader',
            'imageUploader',
            ['modelClass'=>get_class($user), 'modelKeyColumn'=>'avatar', 'deferredBinding' => false]
        );

        $component->bindModel('avatar', $user);
    }

    public function onRun()
    {
        if (!Auth::check()) {
            // Render nothing if the user is not authenticated.
            $currentStep = true;
            return '';
        }

        $currentStep = $this->determineCurrentStep();

        $this->page['currentStep'] = $currentStep;
        $this->page['show'] = $currentStep !== true;

        if ($currentStep == 2) {
            $apiKey = Setting::get('google_maps_key');
            $this->addJs('//maps.googleapis.com/maps/api/js?libraries=places&key='.$apiKey);
        }

        if ($currentStep > 2) {
            $sportQuery = Sport::orderBy('name', 'asc');
            if ($currentStep == 4) {
                $idList = Auth::getUser()->favoriteSports()->lists('id');
                $sportQuery->whereNotIn('id', $idList);
            }
            $this->page['sports'] = ['' => '- None -'] + $sportQuery->lists('name', 'id');
            $this->page['levels'] = ['' => '- None -', 1 => 'Beginner', 2 => 'Intermediate', 3 => 'Pro'];
        }
    }

    public function onNextStep()
    {
        $currentStep = (int) post('current_step');
        $user = Auth::getUser();

        if ($currentStep == 1) {
            $rules = [
                'name' => 'required',
                'surname' => 'required',
            ];

            $validator = Validator::make(post(), $rules);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user->update(Input::only('name', 'surname'));
        } elseif ($currentStep == 2) {
            $rules = [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'street' => 'required',
                'city' => 'required',
                'country' => 'required',
            ];

            $validator = Validator::make(post(), $rules);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user->update(Input::only('latitude', 'longitude', 'street', 'city', 'zip_code', 'state', 'country'));
        } elseif ($currentStep == 3) {
            $sports = array_filter(post('sport'), function($sport) {
                return $sport['id'] !== '';
            });

            if (empty($sports) && empty(post('sport_custom'))) {
                throw new ValidationException(['sport' => 'Please pick at least one sport']);
            }

            $rules = [
                'sport_custom' => 'alphadash|unique:spr_sports,name',
                'sport_custom_level' => 'required_with:sport_custom|in:1,2,3',
            ];
            foreach ($sports as $index => $sport) {
                $rules['sport.'.$index.'.id'] = 'required|exists:spr_sports,id';
                $rules['sport.'.$index.'.level'] = 'required|in:1,2,3';
            }

            $validator = Validator::make(['sport' => $sports], $rules, [
                'sport.required' => 'Please pick at least 1 sport',
                'sport.*.id.required' => 'Please pick a sport',
                'sport.*.id.exists' => 'Please pick a sport from the list',
                'sport.*.level.required' => 'Please pick a level',
                'sport.*.level.in' => 'Please pick a level from the list',
            ]);

            if ($validator->fails()) {
                throw new ValidationException(['sport' => 'Please pick a sport and a level']);
            }

            $syncList = [];
            if ($currentStep === 3){
                $user->favoriteSports()->detach();
            } else {
                $user->interestedSports()->detach();
            }

            foreach ($sports as $sport) {
                if (!isset($syncList[$sport['id']])) {
                    $syncList[$sport['id']] = ['favorite' => $currentStep == 3, 'level' => $sport['level']];
                }
            }

            if (trim(post('sport_custom'))) {
                $sport = new Sport;
                $sport->name = post('sport_custom');
                $sport->slug = str_slug($sport->name);
                $user->sports()->save($sport, ['favorite' => 1, 'level' => post('sport_custom_level')]);
            }

            $user->sports()->sync($syncList, false);
        }

        $currentStep++;

        if ($currentStep == 2) {
            $this->page['apiKey'] = Setting::get('google_maps_key');
        }

        if ($currentStep > 3) {
            return Redirect::refresh();
        }
        $this->page['user'] = Auth::getUser();
        $this->page['isPartial'] = true;
        if ($currentStep > 2) {
            $sportQuery = Sport::orderBy('name', 'asc');
            $this->page['sports'] = ['' => '- None -'] + $sportQuery->lists('name', 'id');
            $this->page['levels'] = ['' => '- None -', 1 => 'Beginner', 2 => 'Intermediate', 3 => 'Pro'];
        }

        return ['#four-step-registration' => $this->renderPartial($this.'::step-'.$currentStep)];
    }

    private function determineCurrentStep()
    {
        $user = Auth::getUser();

        if (!trim($user->name) || !trim($user->surname)) {
            return 1;
        }

        if (!trim($user->street) || !trim($user->zip_code) || !trim($user->city) || is_null($user->latitude) || is_null($user->longitude)) {
            return 2;
        }

        if ($user->favoriteSports()->count() == 0) {
            return 3;
        }

        return true;
    }
}
