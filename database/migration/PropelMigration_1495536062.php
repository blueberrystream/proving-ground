<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495536062.
 * Generated on 2017-05-23 19:41:02 by hachinohe
 */
class PropelMigration_1495536062
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

ALTER TABLE "item"

  ADD "hit_point" INTEGER DEFAULT 0 NOT NULL,

  ADD "attack_point" INTEGER DEFAULT 0 NOT NULL,

  ADD "defense_point" INTEGER DEFAULT 0 NOT NULL;

ALTER TABLE "player_deck" DROP CONSTRAINT "player_deck_fk_player_item1";

ALTER TABLE "player_deck" DROP CONSTRAINT "player_deck_fk_player_item2";

ALTER TABLE "player_deck" DROP CONSTRAINT "player_deck_fk_player_item3";

ALTER TABLE "player_deck" DROP CONSTRAINT "player_deck_fk_player_item4";

ALTER TABLE "player_deck" DROP CONSTRAINT "player_deck_fk_player_item5";

ALTER TABLE "player_deck" RENAME COLUMN "player_item1_id" TO "head_player_item_id";


ALTER TABLE "player_deck" RENAME COLUMN "player_item2_id" TO "left_arm_player_item_id";


ALTER TABLE "player_deck" RENAME COLUMN "player_item3_id" TO "right_arm_player_item_id";


ALTER TABLE "player_deck" RENAME COLUMN "player_item4_id" TO "left_leg_player_item_id";


ALTER TABLE "player_deck" RENAME COLUMN "player_item5_id" TO "right_leg_player_item_id";

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_head_player_item"
    FOREIGN KEY ("head_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_left_arm_player_item"
    FOREIGN KEY ("left_arm_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_right_arm_player_item"
    FOREIGN KEY ("right_arm_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_left_leg_player_item"
    FOREIGN KEY ("left_leg_player_item_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_right_leg_player_item"
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

ALTER TABLE "item"

  DROP COLUMN "hit_point",

  DROP COLUMN "attack_point",

  DROP COLUMN "defense_point";

ALTER TABLE "player_deck" DROP CONSTRAINT "player_deck_fk_head_player_item";

ALTER TABLE "player_deck" DROP CONSTRAINT "player_deck_fk_left_arm_player_item";

ALTER TABLE "player_deck" DROP CONSTRAINT "player_deck_fk_right_arm_player_item";

ALTER TABLE "player_deck" DROP CONSTRAINT "player_deck_fk_left_leg_player_item";

ALTER TABLE "player_deck" DROP CONSTRAINT "player_deck_fk_right_leg_player_item";

ALTER TABLE "player_deck" RENAME COLUMN "head_player_item_id" TO "player_item1_id";


ALTER TABLE "player_deck" RENAME COLUMN "left_arm_player_item_id" TO "player_item2_id";


ALTER TABLE "player_deck" RENAME COLUMN "right_arm_player_item_id" TO "player_item3_id";


ALTER TABLE "player_deck" RENAME COLUMN "left_leg_player_item_id" TO "player_item4_id";


ALTER TABLE "player_deck" RENAME COLUMN "right_leg_player_item_id" TO "player_item5_id";

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_player_item1"
    FOREIGN KEY ("player_item1_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_player_item2"
    FOREIGN KEY ("player_item2_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_player_item3"
    FOREIGN KEY ("player_item3_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_player_item4"
    FOREIGN KEY ("player_item4_id")
    REFERENCES "player_item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_player_item5"
    FOREIGN KEY ("player_item5_id")
    REFERENCES "player_item" ("id");

COMMIT;
',
);
    }

}