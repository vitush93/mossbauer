<?php

define('ROOT_DIR', __DIR__);

require_once 'admin/bootstrap.php';
require_once 'vendor/autoload.php';
require_once 'autoload.php';

/** @var \Latte\Engine $latte */
$latte = \Mossbauer\Core\Container::get('latte');

$app = new \Slim\Slim();

$app->get('/', function () {

    /** @var \Latte\Engine $latte */
    $latte = \Mossbauer\Core\Container::get('latte');

    $upcoming = collection('Upcoming')->find(function ($item) {
        $date = new \Nette\Utils\DateTime($item['Date']);

        return $date > new \Nette\Utils\DateTime();
    })->limit(5)->sort(['Date' => 1])->toArray();

    $news = collection('News')->find()->sort(['created' => -1])->toArray();

    $latte->render('templates/home/home.latte', [
        'upcoming' => $upcoming,
        'news' => $news
    ]);
});

$app->get('/people', function () use ($app, $latte) {

    $breadcrumbs = [
        ['title' => 'People']
    ];

    $people = collection('People')->find()->toArray();

    $latte->render('templates/people/people.latte', [
        'breadcrumbs' => $breadcrumbs,
        'people' => $people
    ]);
});

$app->get('/news', function () use ($app, $latte) {

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

    $news = collection('News')->find()->limit($limit)->skip($offset)->sort(['created' => -1])->toArray();

    $latte->render('templates/news/news.latte', [
        'breadcrumbs' => $breadcrumbs,
        'paginator' => $paginator,
        'news' => $news
    ]);
});

$app->get('/news/:slug', function ($slug) use ($latte) {

    $entry = collection('News')->findOne(['Title_slug' => $slug]);
    if ($entry == null) {
        $latte->render('templates/error.latte');

        return;
    }

    $breadcrumbs = [
        ['title' => 'News archive', 'link' => '/news'],
        ['title' => $entry['Title']]
    ];

    $latte->render('templates/news/single.latte', [
        'breadcrumbs' => $breadcrumbs,
        'entry' => $entry
    ]);
});

$app->get('/:page', function ($page) use ($latte) {

    /** @var \Latte\Engine $latte */
    $latte = \Mossbauer\Core\Container::get('latte');

    $page = collection('Pages')->findOne(['Title_slug' => $page]);
    if ($page == null) {
        $latte->render('templates/error.latte');

        return;
    }

    $latte->render('templates/page/page.latte', [
        'breadcrumbs' => [
            ['title' => $page['Title']]
        ],
        'page' => $page,
    ]);
});

$app->run();