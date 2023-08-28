<?php

namespace lexishamilton\craftclearjobs\models;

use Craft;
use craft\base\Model;

/**
 * Clear Jobs settings
 */
class Settings extends Model
{
    public $clearApiUrl = "";

    public function rules(): array
    {
        return [
            [['clearApiUrl'], 'required'],
            ['clearApiUrl', 'url']
        ];

    }
}
