<?php
require_once '../autoload.php';

use app\model\PlayerQuery;

$player_id = $_COOKIE['player_id'];
if (is_null($player_id)) {
    header('Location: /index.php');
}

$player = PlayerQuery::create()->findPK($player_id);
echo $player->getName();
?>

