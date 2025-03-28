<?php

namespace App\Validators;

use GuzzleHttp\Client;

class ReCaptcha
{
	public function validate($attribute, $value, $parameters, $validator)
	{
		// Check status and validate if not enabled
		if (!lib()->config->recaptcha_status) {
			return true;
		}

		$client = new Client;
		$response = $client->post(
			'https://www.google.com/recaptcha/api/siteverify',
			[
				'form_params' =>
				[
					'secret' => lib()->config->recaptcha_secret_key,
					'response' => $value
				]
			]
		);
		$body = json_decode((string)$response->getBody());
		return $body->success;
	}
}
