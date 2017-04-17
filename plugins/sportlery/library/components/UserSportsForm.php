<?php

namespace Sportlery\Library\Components;

use Auth;
use Input;
use Redirect;
use Cms\Classes\ComponentBase;
use RainLab\Translate\Models\Message;
use Sportlery\Library\Models\Sport;

class UserSportsForm extends ComponentBase
{
    /**
     * Returns information about this component, including name and description.
     */
    public function componentDetails()
    {
        return [
            'name' => 'User sports form',
            'description' => 'Show a form to manage the user\'s sports',
        ];
    }

    public function onRun()
    {
        $user = \Auth::getUser();

        $this->page['userSports'] = $user->sports()->orderBy('name', 'asc')->get();
        $sports = Sport::orderBy('name', 'asc')->whereNotIn('id', $this->page['userSports']->lists('id'))->lists('name', 'id');
        $this->page['sports'] = ['' => '- '.Message::trans('Geen').' -'] + $sports;
        $this->page['userLevels'] = [
            1 => Message::trans('Beginner'),
            2 => Message::trans('Gevorderd'),
            3 => Message::trans('Pro'),
        ];
        $this->page['levels'] = ['' => '- '.Message::trans('Geen').' -'] + $this->page['userLevels'];
    }

    public function onUpdate()
    {
        $user = Auth::getUser();
        $userSports = implode(',', $user->sports()->lists('id'));
        $data = Input::all();
        $sportCustom = $data['sport_custom'];
        $data['sport_custom'] = str_slug($data['sport_custom']);
        $rules = [
            'sport_new_id' => 'exists:spr_sports,id|not_in:'.$userSports,
            'sport_new_level' => 'required_with:sport_new_id|in:1,2,3',
            'sport_custom' => 'unique:spr_sports,slug',
            'sport_custom_level' => 'required_with:sport_custom|in:1,2,3',
        ];
        foreach ($data['sport'] as $id => $level) {
            $rules["sport.$id"] = 'required|in:1,2,3';
        }
        $validator = \Validator::make($data, $rules, [
            'sport_custom.unique' => Message::trans('De sport ":sport" bestaat al.', ['sport' => e($sportCustom)])
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $data['sport_custom'] = $sportCustom;

        $syncData = [];

        foreach ($data['sport'] as $id => $level) {
            $syncData[$id] = ['favorite' => 1, 'level' => $level];
        }

        if ($data['sport_new_id']) {
            $syncData[$data['sport_new_id']] = [
                'favorite' => 1,
                'level' => $data['sport_new_level'],
            ];
        }

        if (trim($data['sport_custom'])) {
            $sport = new Sport;
            $sport->name = $data['sport_custom'];
            $sport->slug = str_slug($sport->name);
            $sport->save();
            $syncData[$sport->id] = ['favorite' => 1, 'level' => $data['sport_custom_level']];
        }

        $user->sports()->sync($syncData);

        return Redirect::refresh();
    }

    public function onDelete()
    {
        $sportId = post('sport_id');
        $user = \Auth::getUser();
        $user->sports()->detach($sportId);
        return Redirect::refresh();
    }
}
