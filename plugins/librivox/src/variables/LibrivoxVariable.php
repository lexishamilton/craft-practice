<?php

namespace lexishamilton\craftlibrivox\variables;

use lexishamilton\craftlibrivox\Plugin;

class LibrivoxVariable
{
    public function getLibrivoxBooks(): array {
        return Plugin::getInstance()->librivox->getBooks();
    }

    public function getLibrivoxBookById($bookId): BookModel {
        return Plugin::getInstance()->librivox->getBookById();
    }

}