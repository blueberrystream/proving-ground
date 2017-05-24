<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495609177.
 * Generated on 2017-05-24 15:59:37 by hachinohe
 */
class PropelMigration_1495609177
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

CREATE TABLE "player_deck"
(
    "id" serial NOT NULL,
    "player_id" INTEGER NOT NULL,
    "first_proprium_id" INTEGER NOT NULL,
    "second_proprium_id" INTEGER NOT NULL,
    "third_proprium_id" INTEGER NOT NULL,
    "fourth_proprium_id" INTEGER NOT NULL,
    "fifth_proprium_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP NOT NULL,
    "updated_at" TIMESTAMP NOT NULL,
    PRIMARY KEY ("id")
);

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_97a1b7"
    FOREIGN KEY ("player_id")
    REFERENCES "player" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_first_proprium"
    FOREIGN KEY ("first_proprium_id")
    REFERENCES "proprium" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_second_proprium"
    FOREIGN KEY ("second_proprium_id")
    REFERENCES "proprium" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_third_proprium"
    FOREIGN KEY ("third_proprium_id")
    REFERENCES "proprium" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_fourth_proprium"
    FOREIGN KEY ("fourth_proprium_id")
    REFERENCES "proprium" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_fifth_proprium"
    FOREIGN KEY ("fifth_proprium_id")
    REFERENCES "proprium" ("id");

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

DROP TABLE IF EXISTS "player_deck" CASCADE;

COMMIT;
',
);
    }

}