<?php

namespace lexishamilton\craftlibrivox\variables;

use lexishamilton\craftlibrivox\Plugin;

class LibrivoxVariable
{
    public function getLibrivoxBooks(): array {
        return Plugin::getInstance()->librivox->loadBooks();
    }

}