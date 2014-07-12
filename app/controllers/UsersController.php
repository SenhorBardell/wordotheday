<?php

class UsersController extends ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::all();
		return $this->respond($this->transformCollection($users));
	}

	public function adminauth() {

		if (!Input::has('user')) {
			return $this->respondInsufficientPrivileges('No user');
		}

		if (!Input::has('password')) {
			return $this->respondInsufficientPrivileges('No password provided');
		}

		if (Input::get('user') == 'admin' && Input::get('password') == 'root') {
			return $this->respondNoContent();
		} else {
			return $this->respondInsufficientPrivileges('User or pass is wrong');
		}

	}

	/**
	 * Ugly and wrong, but thi will get the word when user open an app for a first time
	 * 
	 * @param  $id user_id
	 * @return Response
	 */
	public function firstword($id) {
		$word = Setting::find(1)->word_id;
		return $this->respond($word);
	}

	/**
	 * Daily bonus
	 * 
	 * @param  $id
	 * @return Response
	 */
	public function getbonus($id) {
		$user = User::find($id);

		if ($user->word_id == '0')
			return $this->respondInsufficientPrivileges('User has already got bonus');

		$user->word_id = 0;
		$user->balance = $user->balance + Setting::first()->daily_bonus;
		$user->save();
		return $this->respond($this->transform($user));
	}

	public function addlife($user_id) {
		
		$validator = Validator::make(array(
			'id' => $user_id
		), array(
			'id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$user = User::find($user_id);
		if ($user) {
			if ($user->balance > 3) {
				$user->balance--;
				$user->save();
				return $this->respond($this->transform($user));
			} else {
				return $this->respondInsufficientPrivileges('Not enough money');
			}
		} else {
			return $this->respondNotFound('User not found');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validation = User::validate(Input::all());

		if ($validation->fails())
		 	return $this->respondInsufficientPrivileges($validation->messages()->all());

		// $oldUser = User::where('username', Input::get('username'))->first();

		// return $oldUser;
		// if ($oldUser) {

		// 	$oldUser->password = str_random(40);
		// 	$oldUser->save();

		// 	return $oldUser;

		//} //else {
			// $user = User::create(array(
			// 	'username' => Input::get('username'),
			// 	'password' => str_random(40),
			// 	// 'word_id' => SentWordCard::orderBy('created_at', 'desc')->first()->word_id,
			// 	// 'balance' => Input::has('balance') ? Input::get('balance') : '100',
			// 	'balance' => '100'
			// 	// 'overal_standing' => 0,
			// 	// 'max_result' => 0,
			// ));

			// return $user;

			// if ($user)
			// 	return $this->respond(array(
			// 		'username' => $user['username'],
			// 		'max_result' => $user['max_result'],
			// 		'overal_standing' => $user['overal_standing'],
			// 		'balance' => $user['balance'],
			// 		'id' => $user['id'],
			// 		'password' => $user['password'],
			// 		'word_id' => $user['word_id']
			// 	));
				

			// return $this->respondServerError('Error creating user');
		// }
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$validator = Validator::make(array(
			'id' => $id
		), array(
			'id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$user = User::find($id);

		if ($user) {
			return $this->respond($this->transform($user));
		}

		return $this->respondNotFound('User not found');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$validation = $validator = Validator::make(array(
			'username' => Input::get('username'),
			'balance' => Input::get('balance')
		), array(
			'username' => 'Required|Min:3|Max:80|Alpha',
			'balance' => 'Integer'
		));


		if ($validation->passes()) {
			$user = User::find($id);

			if (!$user) {
				$this->respondNotFound('Cant find user');
			}

			$user->username = Input::get('username');
			$user->balance = Input::get('balance');
			// $user->password = Input::get('password');

			if ($user->save()) {
				return $this->respondNoContent();
			}

			return $this->respondServerError();
		}

		if ($validation->fails()) {
		 	return $this->respondInsufficientPrivileges($validation->messages()->all());
		 }
	}

	public function subscriptions($user_id) {

		$validator = Validator::make(array(
			'id' => $user_id
		), array(
			'id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$subscriptions = User::find($user_id)->subscriptions;
		$subscriptions_ar = array();
		foreach ($subscriptions as $subscription) {
			array_push($subscriptions_ar, array(
				'category_id' => $subscription['category_id'],
				'id' => $subscription['id']
			));
		}
		$result = array('user_id' => $user_id, 'subscriptions' => $subscriptions_ar);
		return $this->respond($result);
	}

	public function subscribe($user_id) {
		$validator = Validator::make(array(
			'id' => $user_id
		), array(
			'id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$category_id = Input::get('category_id');
		if (Subscription::where('category_id', '=', $category_id)->first()) {
			return $this->respondInsufficientPrivileges('Already subscribed');
		}
		if ($category_id) {
			$subscription = Subscription::create(array(
				'user_id' => $user_id,
				'category_id' => $category_id
			));
			return $this->respond(array('user_id' => $user_id, 'category_id' => $category_id, 'id' => $subscription['id']));
		}
		return $this->respondNotFound();
		
	}

	public function unsubscribe($user_id) {

		$validator = Validator::make(array(
			'id' => $user_id
		), array(
			'id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$category_id = Input::get('category_id');
		if ($category_id) {
			$subscription = Subscription::whereRaw('user_id = '.$user_id.' and category_id = '.$category_id)->first();
			if ($subscription) {
				$subscription->delete();
				return $this->respondNoContent();
			}
			return $this->respondNotFound('Subscription not found');
		}

		return $this->respondNotFound('Category not found');
	}

	public function devices($user_id) {

		$validator = Validator::make(array(
			'id' => $user_id
		), array(
			'id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$devices = User::find($user_id)->devices->toArray();
		$device_r = array();
		if ($devices) {
			foreach ($devices as $device) {
				array_push($device_r, $device['device_id']);
			}
			return $this->respond(array('user_id' => $user_id, 'devices' => $device_r));
		}
	
		return $this->respondNotFound('Devices not found');
	}

	public function add_device($user_id) {

		$validator = Validator::make(array(
			'id' => $user_id
		), array(
			'id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$user = User::find($user_id);
		$devices = $user->devices->toArray(); // Check for duplicates?
		$device_id = Input::get('device_id');

		if (!!!$device_id) {
			return $this->respondInsufficientPrivileges('Please provide a device');
		}


		if ($user) {
			if ($device_id) {
				if (in_array($device_id, $devices)) {
					return $this->respondInsufficientPrivileges('User already have this device');
				}

				Device::create(array('user_id' => $user_id, 'device_id' => $device_id));
				return $this->respondNoContent();
			}
			return $this->respondNotFound('Please give a device');
		}

		return $this->respondNotFound('User or devices not found');
	}

	public function remove_device($user_id) {

		$validator = Validator::make(array(
			'id' => $user_id
		), array(
			'id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}


		$device_id = Input::get('device_id');

		if (!$device_id) {
			return $this->respondNotFound('Please give device id');
		}

		$device = Device::whereRaw('user_id = '.$user_id.' and device_id = '.$device_id);
		if ($device) {
				if ($device->delete()) {
					return $this->respondNoContent();
				}
				
				return $this->respondNotFound('Device not found');
		}
		return $this->respondNotFound('User or devices not found');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$validator = Validator::make(array(
			'id' => $id
		), array(
			'id' => 'numeric'
		));

		if ($validator->fails()) {
			return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$user = User::find($id);
		if ($user) {
			if ($user->delete()) {
				return $this->respondNoContent();
			}
			return $this->respondServerError('Cant delete user');
		}
		return $this->respondNotFound('User not found');
	}

	private function transformCollection($users) {
		return array_map([$this, 'transform'], $users->toArray());
	}

	private function transform($user) {
		return [
			'username' => $user['username'],
			// 'password' => $user['password'],
			'max_result' => $user['max_result'],
			'overal_standing' => $user['overal_standing'],
			'balance' => $user['balance'],
			'id' => $user['id'],
			'word_id' => $user['word_id']
		];
	}

}