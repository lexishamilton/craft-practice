<?php

namespace lexishamilton\craftlibrivox\services;

use craft\base\Component;
use craft\helpers\DateTimeHelper;
use lexishamilton\craftlibrivox\models\BookModel;
use lexishamilton\craftlibrivox\records\BookAuthorRecord;
use lexishamilton\craftlibrivox\records\BookRecord;
use lexishamilton\craftlibrivox\records\AuthorRecord;
use GuzzleHttp;

class LibrivoxService extends Component {

    protected function _apiRequest(): array {

        // GET request
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', 'https://librivox.org/api/feed/audiobooks/?format=json');
        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody) {
            return $responseBody;
        } else {

            // @TODO: if empty, we wouldn't want to cache it.
            return [];
        }
    }

    public function loadBooks() {
        $responseData = $this->_apiRequest();
        $books = [];
//        var_dump($responseData); exit;

        //check that array isn't empty
        if(sizeof($responseData) > 0 && array_key_exists('books', $responseData)) {
            $books = $responseData['books'];

            // for each, save a book if book id unique
            foreach($books as $book) {
                $this->saveBook($book);
            }
        }

        return $books;
    }

    public function saveBook($book) {
        // Check if the Book has already been loaded
        $bookRecord = BookRecord::find()
            ->where(['bookId' => (string) $book['id']])
            ->one();

        // If not in the db, save it
        if (!$bookRecord) {
            $bookRecord = new BookRecord();

            //add fields and hydrate
            $bookRecord->setAttribute('bookId', (int)$book['id']);
            $bookRecord->setAttribute('title', (string)$book['title']);
            $bookRecord->setAttribute('description', (string)$book['description']);
            $bookRecord->setAttribute('language', (string)$book['language']);
            $bookRecord->setAttribute('copyrightYear', (int)$book['copyright_year']);
            $bookRecord->setAttribute('totalTime', (string)$book['totaltime']);

            //save book authors
            $authors = $book['authors'];
            foreach ($authors as $author) {
                $this->saveAuthor($book['id'], $author);
            }

            //save into database
            $bookRecord->save();
        }
    }

    public function saveAuthor($bookId, $author) {
//        print_r($author['first_name'] . " " . $author['last_name']);

        // Check if the Author has already been loaded
        $authorRecord = AuthorRecord::find()
            ->where(['authorId' => (string) $author['id']])
            ->one();

        // If not in the db, save it
        if (!$authorRecord) {
            $authorRecord = new AuthorRecord();

            //add fields and hydrate
            $authorRecord->setAttribute('authorId', (int)$author['id']);
            $authorRecord->setAttribute('firstName', (string)$author['first_name']);
            $authorRecord->setAttribute('lastName', (string)$author['last_name']);
            $authorRecord->setAttribute('dob', (string)$author['dob']);
            $authorRecord->setAttribute('dod', (string)$author['dod']);

            $authorRecord->save();
        }


        //Add relational mapping to db of book and author
        $bookAuthorRecord = new BookAuthorRecord();

        //add fields and hydrate
        $bookAuthorRecord->setAttribute('bookId', (int)$bookId);
        $bookAuthorRecord->setAttribute('authorId', (int)$author['id']);

        $bookAuthorRecord->save();
    }


    public function getBooks(): array
    {
        $bookRecordTableName = BookRecord::tableName();

        $bookRecords = BookRecord::find()
            ->select("${bookRecordTableName}.*")
            ->all();

        $bookModels = [];

        foreach($bookRecords as $bookRecord) {
            $bookModel = new BookModel();
            $bookModel->setAttributes($bookRecord->getAttributes(), false);

            $bookModels[] = $bookModel;
        }

        return $bookModels;
    }

    public function getBookById($bookId): BookModel
    {
        $bookModel = new BookModel();

        $bookRecord = BookRecord::find()
            ->where(['bookId' => $bookId])
            ->one();

        if ($bookRecord) {
            $bookModel->setAttributes($bookRecord->getAttributes(), false);
        }

        return $bookModel;

    }
}
