<?php

namespace lexishamilton\craftlibrivox\migrations;

use Craft;
use craft\db\Migration;
use lexishamilton\craftlibrivox\records\BookRecord;
use lexishamilton\craftlibrivox\records\AuthorRecord;

class Install extends Migration
{


    /**
     * @return bool
     */
    public function safeUp(): bool
    {
        //Create Book Table
        $bookTable = BookRecord::tableName();

        if(!$this->db->tableExists($bookTable)) {
            $this->createTable($bookTable, [
                'bookId' => $this->primaryKey(),
                'uid' => $this->uid(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'title' => $this->string()->notNull(),
                'description' => $this->longText(),
                'language' => $this->string(),
                'copyrightYear' => $this->integer(),
                'totalTime' => $this->string(),
                'authorId' => $this->integer()
            ]);
        }

        //Create Author Table
        $authorTable = AuthorRecord::tableName();

        if(!$this->db->tableExists($authorTable)) {
            $this->createTable($authorTable, [
                'authorId' => $this->primaryKey(),
                'uid' => $this->uid(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'firstName' => $this->string(),
                'lastName' => $this->string(),
                'dob' => $this->string(),
                'dod' => $this->string(),

            ]);
        }

        // Refresh the db schema caches
        Craft::$app->db->schema->refresh();

        return true;
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    public function safeDown(): bool
    {
        $this->dropTableIfExists(BookRecord::tableName());
        $this->dropTableIfExists(AuthorRecord::tableName());

        return true;
    }
}