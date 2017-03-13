<?php namespace Sportlery\Library\Components;

use Cms\Classes\ComponentBase;
use Input;
use Mail;
use Validator;
use Redirect;

class UserTickets extends ComponentBase {

	public function componentDetails() {
		return [
			'name' => 'User Ticket Support',
			'description' => 'Allows Users to create a new Ticket',

		];
	}

	public function onCreate() {

		$user = \Auth::getUser();
		$validator = Validator::make(
			[
				'subject' => Input::get('subject'),
				'content' => Input::get('content'),
			],
			[
				'subject' => 'required',
				'content' => 'required',

			]
		);

		// These variables are available inside the message as Twig
		$vars = ['name' => Input::get('name'), 'email' => Input::get('email'), 'content' => Input::get('content')];

		Mail::send('sportlery.library::mail.message', $vars, function($message) {

		    $message->to('ab.kalle@live.nl', 'Sportlery Ticket Support');
		    $message->subject(Input::get('subject'));

		});
	}
}