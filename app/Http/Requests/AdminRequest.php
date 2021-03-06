<?php

namespace App\Http\Requests;

use Gate;
use App\Http\Requests\Request;

class AdminRequest extends Request {
	public function authorize() {
		return Gate::allows('permission', 'admin');
	}
	public function rules() {
		return [
			//
		];
	}
}
