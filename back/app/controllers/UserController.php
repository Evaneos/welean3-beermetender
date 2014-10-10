<?php

class UserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Response::customJson(array(
		    'error' => true,
		    'data' => 'You cannot access this!'
		), 403);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$newUser = Input::json()->all();

		$user = User::where(function($query) use ($newUser) {
			$query->where('username', '=', $newUser['username']);
			
			if (array_key_exists('email', $newUser)) {
				$query->orWhere('email', '=', $newUser['email']);
			}

			if (array_key_exists('facebook_user_id', $newUser)) {
				$query->orWhere('facebook_user_id', '=', $newUser['facebook_user_id']);
			}
		})->first();

		if ($user) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'User already exists!'
			), 403);
		}

		$user = new User();
		$user->email = $newUser['email'];
		$user->username = $newUser['username'];
		$user->password = Hash::make($newUser['password']);
		$user->facebook_user_id = $newUser['facebook_user_id'];
		$user->save();

		return Response::customJson(array(
		    'error' => false,
		    'data' => $user
		), 200);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::where('facebook_user_id', $id)->first();

		if (!$user) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'User not found!'
			), 404);
		}

		return Response::customJson(array(
		    'error' => false,
		    'data' => $user
		), 200);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = User::where('facebook_user_id', $id)->first();

		if (!$user) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'User not found!'
			), 404);
		}

		$newUser = Input::json()->all();

		$authId = Auth::user()->id;
		if ($user->id != authId || $user->facebook_image != $newUser['facebook_image']) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'You cannot update this!'
			), 403);
		}

		$user->email = $newUser['email'];
		$user->username = $newUser['username'];
		$user->password = Hash::make($newUser['password']);
		$user->save();

		$user->delete();
 
  		return Response::customJson(array(
        	'error' => false
  		), 200);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = User::where('facebook_user_id', $id)->first();

		if (!$user) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'User not found!'
			), 404);
		}

		$authId = Auth::user()->id;
		if ($user->id != authId) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'You cannot delete this!'
			), 403);
		}

		$user->delete();
 
  		return Response::customJson(array(
        	'error' => false
  		), 200);
	}

	public static function serializeCollection($users) {
		$serializedArray = array();
		foreach($users as $user) {
		    $serializedArray[] = self::serializeobject($user);
		};

		return $serializedArray;
	}

	public static function serializeObject($user) {
		$serializedUser = new stdClass();

		$serializedUser->id = $user->facebook_user_id;
		$serializedUser->name = $user->name;
		$serializedUser->email = $user->email;

		return $serializedUser;
	}
}
