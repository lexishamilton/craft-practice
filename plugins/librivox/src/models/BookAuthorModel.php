<?php

namespace lexishamilton\craftlibrivox\models;

use Craft;
use craft\base\model;

class BookAuthorModel extends Model
{

    /**
     * @var int|null Unique identifier
     */
    public $bookAuthorId;

    /**
     * @var \DateTime Date created
     */
    public $dateCreated;

    /**
     * @var \DateTime Date updated
     */
    public $dateUpdated;

    /**
     * @var int Associated book id
     */
    public $bookId;

    /**
     * @var int Associated author id
     */
    public $authorId;

}