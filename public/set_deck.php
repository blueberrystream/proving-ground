<?php
require_once '../autoload.php';

use app\model\PlayerQuery;
use app\model\PlayerDeck;

$player_id = $_COOKIE['player_id'];
if (is_null($player_id)) {
    header('Location: /index.php');
}

$player = PlayerQuery::create()->findPK($player_id);

if (!empty($_POST)) {
    if ($player->countPlayerDecks() === 0) {
        $player_deck = new PlayerDeck();
        $player_deck->setPlayer($player);
    } else {
        $player_deck = $player->getPlayerDecks()->getFirst();
    }

    $player_deck->setHeadPlayerItemId($_POST['head_player_item_id']);
    $player_deck->setLeftArmPlayerItemId($_POST['left_arm_player_item_id']);
    $player_deck->setRightArmPlayerItemId($_POST['right_arm_player_item_id']);
    $player_deck->setLeftLegPlayerItemId($_POST['left_leg_player_item_id']);
    $player_deck->setRightLegPlayerItemId($_POST['right_leg_player_item_id']);

    $player_deck->save();
    header('Location: /menu.php');
} else {
    $player_items = $player->getPlayerItems();
    foreach ($player_items as $player_item) {
        $item = $player_item->getItem();
        $sorted_player_items[$item->getPartId()][] = $player_item;
    }
?>
<form method="post">
頭:<select name="head_player_item_id">
<option value="null">装備なし</option>
<?php
    foreach ($sorted_player_items[1] as $player_item) { // $sorted_player_items[1] の 1 ってなんやねん
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
左腕:<select name="left_arm_player_item_id">
<option value="null">装備なし</option>
<?php
    foreach ($sorted_player_items[2] as $player_item) {
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
右腕:<select name="right_arm_player_item_id">
<option value="null">装備なし</option>
<?php
    foreach ($sorted_player_items[3] as $player_item) {
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
左脚:<select name="left_leg_player_item_id">
<option value="null">装備なし</option>
<?php
    foreach ($sorted_player_items[4] as $player_item) {
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
右脚:<select name="right_leg_player_item_id">
<option value="null">装備なし</option>
<?php
    foreach ($sorted_player_items[5] as $player_item) {
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
<input type="submit">
</form>
<?php
}
