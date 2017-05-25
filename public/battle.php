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
$player = PlayerQuery::create()->findPK($player_id);

if (isset($_POST['enemy_player_id'])) {
    $enemy_player = PlayerQuery::create()->findPK($_POST['enemy_player_id']);

    $player_points = $player->getPoints();
    $enemy_player_points = $enemy_player->getPoints();

    echo '相手のステータス↓';
    echo '<ul>';
    foreach ($enemy_player_points as $key => $point) {
        printf('<li>%s: %d</li>', $key, $point);
    }
    echo '</ul>';

    $player_deck = $player->getPlayerDecks()->getFirst();
    $enemy_player_deck = $enemy_player->getPlayerDecks()->getFirst();

    $property_names = ['First', 'Second', 'Third', 'Fourth', 'Fifth'];
    $nb_phase = 1;
    echo 'バトル経過↓';
    echo '<ul>';
    foreach ($property_names as $property_name) {
        $method_name = "getPropriumRelatedBy${property_name}PropriumId";
        $player_proprium = $player_deck->$method_name();
        $enemy_player_proprium = $enemy_player_deck->$method_name();

        echo "<li>phase${nb_phase}</li>";
        $nb_phase++;
        echo '<ul>';
        printf('<li>自分: %s vs. 相手 %s</li>', $player_proprium->getName(), $enemy_player_proprium->getName());

        // じゃんけん
        $diff = $player_proprium->getId() - $enemy_player_proprium->getId();
        switch ($diff) {
            case -1:
            case 2:
                $damage_point = $player_points['attack_point'] - $enemy_player_points['defense_point'];
                if ($damage_point < 0) {
                    $damage_point = 0;
                }
                $enemy_player_points['hit_point'] -= $damage_point;
                printf('<li>自分の攻撃！相手に%dポイントのダメージ！</li>', $damage_point);
                break;
            case -2:
            case 1:
                $damage_point = $enemy_player_points['attack_point'] - $player_points['defense_point'];
                if ($damage_point < 0) {
                    $damage_point = 0;
                }
                $player_points['hit_point'] -= $damage_point;
                printf('<li>相手の攻撃！自分に%dポイントのダメージ！</li>', $damage_point);
                break;
            default:
                $damage_point = $player_points['attack_point'] - $enemy_player_points['defense_point'];
                if ($damage_point < 0) {
                    $damage_point = 0;
                }
                $enemy_player_points['hit_point'] -= $damage_point;
                printf('<li>自分の攻撃！相手に%dポイントのダメージ！</li>', $damage_point);

                $damage_point = $enemy_player_points['attack_point'] - $player_points['defense_point'];
                if ($damage_point < 0) {
                    $damage_point = 0;
                }
                $player_points['hit_point'] -= $damage_point;
                printf('<li>相手の攻撃！自分に%dポイントのダメージ！</li>', $damage_point);
                break;
        }
        printf('<li>[現在のHP] 自分: %d / 相手: %d</li>', $player_points['hit_point'], $enemy_player_points['hit_point']);
        echo '</ul>';

        // どちらかのHPが0以下であれば即時中止
        if ($player_points['hit_point'] <= 0 || $enemy_player_points['hit_point'] <= 0) {
            break;
        }
    }
    echo '</ul>';

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

    if ($player_points['hit_point'] <= 0 && $enemy_player_points['hit_point'] <= 0
        || $player_points['hit_point'] === $enemy_player_points['hit_point']) {
        echo '引き分け<br>';
        $player_battle_log->setResult(PlayerBattleLog::RESULT_DRAW);
        $enemy_player_battle_log->setResult(PlayerBattleLog::RESULT_DRAW);
    } elseif ($player_points['hit_point'] <= 0 || $player_points['hit_point'] < $enemy_player_points['hit_point']) {
        echo '負けた...<br>';
        $player_battle_log->setResult(PlayerBattleLog::RESULT_LOSE);
        $enemy_player_battle_log->setResult(PlayerBattleLog::RESULT_WIN);
    } elseif ($enemy_player_points['hit_point'] <= 0 || $enemy_player_points['hit_point'] < $player_points['hit_point']) {
        echo '勝った！<br>';
        $player_battle_log->setResult(PlayerBattleLog::RESULT_WIN);
        $enemy_player_battle_log->setResult(PlayerBattleLog::RESULT_LOSE);
    } else {
        echo '謎<br>';
        $player_battle_log->setResult(PlayerBattleLog::RESULT_ERROR);
        $enemy_player_battle_log->setResult(PlayerBattleLog::RESULT_ERROR);
    }
    echo '<br>';

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
デッキ構築した人としかバトルできません。<br>
自分のステータス↓
<ul>
<?php
$points = $player->getPoints();
foreach ($points as $key => $point) {
    printf('<li>%s: %d</li>', $key, $point);
}
?>
</ul>

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
