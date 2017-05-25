<?php
require_once '../autoload.php';

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;

use app\model\PlayerQuery;
use app\model\PlayerBattleLog;
use app\model\Map\PlayerBattleLogTableMap;

$player_id = $_COOKIE['player_id'];
if (is_null($player_id)) {
    header('Location: /index.php');
    return;
}

if (isset($_POST['enemy_player_id'])) {
    $player = PlayerQuery::create()->findPK($player_id);
    $enemy_player = PlayerQuery::create()->findPK($_POST['enemy_player_id']);

    $player_points = $player->getPoints();
    $enemy_player_points = $enemy_player->getPoints();

    echo '<pre>';
    echo 'before battle<br>';
    echo 'player_points: ';
    var_dump($player_points);
    echo 'enemy_player_points: ';
    var_dump($enemy_player_points);
    echo '</pre>';

    $player_deck = $player->getPlayerDecks()->getFirst();
    $enemy_player_deck = $enemy_player->getPlayerDecks()->getFirst();

    $property_names = ['First', 'Second', 'Third', 'Fourth', 'Fifth'];
    foreach ($property_names as $property_name) {
        $method_name = "get${property_name}PropriumId";
        $player_proprium_id = $player_deck->$method_name();
        $enemy_player_proprium_id = $enemy_player_deck->$method_name();

        // じゃんけん
        $diff = $player_proprium_id - $enemy_player_proprium_id;
        switch ($diff) {
            case -1:
            case 2:
                $damage_point = $player_points['attack_point'] - $enemy_player_points['defense_point'];
                if (0 < $damage_point) {
                    $enemy_player_points['hit_point'] -= $damage_point;
                }
                break;
            case -2:
            case 1:
                $damage_point = $enemy_player_points['attack_point'] - $player_points['defense_point'];
                if (0 < $damage_point) {
                    $player_points['hit_point'] -= $damage_point;
                }
                break;
            default:
                $damage_point = $player_points['attack_point'] - $enemy_player_points['defense_point'];
                if (0 < $damage_point) {
                    $enemy_player_points['hit_point'] -= round($damage_point * 0.5);
                }
                $damage_point = $enemy_player_points['attack_point'] - $player_points['defense_point'];
                if (0 < $damage_point) {
                    $player_points['hit_point'] -= round($damage_point * 0.5);
                }
                break;
        }

        // どちらかのHPが0以下であれば即時中止
        if ($player_points['hit_point'] <= 0 || $enemy_player_points['hit_point'] <= 0) {
            break;
        }
    }

    echo '<pre>';
    echo 'after battle<br>';
    echo 'player_points: ';
    var_dump($player_points);
    echo 'enemy_player_points: ';
    var_dump($enemy_player_points);
    echo '</pre>';

    $player_battle_log = new PlayerBattleLog();
    $player_battle_log->fromArray([
        'player_id' => $player->getId(),
        'enemy_player_id' => $enemy_player->getId(),
        'challenged' => true,
    ], PlayerBattleLogTableMap::TYPE_FIELDNAME);
    $enemy_player_battle_log = new PlayerBattleLog();
    $enemy_player_battle_log->fromArray([
        'player_id' => $enemy_player->getId(),
        'enemy_player_id' => $player->getId(),
        'challenged' => false,
    ], PlayerBattleLogTableMap::TYPE_FIELDNAME);

    if ($player_points['hit_point'] <= 0 || $player_points['hit_point'] < $enemy_player_points['hit_point']) {
        echo '負けた...<br>';
        $player_battle_log->setResult(PlayerBattleLog::RESULT_LOSE);
        $enemy_player_battle_log->setResult(PlayerBattleLog::RESULT_WIN);
    } elseif ($enemy_player_points['hit_point'] <= 0 || $enemy_player_points['hit_point'] < $player_points['hit_point']) {
        echo '勝った！<br>';
        $player_battle_log->setResult(PlayerBattleLog::RESULT_WIN);
        $enemy_player_battle_log->setResult(PlayerBattleLog::RESULT_LOSE);
    } else {
        echo '引き分け<br>';
        $player_battle_log->setResult(PlayerBattleLog::RESULT_DRAW);
        $enemy_player_battle_log->setResult(PlayerBattleLog::RESULT_DRAW);
    }

    $connection = Propel::getWriteConnection(PlayerBattleLogTableMap::DATABASE_NAME);
    try {
        $player_battle_log->save();
        $enemy_player_battle_log->save();
        $connection->commit();
    } catch (\Exception $e) {
        $connection->rollback();
        throw $e;
    }
}
?>
デッキ構築した人としかバトルできません。
<form method="post">
<select name="enemy_player_id">
<?php
$players = PlayerQuery::create()->filterById($player_id, Criteria::NOT_EQUAL)->find();
foreach ($players as $player) {
    $player_deck = $player->getPlayerDecks()->getFirst();
    $is_battlable = !is_null($player_deck);

    if ($is_battlable) {
        printf('<option value="%d">%s</option>', $player->getId(), $player->getName());
    }
}
?>
</select>
<input type="submit">
</form>
<a href="/menu.php">menu</a>
