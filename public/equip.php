<?php
require_once '../autoload.php';

use app\model\PlayerQuery;
use app\model\PlayerEquipment;

$player_id = $_COOKIE['player_id'];
if (is_null($player_id)) {
    header('Location: /index.php');
}

$player = PlayerQuery::create()->findPK($player_id);

if (!empty($_POST)) {
    if ($_POST['weapon1_player_item_id'] === $_POST['weapon2_player_item_id']) {
        header('Location: /menu.php');
    }

    if ($player->countPlayerEquipments() === 0) {
        $player_equipment = new PlayerEquipment();
        $player_equipment->setPlayer($player);
    } else {
        $player_equipment = $player->getPlayerEquipments()->getFirst();
    }

    $player_equipment->setWeapon1PlayerItemId($_POST['weapon1_player_item_id']);
    $player_equipment->setWeapon2PlayerItemId($_POST['weapon2_player_item_id']);
    $player_equipment->setHeadPlayerItemId($_POST['head_player_item_id']);
    $player_equipment->setLeftArmPlayerItemId($_POST['left_arm_player_item_id']);
    $player_equipment->setRightArmPlayerItemId($_POST['right_arm_player_item_id']);
    $player_equipment->setLeftLegPlayerItemId($_POST['left_leg_player_item_id']);
    $player_equipment->setRightLegPlayerItemId($_POST['right_leg_player_item_id']);

    $player_equipment->save();
    header('Location: /menu.php');
} else {
    $player_items = $player->getPlayerItems();
    foreach ($player_items as $player_item) {
        $item = $player_item->getItem();
        $sorted_player_items[$item->getPartId()][] = $player_item;
    }
?>
武器1と武器2を同じにはできないです。
<form method="post">
武器1:<select name="weapon1_player_item_id">
<?php
    foreach ($sorted_player_items[6] as $player_item) { // $sorted_player_items[6] の 6 ってなんやねん
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
武器2:<select name="weapon2_player_item_id">
<?php
    foreach ($sorted_player_items[6] as $player_item) {
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
頭:<select name="head_player_item_id">
<?php
    foreach ($sorted_player_items[1] as $player_item) {
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
左腕:<select name="left_arm_player_item_id">
<?php
    foreach ($sorted_player_items[2] as $player_item) {
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
右腕:<select name="right_arm_player_item_id">
<?php
    foreach ($sorted_player_items[3] as $player_item) {
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
左脚:<select name="left_leg_player_item_id">
<?php
    foreach ($sorted_player_items[4] as $player_item) {
        $item = $player_item->getItem();
        printf('<option value="%d">[%s] %s</option>', $player_item->getId(), $item->getProprium()->getName(), $item->getName());
    }
?>
</select><br>
右脚:<select name="right_leg_player_item_id">
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
