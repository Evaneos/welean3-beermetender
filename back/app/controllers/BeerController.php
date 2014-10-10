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

		return Response::json(array(
		    'error' => false,
		    'data' => $this->serializeCollection($beers)
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
		if ($newBeer['user_from_id'] != $authId) {
			return Response::json(array(
			    'error' => true,
			    'data' => 'You cannot create this!'
			), 403);
		}

		$beer = Beer::where(function($query) use ($newBeer) {
			$query->where('user_from_id', '=', $newBeer['user_from_id'])
				  ->where('user_to_id', '=', $newBeer['user_to_id']);
		})->first();

		if (!$beer) {
			$beer = new Beer();
			$beer->user_from_id = $newBeer['user_from_id'];
			$beer->user_to_id = $newBeer['user_to_id'];
		}

		$beer->number = $newBeer['number'];
		$beer->save();

		return Response::json(array(
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
			return Response::json(array(
			    'error' => true,
			    'data' => 'Beer not found!'
			), 404);
		}

		return Response::json(array(
		    'error' => false,
		    'data' => $this->serializeObject($beer)
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
			return Response::json(array(
			    'error' => true,
			    'data' => 'Beer not found!'
			), 404);
		}

		$authId = Auth::user()->id;
		if ($beer->user_from_id != authId && $beer->user_to_id != authId) {
			return Response::json(array(
			    'error' => true,
			    'data' => 'You cannot touch this!'
			), 403);
		}

		$newBeer = Input::json()->all();

		$beer->number = $newBeer['number'];
		$beer->save();

		return Response::json(array(
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
			return Response::json(array(
			    'error' => true,
			    'data' => 'Beer not found!'
			), 404);
		}

		$authId = Auth::user()->id;
		if ($beer->user_from_id != authId && $beer->user_to_id != authId) {
			return Response::json(array(
			    'error' => true,
			    'data' => 'You cannot delete this!'
			), 403);
		}

		$beer->delete();
 
  		return Response::json(array(
        	'error' => false
  		), 200);
	}

	protected function serializeCollection($beers) {
		$serializedArray = array();
		foreach($beers as $beer) {
		    $serializedArray[] = $this->serializeobject($beer);
		};

		return $serializedArray;
	}

	protected function serializeObject($beer) {
		$serializedBeer = new stdClass();

		$serializedBeer->id = $beer->id;

		$authId = Auth::user()->id;
		if ($beer->user_from_id == $authId) {
			$serializedBeer->user = $beer->userTo;
			$serializedBeer->balance = $beer->number;
		} else {
			$serializedBeer->user = $beer->userFrom;
			$serializedBeer->balance = $beer->number;
		}

		return $serializedBeer;
	}
}
