<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495607378.
 * Generated on 2017-05-24 15:29:38 by hachinohe
 */
class PropelMigration_1495607378
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
BEGIN;

ALTER TABLE "player_deck"

  ALTER COLUMN "head_player_item_id" SET NOT NULL,

  ALTER COLUMN "left_arm_player_item_id" SET NOT NULL,

  ALTER COLUMN "right_arm_player_item_id" SET NOT NULL,

  ALTER COLUMN "left_leg_player_item_id" SET NOT NULL,

  ALTER COLUMN "right_leg_player_item_id" SET NOT NULL;

COMMIT;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
BEGIN;

ALTER TABLE "player_deck"

  ALTER COLUMN "head_player_item_id" DROP NOT NULL,

  ALTER COLUMN "left_arm_player_item_id" DROP NOT NULL,

  ALTER COLUMN "right_arm_player_item_id" DROP NOT NULL,

  ALTER COLUMN "left_leg_player_item_id" DROP NOT NULL,

  ALTER COLUMN "right_leg_player_item_id" DROP NOT NULL;

COMMIT;
',
);
    }

}