<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495104400.
 * Generated on 2017-05-18 19:46:40 by hachinohe
 */
class PropelMigration_1495104400
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

  ALTER COLUMN "player_item1_id" DROP NOT NULL,

  ALTER COLUMN "player_item2_id" DROP NOT NULL,

  ALTER COLUMN "player_item3_id" DROP NOT NULL,

  ALTER COLUMN "player_item4_id" DROP NOT NULL,

  ALTER COLUMN "player_item5_id" DROP NOT NULL;

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

  ALTER COLUMN "player_item1_id" SET NOT NULL,

  ALTER COLUMN "player_item2_id" SET NOT NULL,

  ALTER COLUMN "player_item3_id" SET NOT NULL,

  ALTER COLUMN "player_item4_id" SET NOT NULL,

  ALTER COLUMN "player_item5_id" SET NOT NULL;

COMMIT;
',
);
    }

}