<?php namespace Sportlery\Contact\Components;


use Cms\Classes\ComponentBase;
use Input;
use Mail;

class ContactForm extends ComponentBase {

	public function componentDetails() {
		return [
			'name' => 'Contact Form', 
			'description' => 'Simple contact Form',	
		];
	}

	public function onSend() {

		// These variables are available inside the message as Twig		
		$vars = ['name' => Input::get('name'), 'email' => Input::get('email'), 'content' => Input::get('content')];

		Mail::send('sportlery.contact::mail.message', $vars, function($message) {

		    $message->to('foxhousegr@gmail.com', 'Sportlery Test');
		    $message->subject('This is a new message');

		});

	}
}