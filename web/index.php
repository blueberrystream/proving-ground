<?php
require_once '../autoload.php';

echo '<pre>';

$players = PlayerQuery::create()->find();
foreach ($players as $player) {
    echo sprintf("%d: %s\n", $player->getId(), $player->getName());
}

$items = ItemQuery::create()->find();
foreach ($items as $item) {
    echo sprintf("%d: %s [%s]\n", $item->getId(), $item->getName(), $item->getPart()->getName());
}
