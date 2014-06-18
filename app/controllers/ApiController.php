<?php

class ApiController extends \BaseController {

	protected $statusCode = 200;

	public function getStatusCode() {
		return $this->statusCode;
	}

	public function setStatusCode($statusCode) {
		$this->statusCode = $statusCode;
		return $this;
	}

	public function respondNotFound($message = 'Not found') {
		return $this->setStatusCode(404)->respondWithError($message);
	}

	public function respond($data, $headers = []) {
		return Response::json($data, $this->getStatusCode(), $headers);
	}

	public function respondNoContent($headers = []) {
		return $this->setStatusCode(204)->respond(array(), $headers);
	}

	public function respondInvalidApi($message = 'Invalid Api') {
		return $this->setStatusCode(401)->respondWithError($message);
	}

	public function respondInsufficientPrivileges($message = 'Insufficient Privileges') {
		return $this->setStatusCode(403)->respondWithError($message);
	} 

	public function respondServerError($message = 'Server error') {
		return $this->setStatusCode(500)->respondWithError($message);
	}

	public function respondWithError($message) {
		return $this->respond([
			'error' => [
				'message' => $message,
				'status_code' => $this->getStatusCode()
			]
		]);
	}

}