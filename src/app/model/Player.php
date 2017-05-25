<?php

namespace app\model;

use app\model\Base\Player as BasePlayer;

/**
 * Skeleton subclass for representing a row from the 'player' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Player extends BasePlayer
{
    public function getPoints() {
        $points = [
            'hit_point' => 0,
            'attack_point' => 0,
            'defense_point' => 0,
        ];

        if ($this->countPlayerEquipments() === 0) {
            return $points;
        }

        $player_equipment = $this->getPlayerEquipments()->getFirst();
        $item_property_names = ['Weapon1', 'Weapon2', 'Head', 'LeftArm', 'RightArm', 'LeftLeg', 'RightLeg'];
        foreach ($item_property_names as $item_property_name) {
            $method_name = "getPlayerItemRelatedBy${item_property_name}PlayerItemId";
            $items[] = $player_equipment->$method_name()->getItem();
        }

        foreach ($items as $item) {
            $points['hit_point'] += $item->getHitPoint();
            $points['attack_point'] += $item->getAttackPoint();
            $points['defense_point'] += $item->getDefensePoint();
        }

        return $points;
    }
}
