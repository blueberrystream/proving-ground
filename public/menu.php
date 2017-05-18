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
<h2>menu</h2>
<ol>
<?php
$menu_items = ['get_item', 'set_deck', 'battle', 'battle_log', 'logout'];
foreach ($menu_items as $menu_item) {
    printf('<li><a href="/%s.php">%s</a></li>', $menu_item, $menu_item);
}
?>
</ol>

<h2>deck</h2>
<ul>
<?php
if ($player->countPlayerDecks() === 0) {
    echo '<li>not set</li>';
} else {
    $player_deck = $player->getPlayerDecks()->getFirst();
    for ($i = 1; $i <= 5; $i++) {
        $method_name = "getPlayerItemRelatedByPlayerItem${i}Id";
        $player_item = $player_deck->$method_name();
        if (is_null($player_item)) {
            echo '<li>not set</li>';
        } else {
            $item = $player_item->getItem();
            printf('<li>[%s][%s] %s</li>', $item->getProprium()->getName(), $item->getPart()->getName(), $item->getName());
        }
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
        printf('<li>[%s][%s] %s</li>', $item->getProprium()->getName(), $item->getPart()->getName(), $item->getName());
    }
}
?>
</ul>
