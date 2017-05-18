<?php
require_once '../autoload.php';

use app\model\PlayerQuery;

$player_id = $_COOKIE['player_id'];
if (is_null($player_id)) {
    header('Location: /index.php');
}

if (isset($_POST['"enemy_player_id"'])) {
    $player = PlayerQuery::create()->findPK($player_id);
    $enemy_player = PlayerQuery::create()->findPK($_POST['"enemy_player_id"']);

    $player_deck = $player->getPlayerDecks()->getFirst();
    $enemy_player_deck = $enemy_player->getPlayerDecks()->getFirst();

    $win = 0;
    for ($i = 1; $i <= 5; $i++) {
        $method_name = "getPlayerItemRelatedByPlayerItem${i}Id";
        $player_item = $player_deck->$method_name();
        $enemy_player_item = $enemy_player_deck->$method_name();

        $player_raw_item = $player_item->getItem();
        $enemy_player_raw_item = $enemy_player_item->getItem();

        // じゃんけん
        $diff = $player_raw_item->getPropriumId() - $enemy_player_raw_item->getPropriumId();
        switch ($diff) {
            case -1:
            case 2:
                $win++;
                break;
            case -2:
            case 1:
                $win--;
                break;
            default:
                break;
        }
    }

    if ($win < 0) {
        echo 'lose';
    } elseif (0 < $win) {
        echo 'win';
    } else {
        echo 'draw';
    }
} else {
?>
デッキ構築した人としかバトルできません。
<form method="post">
<select name="enemy_player_id">
<?php
$players = PlayerQuery::create()->find();
foreach ($players as $player) {
    $player_deck = $player->getPlayerDecks()->getFirst();
    $is_battlable = !is_null($player_deck);
    if ($is_battlable) {
        for ($i = 1; $i <= 5; $i++) {
            $method_name = "getPlayerItem${i}Id";
            $player_item_id = $player_deck->$method_name();
            $is_battlable = $is_battlable && !is_null($player_item_id);
        }
    }

    if ($is_battlable) {
        printf('<option value="%d">%s</option>', $player->getId(), $player->getName());
    }
}
?>
</select>
<input type="submit">
</form>
<?php
}
