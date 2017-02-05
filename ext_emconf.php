<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Responsive background images',
    'description' => 'View helper for rendering responsive background images',
    'category' => 'library',
    'version' => '1.0.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Jesper Paardekooper',
    'author_email' => 'j.paardekooper@develement.nl',
    'author_company' => 'DevElement',
    'constraints' => [
        'depends' => [
            'typo3' => '6.2.0-8.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'DevElement\\DevelementResponsiveBackgroundImg\\' => 'Classes'
        ]
    ],
];