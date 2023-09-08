<?php

namespace lexishamilton\craftlibrivox\models;

use Craft;
use craft\base\model;

class BookModel extends Model
{

    /**
     * @var int|null Unique identifier
     */
    public $bookId;

    /**
     * @var \DateTime Date created
     */
    public $dateCreated;

    /**
     * @var \DateTime Date updated
     */
    public $dateUpdated;

    /**
     * @var string Title
     */
    public $title;

    /**
     * @var string|null Description
     */
    public $description;

    /**
     * @var string|null Language
     */
    public $language;

    /**
     * @var int|null Copyright year
     */
    public $copyrightYear;

    /**
     * @var string Total time
     */
    public $totalTime;



}