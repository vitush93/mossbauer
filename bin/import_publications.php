<?php

require '../admin/bootstrap.php';
require '../vendor/autoload.php';


foreach (\Nette\Utils\Finder::findFiles('*.json')->in('./publications') as $key => $file) {
    $json = file_get_contents($key);
    $publications = \Nette\Utils\Json::decode($json);

    foreach ($publications as $pub) {
        if (property_exists($pub, 'Fields')) {
            $row = $pub->Fields;

            $data = [
                'Authors' => $row->author,
                'Title' => $row->title,
                'Journal' => $row->journal,
                'Volume' => $row->volume,
                'Issue' => $row->number,
                'Year' => $row->year,
                'Pages' => null,
                'DOI' => $row->doi,
                'DOIURL' => $row->url,
                'Best' => false
            ];

            cockpit('collections:save_entry', 'Publications', $data);
        }
    }
}

exit(0);
