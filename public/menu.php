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
<ol>
<?php
$menu_items = ['get_item', 'set_deck', 'battle', 'battle_log', 'logout'];
foreach ($menu_items as $menu_item) {
    printf('<li><a href="/%s.php">%s</a></li>', $menu_item, $menu_item);
}
?>
</ol>

<ul>
<?php
$player_items = $player->getPlayerItems();
foreach ($player_items as $player_item) {
    $item = $player_item->getItem();
    printf('<li>[%s][%s] %s</li>', $item->getProprium()->getName(), $item->getPart()->getName(), $item->getName());
}
?>
</ul>
