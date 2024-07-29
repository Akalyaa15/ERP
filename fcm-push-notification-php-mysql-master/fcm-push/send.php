<?php 
	define('SERVER_API_KEY', 'AAAAYnCllgg:APA91bE1g5XmyoabPSIosUVaFtOsMWiu18B3-iqLL1yRH3EQz3n3untEwnVU94L4DkKxo_GfyA9KPdwSUh1BISDaJJvaSjLJ7AnT3PXZpqba4dV8LEPwYR6lGH8xcsKHP6P9I0s0yxD8');

	require 'DbConnect.php';
	$db = new DbConnect;
	$conn = $db->connect();
	$stmt = $conn->prepare('SELECT * FROM tokens');
	$stmt->execute();
	$tokens = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach ($tokens as $token) {
		$registrationIds[] = $token['token'];
	}

	// $tokens = ['cCLA1_8Inic:APA91bGhuCksjWEETYWVOh04scsZInxdWmXekEr5F9-1zJuTDZDw3It_tNmpA__PmoxDTISZzplD_ciXvsuw2pMtYSzdfIUAUfcTLnghvJS0CVkYW9sVx2HnF1rqnxsFgSdYmcXpHKLs'];
	
	$header = [
		'Authorization: Key=' . SERVER_API_KEY,
		'Content-Type: Application/json'
	];

	$msg = [
		'title' => 'Gems manager',
		'body' => 'Clockout before 11:30pm',
		'icon' => 'https://www.gemicates.com/irdb_images/gem.png',
		'image' => 'https://erpdemo.gemicates.com/icon.png',
	];

	$payload = [
		'registration_ids' 	=> $registrationIds,
		'data'				=> $msg
	];

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => json_encode( $payload ),
	  CURLOPT_HTTPHEADER => $header
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	}
 ?>