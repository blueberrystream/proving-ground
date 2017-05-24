<?php
require_once '../autoload.php';

use app\model\PlayerQuery;
use app\model\ItemQuery;
use app\model\PlayerItem;

$player_id = $_COOKIE['player_id'];
if (is_null($player_id)) {
    header('Location: /index.php');
    return;
}

$player = PlayerQuery::create()->findPK($player_id);

$items = ItemQuery::create()->find();
$random = mt_rand(0, count($items) - 1);
$item = $items[$random];
$player_item = new PlayerItem();
$player_item->setPlayer($player);
$player_item->setItem($item);
$player_item->save();

header('Location: /menu.php');
