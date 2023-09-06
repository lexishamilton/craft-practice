<?php

namespace lexishamilton\craftlibrivox\records;

use craft\db\ActiveRecord;

class BookRecord extends ActiveRecord
{

    /**
     * @inheritdoc
     * @return string the table name
     */
    public static function tableName(): string {
         return '{{%lexishamilton_librivox_book}}';
    }

    public function getAuthors() {
        return $this->hasMany(AuthorRecord::class, ['authorId' => 'authorId']);
    }
}