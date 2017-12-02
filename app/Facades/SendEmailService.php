<?php
namespace App\Facades;


use Illuminate\Support\Facades\Facade;


class SendEmailService extends Facade {

	protected static function getFacadeAccessor() {

		return 'SendEmailService';
	}

}