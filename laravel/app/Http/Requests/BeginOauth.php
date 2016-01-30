<?php namespace App\Http\Requests {


	class BeginOauth extends Request {

		public function rules() {
			return [
				'service' => 'in:microsoft'
			];
		}

		public function authorize() {
			return true;
		}

	}

}