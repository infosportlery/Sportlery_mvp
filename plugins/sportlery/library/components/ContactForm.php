<?php 

namespace Sportlery\Library\components;

use Cms\Classes\ComponentBase;
use Input;
use Mail;


/**
*  Ticket Form (contact Form)
*/
class ContactForm extends ComponentBase
{
	
	public function componentDetails()
	{
		return [
			'name' => 'Contact/Ticket Form',
			'description' => 'A Contact Form (used for tickets)',
		];
	}

	public function onRun() {
    }

    public function onSend() {
    	// push to db and send
    	$vars = ['name' => 'test', 'content' => Input::get('content')];

    	Mail::send('sportlery.library::mail.message', $vars, function($message){
		
			$message->to('ab.kalle@live.nl', 'Sportlery Ticket Support');
	    	$message->subject(Input::get('subject'));
	    	var_dump($message);
    	});

    	

    }
}