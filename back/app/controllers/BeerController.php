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
		$beers = Beer::where(function($query) {
			$query->where('user_from_id', '=', $authId)
				  ->orWhere('user_to_id', '=', $authId);
		})->get();

		return Response::json(array(
		    'error' => false,
		    'data' => $beers->toArray()
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
		$beer->what = $beer->what+$newBeer['what'];
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
		$beer = Beer::find($id);

		return Response::json(array(
		    'error' => false,
		    'data' => $beer
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
		$newBeer = Input::json()->all();

		$beer = Beer::find($id);

		$authId = Auth::user()->id;
		if ($beer['user_from_id'] != authId && $beer['user_from_id'] != authId) {
			return Response::json(array(
			    'error' => true,
			    'data' => 'You cannot touch this!'
			), 403);
		}

		$beer->number = $newBeer['number'];
		$beer->what = $newBeer['what'];
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

		$beer->delete();
 
  		return Response::json(array(
        	'error' => false
  		), 200);
	}


}
