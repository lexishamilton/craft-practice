<?php

namespace lexishamilton\craftlibrivox\controllers;

use craft\web\Controller;
use craft\web\Response;

class BookController extends Controller
{

    public function actionIndex(): Response
    {
        $variables = [];

        return $this->renderTemplate('librivox/index', $variables);
    }

    public function actionEdit($bookId): Response {
        $variables = [];
        $variables['bookId'] = $bookId;

        return $this->renderTemplate('librivox/book/_edit', $variables);
    }
}


