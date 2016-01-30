<?php namespace App\Http\Requests {


	class CompleteOauth extends Request {

		public function rules() {
			return [
				'code' => 'required',
				'state' => 'required',
			];
		}

		public function authorize() {
			return true;
		}

	}

}