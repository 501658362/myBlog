<?php
namespace App\Facades;


use Illuminate\Support\Facades\Facade;


class PostServices extends Facade {

	protected static function getFacadeAccessor() {

		return 'PostServices';
	}

}