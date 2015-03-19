<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function index(){
		return 'hello';
	}

	public function homepage(){
		
		$input=Input::all();
		$data['header'] = View::make('templates/header')->render();
		$data['footer'] = View::make('templates/footer')->render();
		$data['features'] = Article::getFeature();
		$data['page']='home';
		return View::make('homepage',$data);
	}

	public function test($id){
		echo $id;
	}

}
