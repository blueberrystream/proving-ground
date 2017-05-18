<?php
require_once '../autoload.php';

use app\model\PlayerQuery;
use app\model\PlayerDeck;

$player_id = $_COOKIE['player_id'];
if (is_null($player_id)) {
    header('Location: /index.php');
}

$player = PlayerQuery::create()->findPK($player_id);

if (isset($_POST['player_items'])) {
    $player_items = $_POST['player_items'];

    if ($player->countPlayerDecks() === 0) {
        $player_deck = new PlayerDeck();
        $player_deck->setPlayer($player);
    } else {
        $player_deck = $player->getPlayerDecks()->getFirst();
    }

    for ($i = 1; $i <= 5; $i++) {
        $method_name = "setPlayerItem${i}Id";
        $player_item = $player_items[$i - 1];
        if (isset($player_item)) {
            $player_deck->$method_name($player_item);
        } else {
            $player_deck->$method_name(null);
        }
    }

    $player_deck->save();
    header('Location: /menu.php');
} else {
?>
<form method="post">
何個でも選べるけど、上から数えて5番目までしか設定できないよ。
<ul style="list-style-type: none">
<?php
$player_items = $player->getPlayerItems();
foreach ($player_items as $player_item) {
    $item = $player_item->getItem();
    printf(
        '<li><label><input type="checkbox" name="player_items[]" value="%d">[%s][%s] %s</label></li>',
        $player_item->getId(),
        $item->getProprium()->getName(),
        $item->getPart()->getName(),
        $item->getName()
    );
}
?>
</ul>
<input type="submit">
</form>
<?php
}
