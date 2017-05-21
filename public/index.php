<?php
require_once '../autoload.php';

use app\model\PlayerQuery;

if (isset($_COOKIE['player_id'])) {
    $player_id = $_COOKIE['player_id'];
}
if (isset($_POST['player_id'])) {
    $player_id = $_POST['player_id'];
    setcookie('player_id', $player_id);
}
if (isset($player_id)) {
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
