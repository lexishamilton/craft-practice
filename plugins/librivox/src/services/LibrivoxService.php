<?php

namespace lexishamilton\craftlibrivox\services;

use craft\base\Component;
use craft\helpers\DateTimeHelper;
use lexishamilton\craftlibrivox\records\BookRecord;
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

//    public function getBooks() {
//
//
//    }


    public function saveBook($book) {

//        foreach($book as $k => $v) {
//            $kv = [$k, $v];
//            print_r($kv);
//        }

//        $copyrightYear = $book["copyright_year"];
//        $copyrightYear = (int) $copyrightYear;
//
//        print_r(gettype($copyrightYear));


        $bookRecord = new BookRecord();

        //add fields and hydrate
        $bookRecord->setAttribute('bookId', (int) $book["id"] );
        $bookRecord->setAttribute('title', (string) $book["title"] );
        $bookRecord->setAttribute('description', (string) $book["description"] );
        $bookRecord->setAttribute('language', (string) $book["language"] );
        $bookRecord->setAttribute('copyrightYear', (int) $book["copyright_year"] );
        $bookRecord->setAttribute('totalTime', (string) $book["totaltime"] );
//        $bookRecord->setAttribute('title', (string) $book["title"] ); //author ids


        //save into database
        $bookRecord->save();

    }
}
