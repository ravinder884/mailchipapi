<?php
$email = "test@exampple.com";
$fname = "Steve";
$lname = "Smith";

  $apiKey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-us4';
		$listID = 'xxxxxxxxxxx';
    
     // MailChimp API URL
			$memberID = md5(strtolower($email));
			$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
			$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/' . $memberID;

     // member information
			$json = json_encode([
				'email_address' => $email,
				'status'        => 'subscribed',
				 'merge_fields' => array(
                   "FNAME"=> $fname,
                   "LNAME"=> $lname
                  )
			]);
     
    // send a HTTP POST request with curl
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			$result = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

        // store the status message based on response code
			if ($httpCode == 200) {
				$response['msg'] = '<p style="color: #34A853">You have successfully subscribed for Newsletter.</p>';
			} else {
				switch ($httpCode) {
					case 214:
					$msg = 'You are already subscribed.';
					break;
					default:
					$msg = 'Some problem occurred, please try again.';
					break;
				}
				$response['msg'] = '<p style="color: #EA4335">'.$msg.'</p>';
			}
     
