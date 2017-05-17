<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495095523.
 * Generated on 2017-05-18 08:18:43 by hachinohe
 */
class PropelMigration_1495095523
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

CREATE TABLE "player"
(
    "id" serial NOT NULL,
    "name" VARCHAR(255) NOT NULL,
    PRIMARY KEY ("id")
);

CREATE TABLE "part"
(
    "id" serial NOT NULL,
    "name" VARCHAR(255) NOT NULL,
    PRIMARY KEY ("id")
);

CREATE TABLE "proprium"
(
    "id" serial NOT NULL,
    "name" VARCHAR(255) NOT NULL,
    PRIMARY KEY ("id")
);

CREATE TABLE "item"
(
    "id" serial NOT NULL,
    "name" VARCHAR(255) NOT NULL,
    "proprium_id" INTEGER NOT NULL,
    "part_id" INTEGER NOT NULL,
    PRIMARY KEY ("id")
);

CREATE TABLE "player_item"
(
    "id" serial NOT NULL,
    "player_id" INTEGER NOT NULL,
    "item_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP NOT NULL,
    "updated_at" TIMESTAMP NOT NULL,
    PRIMARY KEY ("id")
);

CREATE TABLE "player_deck"
(
    "id" serial NOT NULL,
    "player_id" INTEGER NOT NULL,
    "player_item1_id" INTEGER NOT NULL,
    "player_item2_id" INTEGER NOT NULL,
    "player_item3_id" INTEGER NOT NULL,
    "player_item4_id" INTEGER NOT NULL,
    "player_item5_id" INTEGER NOT NULL,
    "created_at" TIMESTAMP NOT NULL,
    "updated_at" TIMESTAMP NOT NULL,
    PRIMARY KEY ("id")
);

CREATE TABLE "player_battle_log"
(
    "id" serial NOT NULL,
    "player_id" INTEGER NOT NULL,
    "enemy_player_id" INTEGER NOT NULL,
    "win" BOOLEAN NOT NULL,
    "created_at" TIMESTAMP NOT NULL,
    "updated_at" TIMESTAMP NOT NULL,
    PRIMARY KEY ("id")
);

ALTER TABLE "item" ADD CONSTRAINT "item_fk_9243eb"
    FOREIGN KEY ("part_id")
    REFERENCES "part" ("id");

ALTER TABLE "item" ADD CONSTRAINT "item_fk_f46ced"
    FOREIGN KEY ("proprium_id")
    REFERENCES "proprium" ("id");

ALTER TABLE "player_item" ADD CONSTRAINT "player_item_fk_97a1b7"
    FOREIGN KEY ("player_id")
    REFERENCES "player" ("id");

ALTER TABLE "player_item" ADD CONSTRAINT "player_item_fk_5cf635"
    FOREIGN KEY ("item_id")
    REFERENCES "item" ("id");

ALTER TABLE "player_deck" ADD CONSTRAINT "player_deck_fk_97a1b7"
    FOREIGN KEY ("player_id")
    REFERENCES "player" ("id");

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

ALTER TABLE "player_battle_log" ADD CONSTRAINT "player_battle_log_fk_player"
    FOREIGN KEY ("player_id")
    REFERENCES "player" ("id");

ALTER TABLE "player_battle_log" ADD CONSTRAINT "player_battle_log_fk_enemy_player"
    FOREIGN KEY ("enemy_player_id")
    REFERENCES "player" ("id");

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

DROP TABLE IF EXISTS "player" CASCADE;

DROP TABLE IF EXISTS "part" CASCADE;

DROP TABLE IF EXISTS "proprium" CASCADE;

DROP TABLE IF EXISTS "item" CASCADE;

DROP TABLE IF EXISTS "player_item" CASCADE;

DROP TABLE IF EXISTS "player_deck" CASCADE;

DROP TABLE IF EXISTS "player_battle_log" CASCADE;

COMMIT;
',
);
    }

}