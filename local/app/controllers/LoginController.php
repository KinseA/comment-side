<?php

class LoginController extends BaseController {
	public function facebook(){
		if(!User::loggedIn()){

			 // get data from input
		    $code = Input::get( 'code' );

		    // get fb service
		    $fb = OAuth::consumer( 'Facebook' );

		    // check if code is valid

		    // if code is provided get user data and sign in
		    if ( !empty( $code ) ) {

		        // This was a callback request from facebook, get the token
		        $token = $fb->requestAccessToken( $code );

		        // Send a request with it
		        $result = json_decode( $fb->request( '/me' ), true );

		       // $message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
		        //echo $message. "<br/>";
		        if(!$user=User::facebook($result['id'])){
		        	$user=new User();
		        	$user->username=$result['name'];
		        	$user->facebook_id=$result['id'];
		        	$user->joined=time();
		        	$user->rank=1;
					$user->save();
					$user=null;
				}
				$user=User::getByFacebook($result['id']);
				//TODO: Generate login string every time the user logs in. This is unique to that session
				foreach($user as $u){
					Cookie::make('user',$u->id,1209600);
					Session::put('user',$u->id);
				}

		    }
		    // if not ask for permission first
		    else {
		        // get fb authorization
		        $url = $fb->getAuthorizationUri();

		        // return to facebook login url
		        return Redirect::to( (string)$url );
		    }
		}
		return Redirect::back()->with('message','Operation Successful !');
	}

	public function youtube(){
		if(!User::loggedIn()){

			 // get data from input
		   /* $code = Input::get( 'code' );

		    // get fb service
		    $fb = OAuth::consumer( 'Google','http://localhost:8080/hubbubb-laravel' );

		    // check if code is valid

		    // if code is provided get user data and sign in
		    if ( !empty( $code ) ) {

		        // This was a callback request from facebook, get the token
		        $token = $fb->requestAccessToken( $code );

		        // Send a request with it
		        $result = json_decode( $fb->request( 'https://www.googleapis.com/youtube/v3/liveBroadcasts?part=id,contentDetails&mine=true' ), true );
		        print_r($result);

		       // $message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
		        //echo $message. "<br/>";
		        

		    }
		    // if not ask for permission first
		    else {
		        // get fb authorization
		        $url = $fb->getAuthorizationUri();

		        // return to facebook login url
		        return Redirect::to( (string)$url );
		    }*/

		    // get data from input
		    $code = Input::get( 'code' );

		    // get google service
		    $googleService = OAuth::consumer( 'Google' );

		    // check if code is valid

		    // if code is provided get user data and sign in
		    if ( !empty( $code ) ) {

		        // This was a callback request from google, get the token
		        $token = $googleService->requestAccessToken( $code );
		       

		        // Send a request with it
		        $result = json_decode( $googleService->request( 'https://www.googleapis.com/youtube/v3/liveBroadcasts?part=contentDetails,snippet,id&mine=true' ), true );

		         print_r($result);

		        //Var_dump
		        //display whole array().
		       //print_r($result);

		    }
    // if not ask for permission first
    else {
        // get googleService authorization
        $url = $googleService->getAuthorizationUri();

        // return to google login url
        return Redirect::to( (string)$url );
    }
		}
		//return Redirect::back()->with('message','Operation Successful !');
	}

	public function twitter(){
		// get data from input
		Session::put('url',URL::previous());
		if(!User::loggedIn()){
			$token = Input::get( 'oauth_token' );
		    $verify = Input::get( 'oauth_verifier' );

		    // get twitter service
		    $tw = OAuth::consumer( 'Twitter' );

		    // check if code is valid

		    // if code is provided get user data and sign in
		    if ( !empty( $token ) && !empty( $verify ) ) {

		        // This was a callback request from twitter, get the token
		        $token = $tw->requestAccessToken( $token, $verify );

		        // Send a request with it
		        $result = json_decode( $tw->request( 'account/verify_credentials.json' ), true );

		        if(!$user=User::twitter($result['id'])){ //checks if the user already exists
		        	$user=new User();
		        	$user->username=$result['name'];
		        	$user->twitter_id=$result['id'];
		        	$user->joined=time();
		        	$user->rank=1;
					$user->save();
					$user=null;
					
				}
				//TODO: Generate login string every time the user logs in. This is unique to that session
				$user=User::getByTwitter($result['id']);
				foreach($user as $u){
					Session::put('user',$u->id);
					Cookie::make('user',$u->id,1209600);
				}
				
		    }
		    // if not ask for permission first
		    else {
		        // get request token
		        $reqToken = $tw->requestRequestToken();

		        // get Authorization Uri sending the request token
		        $url = $tw->getAuthorizationUri(array('oauth_token' => $reqToken->getRequestToken()));

		        // return to twitter login url
		        return Redirect::to( (string)$url );
		    }
		}
		return Redirect::to(Session::get('url'))->with('message','Operation Successful !');
	    
	}

	public static function logout(){
		Session::put('url',URL::previous());
		User::logout();
		return Redirect::to(URL::previous());
	}

}
