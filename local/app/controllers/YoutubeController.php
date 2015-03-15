<?php

class YoutubeController extends BaseController {

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
	private $OAUTH2_CLIENT_ID ='892037698680-1gaq9d97f1b0gai1q1inrkpb7ntaj0rk.apps.googleusercontent.com',$OAUTH2_CLIENT_SECRET = 'xPT9yBB6fgAoD6a-DhjgUJGk';

	public function auth(){

		$path = public_path();
		$libs=$path.'/local/custom-libs/';
		$OAUTH2_CLIENT_ID = $this->OAUTH2_CLIENT_ID;
		$OAUTH2_CLIENT_SECRET = $this->OAUTH2_CLIENT_SECRET;
		  require_once $libs.'google-api/src/Google/autoload.php'; // or wherever autoload.php is located

require_once $libs.'google-api/src/Google/Client.php';
require_once $libs.'google-api/src/Google/Service/YouTube.php';
session_start();
/*
 * You can acquire an OAuth 2.0 client ID and client secret from the
 * {{ Google Cloud Console }} <{{ https://cloud.google.com/console }}>
 * For more information about using OAuth 2.0 to access Google APIs, please see:
 * <https://developers.google.com/youtube/v3/guides/authentication>
 * Please ensure that you have enabled the YouTube Data API for your project.
 */


$htmlBody='';
$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$client->setScopes('https://www.googleapis.com/auth/youtube');
$redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],
    FILTER_SANITIZE_URL);
$client->setRedirectUri($redirect);

// Define an object that will be used to make all API requests.
$youtube = new Google_Service_YouTube($client);

if (isset($_GET['code'])) {
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('The session state did not match.');
  }

  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: ' . $redirect);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}


if($client->isAccessTokenExpired()) {

    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));

}

// Check to ensure that the access token was successfully acquired.
if ($client->getAccessToken()) {
  try {
    // Execute an API request that lists broadcasts owned by the user who
    // authorized the request.
    $broadcastsResponse = $youtube->liveBroadcasts->listLiveBroadcasts(
        'id,snippet',
        array(
            'mine' => 'true',
        ));

    $htmlBody .= "<h3>Live Broadcasts</h3><ul>";
    foreach ($broadcastsResponse['items'] as $broadcastItem) {
      $htmlBody .= sprintf('<li>%s (%s)</li>', $broadcastItem['snippet']['title'],
          $broadcastItem['id']);
    }
    $htmlBody .= '</ul>';

  } catch (Google_ServiceException $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  }

  $_SESSION['token'] = $client->getAccessToken();
} else {
  // If the user hasn't authorized the app, initiate the OAuth flow
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;

  $authUrl = $client->createAuthUrl();
  $htmlBody = <<<END
  <h3>Authorization Required</h3>
  <p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
}

print_r($htmlBody);

//print_r($broadcastItem['snippet']['actualStartTime']); 
  
	}

}
