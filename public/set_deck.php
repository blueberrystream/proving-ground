<?php
require_once '../autoload.php';

use app\model\PlayerQuery;
use app\model\PropriumQuery;
use app\model\PlayerDeck;

$player_id = $_COOKIE['player_id'];
if (is_null($player_id)) {
    header('Location: /index.php');
    return;
}

$player = PlayerQuery::create()->findPK($player_id);

if (!empty($_POST)) {
    $orders = $_POST['orders'];
    foreach ($orders as $proprium_id => $order) {
        foreach ($order as $o) {
            $deck[$o] = $proprium_id;
        }
    }

    if ($player->countPlayerDecks() === 0) {
        $player_deck = new PlayerDeck();
        $player_deck->setPlayer($player);
    } else {
        $player_deck = $player->getPlayerDecks()->getFirst();
    }

    $player_deck->setFirstPropriumId($deck['1']);
    $player_deck->setSecondPropriumId($deck['2']);
    $player_deck->setThirdPropriumId($deck['3']);
    $player_deck->setFourthPropriumId($deck['4']);
    $player_deck->setFifthPropriumId($deck['5']);

    $player_deck->save();

    header('Location: /menu.php');
    return;
}

if ($player->countPlayerEquipments() === 0) {
    header('Location: /menu.php');
    return;
} else {
    $player_equipment = $player->getPlayerEquipments()->getFirst();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByWeapon1PlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByWeapon2PlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByHeadPlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByLeftArmPlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByRightArmPlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByLeftLegPlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByRightLegPlayerItemId();

    $player_propriums = PropriumQuery::create()->find(); // デフォルトでGCPを1つずつ
    foreach ($player_equipment_items as $player_equipment_item) {
        $item = $player_equipment_item->getItem();
        $player_propriums[] = $item->getProprium();
    }
?>
<form method="post">
デッキにセットしたい順に1から5の数字を入力してね。
<ul>
<?php
    foreach ($player_propriums as $player_proprium) {
        printf('<li>%s<input type="number" name="orders[%d][]"></li>', $player_proprium->getName(), $player_proprium->getId());
    }
}
?>
<input type="submit">
</form>
