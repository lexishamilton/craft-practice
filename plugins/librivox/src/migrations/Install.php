<?php

namespace lexishamilton\craftlibrivox\migrations;

use Craft;
use craft\db\Migration;
use lexishamilton\craftlibrivox\records\BookRecord;
use lexishamilton\craftlibrivox\records\AuthorRecord;
use lexishamilton\craftlibrivox\records\BookAuthorRecord;

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
                'totalTime' => $this->string()
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
                'dod' => $this->string()
            ]);
        }

        //Create Book Author Table to normalize the data for the most flexibility
        $bookAuthorTable = BookAuthorRecord::tableName();

        if(!$this->db->tableExists($bookAuthorTable)) {
            $this->createTable($bookAuthorTable, [
                'bookAuthorId' => $this->primaryKey(),
                'uid' => $this->uid(),
                'dateCreated' => $this->dateTime()->notNull(),
                'dateUpdated' => $this->dateTime()->notNull(),
                'bookId' => $this->integer(),
                'authorId' => $this->integer()
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
        $this->dropTableIfExists(BookAuthorRecord::tableName());

        return true;
    }
}