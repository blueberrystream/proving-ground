<?php
require_once '../autoload.php';

use app\model\PlayerQuery;

$player_id = $_COOKIE['player_id'];
if (is_null($player_id)) {
    if (isset($_POST['player_id'])) {
        setcookie('player_id', $_POST['player_id']);
        header('Location: /menu.php');
    }
?>
<form method="post">
<select name="player_id">
<?php
    $players = PlayerQuery::create()->find();
    foreach ($players as $player) {
        printf('<option value="%d">%s</option>', $player->getId(), $player->getName());
    }
?>
</select>
<input type="submit">
</form>
<?php
} else {
    header('Location: /menu.php');
}
