<?php

namespace lexishamilton\craftlibrivox\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
use lexishamilton\craftlibrivox\Plugin;

class BookController extends Controller
{

    public function actionIndex(): Response
    {
        $variables = [];

        return $this->renderTemplate('librivox/index', $variables);
    }

    public function actionEdit($bookId): Response {
        $request = Craft::$app->getRequest();

        if ($request->getIsPost()) {
            $post = $request->post();

            try {
                Plugin::getInstance()->librivox->updateBook($post);

                Craft::$app->getSession()->setNotice(Craft::t('librivox', 'Book was successfully saved'));
            } catch (\Exception $e) {
                Craft::$app->getSession()->setError(Craft::t('librivox', 'There was an error importing the book: ' . $e->getMessage()));
                Craft::error('There was an error importing the book: ' . $e->getMessage());
            }
        } else {
            $variables = [];
            $variables['book'] = Plugin::getInstance()->librivox->getBookById($bookId);

            return $this->renderTemplate('librivox/book/_edit', $variables);
        }

        return $this->redirect('librivox/index');
    }


    public function actionDelete($bookId): Response {
        if (!$bookId) {
            Craft::$app->getSession()->setError(Craft::t('librivox', 'Book ID could not be found'));
        }

        try {
            if(Plugin::getInstance()->librivox->deleteBook($bookId)) {
                Craft::$app->getSession()->setNotice(Craft::t('librivox', 'Book was successfully deleted'));
            } else {
                Craft::$app->getSession()->setError(Craft::t('librivox', 'The Book could not be deleted'));
            }

        } catch (\Exception $e) {
            Craft::$app->getSession()->setError(Craft::t('librivox', 'The Book could not be deleted'));
        }

        return $this->redirect('librivox/index');
    }
}


