<?php

function validateJwt($jwt, $secret) {

    $tokenParts = explode('.', $jwt);
    if (count($tokenParts) !== 3) {
        return false; // JWT should have three parts
    }

    list($header64, $payload64, $signature) = $tokenParts;

    // Decode the parts
    $header = json_decode(base64_decode($header64), true);
    $payload = json_decode(base64_decode($payload64), true);


    // Check if decoding was successful
    if (!$header || !$payload) {
        echo "The header received does not have a JWT Token header format.\n";
        return false;
    }

    // Verify the signature
    $computedSignature = hash_hmac('sha256', $header64 . '.' . $payload64, $secret, true);
    $computedSignatureBase64 = base64_encode($computedSignature);
    $computedSignatureBase64 = str_replace('=', '', $computedSignatureBase64);

    if ($computedSignatureBase64 !== $signature) {
        return false;
    }

    if (isset($payload['exp']) && $payload['exp'] < time()) {
        echo "The token has expired";
	return false; // Token has expired
    }

    // Token is valid
    return true;
}

$jwt_secret="raspberrypi";

$headers = getallheaders();

if (isset($headers['Authorization'])) {
    $authHeader = $headers['Authorization'];
    $authHeaders = explode(" ", $authHeader);
    $authHeader=$authHeaders[1];

    if (!validateJwt($authHeader, $jwt_secret)){
        http_response_code(401);
        exit("Unauthorized.");
    }
} else {
    exit("Authorization header not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $rawData = file_get_contents("php://input");
    $jsonData = json_decode($rawData, true);

    if ($jsonData && isset($jsonData['data'])){
        $dataArray = htmlspecialchars($jsonData['data']);
        $data = explode("," , $dataArray);

        include("storeData.php");
    }else{
        http_response_code(400);
        echo "Invalid data.";
    }

}else{
    http_response_code(405);
    echo "The system needs a POST request.";
}

?>
