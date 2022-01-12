<?php
ini_set('display_errors', 1);
require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

$nft_connected = false;
if (\Delight\Cookie\Cookie::exists('nft_connected')) {
    $nft_connected = true;
}
?>

<html>
<head>
</head>
<body>
<?php

use GuzzleHttp\Client;

if ($_GET["action"] == "verify") : ?>
hi there! verify pls
<?php else : 

if (!$nft_connected) {
    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'https://app.clubcard.dev/api/v1/',
    ]);

    $response = $client->post('access_intents.json', [
        'json' => [
            'description' => 'New access intent!',
            'profile_name' => 'From PHP',
            'return_url' => $_ENV['APP_URL'] . "?action=verify",
            'access_list' => []
        ]
    ]);

    $access_intent_body_stream = $response->getBody();
    $access_intent_body = (string) $access_intent_body_stream;
    $access_intent = json_decode($access_intent_body);

    \Delight\Cookie\Cookie::setcookie('clubcard_access_intent', $access_intent->id)

?>
    <script>
        window.location = "<?= $access_intent->redirect_url ?>"
    </script>
<?php
} else {
}
?>
</body>
</html>
<?php endif; ?>