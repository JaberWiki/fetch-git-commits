<?php

$token = 'your_access_token';
$username = 'username'; // GitHub username

$url = "https://api.github.com/users/$username/repos";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: token ' . $token,
    'User-Agent: My-App'
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);

if (empty($data)) {
    echo "No repositories found.";
} else {
    echo "Repository list: \n";
    foreach ($data as $repo) {
        echo $repo['name'] . "\n";
    }
}