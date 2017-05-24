<?php
require_once '../autoload.php';

use app\model\PlayerQuery;
use app\model\PlayerBattleLog;

$player_id = $_COOKIE['player_id'];
if (is_null($player_id)) {
    header('Location: /index.php');
    return;
}

$player = PlayerQuery::create()->findPK($player_id);
echo $player->getName();
?>
<h2>menu</h2>
<ol>
<?php
$menu_items = ['get_item', 'equip', 'set_deck', 'battle', 'logout'];
foreach ($menu_items as $menu_item) {
    printf('<li><a href="/%s.php">%s</a></li>', $menu_item, $menu_item);
}
?>
</ol>

<h2>equipment</h2>
<ul>
<?php
if ($player->countPlayerEquipments() === 0) {
    echo '<li>not set</li>';
} else {
    $player_equipment = $player->getPlayerEquipments()->getFirst();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByWeapon1PlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByWeapon2PlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByHeadPlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByLeftArmPlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByRightArmPlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByLeftLegPlayerItemId();
    $player_equipment_items[] = $player_equipment->getPlayerItemRelatedByRightLegPlayerItemId();
    foreach ($player_equipment_items as $player_item) {
        $item = $player_item->getItem();
        printf('<li>[%s][%s] %s</li>', $item->getPart()->getName(), $item->getProprium()->getName(), $item->getName());
    }
}
?>
</ul>

<h2>items</h2>
<ul>
<?php
$player_items = $player->getPlayerItems();
if ($player->countPlayerItems() === 0) {
    echo '<li>no item</li>';
} else {
    foreach ($player_items as $player_item) {
        $item = $player_item->getItem();
        printf('<li>[%s][%s] %s</li>', $item->getPart()->getName(), $item->getProprium()->getName(), $item->getName());
    }
}
?>
</ul>

<h2>deck</h2>
<ol>
<?php
    if ($player->countPlayerDecks() === 0) {
        echo '<li>not set</li>';
    } else {
        $player_deck = $player->getPlayerDecks()->getFirst();

        $player_propriums[] = $player_deck->getPropriumRelatedByFirstPropriumId();
        $player_propriums[] = $player_deck->getPropriumRelatedBySecondPropriumId();
        $player_propriums[] = $player_deck->getPropriumRelatedByThirdPropriumId();
        $player_propriums[] = $player_deck->getPropriumRelatedByFourthPropriumId();
        $player_propriums[] = $player_deck->getPropriumRelatedByFifthPropriumId();

        foreach ($player_propriums as $player_proprium) {
            printf('<li>%s</li>', $player_proprium->getName());
        }
    }
?>
</ol>

<h2>battle_logs</h2>
<ul>
<?php
$player_battle_logs = $player->getPlayerBattleLogsRelatedByPlayerId();
if ($player->countPlayerBattleLogsRelatedByPlayerId() === 0) {
    echo '<li>no battle log</li>';
} else {
    foreach ($player_battle_logs as $player_battle_log) {
        $enemy_player = PlayerQuery::create()->findPK($player_battle_log->getEnemyPlayerId());
        echo '<li>';
        echo $enemy_player->getName() . 'に';
        echo $player_battle_log->getChallenged() ? '挑んで' : '挑まれて';
        switch ($player_battle_log->getResult()) {
            case PlayerBattleLog::RESULT_LOSE:
                echo '負けた...';
                break;
            case PlayerBattleLog::RESULT_DRAW:
                echo '引き分け';
                break;
            case PlayerBattleLog::RESULT_WIN:
                echo '勝った！';
                break;
            default:
                break;

        }
        printf('(%s)', $player_battle_log->getCreatedAt()->format('Y/m/d H:i:s'));
        echo '</li>';
    }
}
?>
</ul>
