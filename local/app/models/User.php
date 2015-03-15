<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password', 'remember_token');
	public static function facebook($id){
		if(self::where('facebook_id','=',$id)->count()){
			return true;
		}
		return false;
	}

	public static function twitter($id){
		if(self::where('twitter_id','=',$id)->count()){
			return true;
		}
		return false;
	}

	public static function getByTwitter($id){
		$user=self::where('twitter_id','=',$id);
		if($user->count()){
			return $user->get();
		}
		return false;
	}

	public static function getByFacebook($id){
		$user=self::where('facebook_id','=',$id);
		if($user->count()){
			return $user->get();
		}
		return false;
	}

	public static function loggedIn(){
		if(Cookie::get('user')){
			Session::put('user',Cookie::get('user'));
		}
		
		if(Session::has('user')){
			return true;
		}else{
			return false;
		}
	}

	public static function logout(){
		Cookie::forget('user');
		Session::forget('user');
	}

	public static function hasPermission($key){
		if(self::loggedIn()){
			$user=self::where('users.id','=',Session::get('user'))->join('ranks','rank','=','ranks.id')->get();
			$perms=$user[0]->permission;
			if($perms){
				$permissions=json_decode($perms);//returns object by default. True returns an array
				if(isset($permissions->$key)){
					if($permissions->$key){
						return true;
					}
				}
			}
			return false;
		}
	}

}
