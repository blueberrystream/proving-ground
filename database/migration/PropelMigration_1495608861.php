<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495608861.
 * Generated on 2017-05-24 15:54:21 by hachinohe
 */
class PropelMigration_1495608861
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

DROP TABLE IF EXISTS "player_deck" CASCADE;

CREATE TABLE "player_equipment"
(
    "id" serial NOT NULL,
    "player_id" INTEGER NOT NULL,
    "head_player_item_id" INTEGER NOT NULL,
    "left_arm_player_item_id" INTEGER NOT NULL,
    "right_arm_player_item_id" INTEGER NOT NULL,
    "left_leg_player_item_id" INTEGER NOT NULL,
    "right_leg_player_item_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP NOT NULL,
    "updated_at" TIMESTAMP NOT NULL,
    PRIMARY KEY ("id")
);

ALTER TABLE "player_equipment" ADD CONSTRAINT "player_equipment_fk_97a1b7"
    FOREIGN KEY ("player_id")
    REFERENCES "player" ("id");

ALTER TABLE "player_equipment" ADD CONSTRAINT "player_equipment_fk_head_player_item"
    FOREIGN KEY ("head_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_equipment" ADD CONSTRAINT "player_equipment_fk_left_arm_player_item"
    FOREIGN KEY ("left_arm_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_equipment" ADD CONSTRAINT "player_equipment_fk_right_arm_player_item"
    FOREIGN KEY ("right_arm_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_equipment" ADD CONSTRAINT "player_equipment_fk_left_leg_player_item"
    FOREIGN KEY ("left_leg_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_equipment" ADD CONSTRAINT "player_equipment_fk_right_leg_player_item"
    FOREIGN KEY ("right_leg_player_item_id")
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

DROP TABLE IF EXISTS "player_equipment" CASCADE;

CREATE TABLE "player_deck"
(
    "id" serial NOT NULL,
    "player_id" INTEGER NOT NULL,
    "head_player_item_id" INTEGER NOT NULL,
    "left_arm_player_item_id" INTEGER NOT NULL,
    "right_arm_player_item_id" INTEGER NOT NULL,
    "left_leg_player_item_id" INTEGER NOT NULL,
    "right_leg_player_item_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP NOT NULL,
    "updated_at" TIMESTAMP NOT NULL,
    PRIMARY KEY ("id")
);

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_97a1b7"
    FOREIGN KEY ("player_id")
    REFERENCES "player" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_head_player_item"
    FOREIGN KEY ("head_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_left_arm_player_item"
    FOREIGN KEY ("left_arm_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_left_leg_player_item"
    FOREIGN KEY ("left_leg_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_right_arm_player_item"
    FOREIGN KEY ("right_arm_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_right_leg_player_item"
    FOREIGN KEY ("right_leg_player_item_id")
    REFERENCES "player_item" ("id");

COMMIT;
',
);
    }

}