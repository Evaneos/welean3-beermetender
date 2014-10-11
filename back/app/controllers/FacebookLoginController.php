<?php
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

class FacebookLoginController extends \BaseController {

	/**
	 * Authenticates a facebook user
	 *
	 * @return Response
	 */
	public function login()
	{
		FacebookSession::setDefaultApplication(Config::get('app.facebook_app.id'), Config::get('app.facebook_app.secret'));

		$auth = Input::json()->all();
		$token = $auth['token'];

		$session = new FacebookSession($token);
		$request = new FacebookRequest(
		    $session,
		    'GET',
		    '/me'
		);
		$response = $request->execute();
		$graphObject = $response->getGraphObject();

		if ($graphObject) {

			$facebook_id = $graphObject->getProperty('id');
			$email = $graphObject->getProperty('email');
			$name = $graphObject->getProperty('name');
			$user = User::where('facebook_user_id', $facebook_id)->first();

			if (!$user) {
				$user = new User();
				$user->name = $name;
				$user->username = $facebook_id;
				$user->password = Hash::make(rand());
				$user->email = $email;
				$user->facebook_user_id = $facebook_id;
				$user->save();
			}

			$rest_session = RestSession::where('user_id', $user->id)->first();

			if (!$rest_session) {
				$rest_session = new RestSession();
				$rest_session->user_id = $user->id;
				$rest_session->facebook_token = $token;
			}
			
			$rest_session->token = Hash::make($token);
			$rest_session->save();

			return Response::customJson(array(
			    'error' => false,
			    'data' => $rest_session
			), 200);
		}
	}
}
