<?php

namespace lexishamilton\craftlibrivox\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
use lexishamilton\craftlibrivox\Plugin;

class SearchController extends Controller
{
    public function actionSearch(): Response
    {
        $sort = Craft::$app->request->getQueryParam('sort');
        $page = Craft::$app->request->getQueryParam('page');
        $pageSize = Craft::$app->request->getQueryParam('per_page');
        $search = Craft::$app->request->getQueryParam('search');

        $searchResults= [];

        return $this->asJson([
            'pagination' => [
                'total' => (int) 0,
                'per_page' => (int) $pageSize,
                'current_page' => (int) $page,
                'last_page' => (int) 1,
                'from' => (int) (($page - 1) * $pageSize) + 1,
                'to' => (int) (($page * $pageSize) < 0) ? $page * $pageSize : 0,
            ],
            'data' => $searchResults
        ]);
    }
}