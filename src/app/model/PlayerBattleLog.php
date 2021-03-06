<?php

namespace app\model;

use app\model\Base\PlayerBattleLog as BasePlayerBattleLog;

/**
 * Skeleton subclass for representing a row from the 'player_battle_log' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class PlayerBattleLog extends BasePlayerBattleLog
{
    const RESULT_ERROR = -2;
    const RESULT_LOSE = -1;
    const RESULT_DRAW = 0;
    const RESULT_WIN = 1;
}
