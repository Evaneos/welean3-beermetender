<?php

class BaseController extends Controller {

	protected $response;

	function __construct() {
		Response::macro(
            'customJson',
            function ($value, $code) {
                return Response::json(
                	$value,
                	$code,
                	array(
                		'Access-Control-Allow-Origin' => Config::get('app.cors.origin'),
                		'Access-Control-Allow-Credentials' => 'true'
                	)
                );
            }
        );
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}
}
