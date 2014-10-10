<?php

class BaseController extends Controller {

	protected $response;

	function __construct() {
		Response::macro(
            'customJson',
            function ($value, $code) {
<<<<<<< Updated upstream
                return Response::json(
                	$value,
                	$code,
                	array(
                		'Access-Control-Allow-Origin' => 'http://www.beer-me-tender.local:8100',
                		'Access-Control-Allow-Credentials' => 'true'
                	)
                );
=======
                return Response::json($value, $code, array('Access-Control-Allow-Origin' => 'http://www.beer-me-tender.local:8100', 'Access-Control-Allow-Credentials' => 'true'));
>>>>>>> Stashed changes
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
