<?php

namespace lexishamilton\craftlibrivox\models;

use craft\base\Model;

class AuthorModel extends Model
{

    /**
     * @var int|null Unique identifier
     */
    public $authorId;

    /**
     * @var \DateTime Date created
     */
    public $dateCreated;

    /**
     * @var \DateTime Date updated
     */
    public $dateUpdated;

    /**
     * @var string First Name
     */
    public $firstName;

    /**
     * @var string Last Name
     */
    public $lastName;

    /**
     * @var string Date of Birth
     */
    public $dob;

    /**
     * @var string Date of Death
     */
    public $dod;
}