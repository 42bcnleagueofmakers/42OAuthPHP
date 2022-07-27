<?php

const CLIENT_ID = '';
const CLIENT_SECRET = '';
const REDIRECT_URI = '';
const AUTHORIZATION_ENDPOINT = 'https://api.intra.42.fr/oauth/authorize';
const TOKEN_ENDPOINT = 'https://api.intra.42.fr/oauth/token';

// Program to display URL of current page.
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $link = "https";
else $link = "http";
      
// Here append the common URL characters.
$link .= "://";
      
// Append the host(domain name, ip) to the URL.
$link .= $_SERVER['HTTP_HOST'];
      
// Append the requested resource location to the URL
$link .= $_SERVER['REQUEST_URI'];
      
// Print the link
//echo $link;

echo "Just testing. \nSuccesfull Logged as:\n";
$parts = parse_url($link);
parse_str($parts['query'], $query);
//echo $query['code'] . "\n";  

$fields = array('grant_type' => 'authorization_code', 'client_id' => CLIENT_ID, 'client_secret' => CLIENT_SECRET, 'code' => $query['code'], 'redirect_uri' => REDIRECT_URI);
$fields_string = http_build_query($fields);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, TOKEN_ENDPOINT);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string );
$data = curl_exec($ch);
curl_close($ch);

//echo "\nToken:";
$response = json_decode($data, true);
//var_dump($response);
$token = $response['access_token'];
//echo $token;

//echo "\nConsulta:\n";
header('Content-Type: application/json'); // Specify the type of data
$ch = curl_init(); // Initialise cURL
//$post = json_encode($post); // Encode the data array into a JSON string
$authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
curl_setopt($ch, CURLOPT_URL, 'https://api.intra.42.fr/v2/me');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
$result = curl_exec($ch); // Execute the cURL statement
curl_close($ch); // Close the cURL connection
$response = json_decode($result); // Return the received data
var_dump($response);
?>
