<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495612228.
 * Generated on 2017-05-24 16:50:28 by hachinohe
 */
class PropelMigration_1495612228
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

ALTER TABLE "player_equipment" DROP CONSTRAINT "player_equipment_fk_head_player_item";

ALTER TABLE "player_equipment"

  ADD "weapon1_player_item_id" INTEGER NOT NULL,

  ADD "weapon2_player_item_id" INTEGER NOT NULL;

ALTER TABLE "player_equipment" ADD CONSTRAINT "player_equipment_fk_weapon1_player_item"
    FOREIGN KEY ("weapon1_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_equipment" ADD CONSTRAINT "player_equipment_fk_weapon2_player_item"
    FOREIGN KEY ("weapon2_player_item_id")
    REFERENCES "player_item" ("id");

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

ALTER TABLE "player_equipment" DROP CONSTRAINT "player_equipment_fk_weapon1_player_item";

ALTER TABLE "player_equipment" DROP CONSTRAINT "player_equipment_fk_weapon2_player_item";

ALTER TABLE "player_equipment"

  DROP COLUMN "weapon1_player_item_id",

  DROP COLUMN "weapon2_player_item_id";

ALTER TABLE "player_equipment" ADD CONSTRAINT "player_equipment_fk_head_player_item"
    FOREIGN KEY ("head_player_item_id")
    REFERENCES "player_item" ("id");

COMMIT;
',
);
    }

}