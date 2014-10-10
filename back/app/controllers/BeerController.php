<?php

class BeerController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$authId = Auth::user()->id;
		$beers = Beer::with('userFrom')->with('userTo')->where(function($query) use($authId) {
			$query->where('user_from_id', '=', $authId)
				  ->orWhere('user_to_id', '=', $authId);
		})->get();

		return Response::customJson(array(
		    'error' => false,
		    'data' => self::serializeCollection($beers)
		), 200);
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
		$newBeer = Input::json()->all();
		$authId = Auth::user()->id;

		$userFrom = User::find($authId);
		$userTo = User::where('facebook_user_id', $newBeer['user_to_id'])->first();

		if (!$userFrom || !$userTo || ($userFrom->id != $authId && $userTo->id != $authId)) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'You cannot create this!'
			), 403);
		}

		$beer = Beer::where(function($query) use ($newBeer, $userFrom, $userTo) {
			$query->where('user_from_id', '=', $userFrom->id)
				  ->where('user_to_id', '=', $userTo->id);
		})->orWhere(function($query) use ($newBeer, $userFrom, $userTo) {
			$query->where('user_from_id', '=', $userTo->id)
				  ->where('user_to_id', '=', $userFrom->id);
		})->first();

		if (!$beer) {
			$beer = new Beer();
			$beer->user_from_id = $userFrom->id;
			$beer->user_to_id = $userTo->id;
		}

		$beer->number = $newBeer['balance'] ;
		$beer->save();

		return Response::customJson(array(
		    'error' => false,
		    'data' => $beer
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
		$beer = Beer::with('userFrom')->with('userTo')->find($id);

		if (!$beer) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'Beer not found!'
			), 404);
		}

		return Response::customJson(array(
		    'error' => false,
		    'data' => self::serializeObject($beer)
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
		$beer = Beer::find($id);

		if (!$beer) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'Beer not found!'
			), 404);
		}

		$authId = Auth::user()->id;
		if ($beer->user_from_id != $authId && $beer->user_to_id != $authId) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'You cannot touch this!'
			), 403);
		}

		$newBeer = Input::json()->all();

		$balance = $newBeer['balance'];

		$beer->number = $balance;
		$beer->save();

		return Response::customJson(array(
		    'error' => false,
		    'data' => $beer
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
		$beer = Beer::find($id);

		if (!$beer) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'Beer not found!'
			), 404);
		}

		$authId = Auth::user()->id;
		if ($beer->user_from_id != $authId && $beer->user_to_id != $authId) {
			return Response::customJson(array(
			    'error' => true,
			    'data' => 'You cannot delete this!'
			), 403);
		}

		$beer->delete();
 
  		return Response::customJson(array(
        	'error' => false
  		), 200);
	}

	public static function serializeCollection($beers) {
		$serializedArray = array();
		foreach($beers as $beer) {
		    $serializedArray[] = self::serializeobject($beer);
		};

		return $serializedArray;
	}

	public static function serializeObject($beer) {
		$serializedBeer = new stdClass();

		$serializedBeer->id = $beer->id;

		$authId = Auth::user()->id;
		if ($beer->user_from_id == $authId) {
			$serializedBeer->user = UserController::serializeObject($beer->userTo);
			$serializedBeer->balance = 0-$beer->number;
		} else {
			$serializedBeer->user = UserController::serializeObject($beer->userFrom);
			$serializedBeer->balance = $beer->number;
		}

		return $serializedBeer;
	}
}
