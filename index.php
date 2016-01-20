<?php

define('ROOT_DIR', __DIR__);

require_once 'admin/bootstrap.php';
require_once 'vendor/autoload.php';
require_once 'autoload.php';

$app = new \Slim\Slim();

$app->get('/', function () {

    /** @var \Latte\Engine $latte */
    $latte = \Mossbauer\Core\Container::get('latte');

    $latte->render('templates/home/home.latte');
});

$app->get('/news', function () use ($app) {

    /** @var \Latte\Engine $latte */
    $latte = \Mossbauer\Core\Container::get('latte');

    // prepare breadcrumbs
    $breadcrumbs = [
        ['title' => 'News archive']
    ];

    $newsCount = cockpit('collections:count', 'News');

    $pageParam = $app->request()->get('page');
    $currentPage = $pageParam ? $pageParam : 1;

    $paginator = new \Nette\Utils\Paginator();
    $paginator->setItemCount($newsCount);
    $paginator->setItemsPerPage(6);
    $paginator->setPage($currentPage);

    $limit = $paginator->getItemsPerPage(); // per page
    $offset = $paginator->getOffset();

    $news = collection('News')->find()->limit($limit)->skip($offset)->toArray();

    $latte->render('templates/news/news.latte', [
        'breadcrumbs' => $breadcrumbs,
        'paginator' => $paginator,
        'news' => $news
    ]);
});

$app->get('/news/:slug', function ($slug) {
    // TODO render single article

    /** @var \Latte\Engine $latte */
    $latte = \Mossbauer\Core\Container::get('latte');

    $breadcrumbs = [
        ['title' => 'News archive', 'link' => '/news'],
        ['title' => 'News entry title']
    ];

    $latte->render('templates/news/single.latte', ['breadcrumbs' => $breadcrumbs]);
});

$app->get('/:page', function ($page) {
    // TODO retrieve page

    /** @var \Latte\Engine $latte */
    $latte = \Mossbauer\Core\Container::get('latte');

    $latte->render('templates/page/page.latte', [
        'breadcrumbs' => [
            ['title' => 'Page title']
        ],
        'page' => 'Page title'
    ]);
});

$app->run();