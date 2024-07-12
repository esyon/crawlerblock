<?php

/**
 * This Software is the property of ESYON and is protected by copyright law - it is NOT Freeware.
 * Any unauthorized use of this software without a valid license is a violation of the license agreement and will be
 * prosecuted by civil and criminal law.
 *
 * @copyright      (C) ESYON GmbH
 * @since          Version 1.0
 * @author         Alexander Hirschfeld <support@esyon.de>
 * @link           https://www.esyon.de
 */

use OxidEsales\Eshop\Core\ShopControl;

$sMetadataVersion = '2.1';

$aModule = [
    'id' => 'esy/crawlerblock',
    'title' => [
        'de' => 'ESYON Crawler-Blocker',
        'en' => 'ESYON crawler block',
    ],
    'description' => [
        'de' => 'Modul blockiert sämtliche bots / crawlers / spiders.',
        'en' => 'Modul blocks all kind of bots / crawlers / spiders.',
    ],
    'thumbnail' => 'module.png',
    'version' => '1.0.2',
    'author' => 'ESYON GmbH',
    'url' => 'https://www.esyon.de',
    'email' => 'support@esyon.de',
    'extend' => [
        ShopControl::class => Esyon\CrawlerBlock\Core\ShopControl::class
    ],
    'settings' => [
        [
            'group' => 'ESYCrawlerBlockSettings',
            'name' => 'esyUserAgentWhiteList',
            'type' => 'arr',
            'value' => [],
        ],
        [
            'group' => 'ESYCrawlerBlockSettings',
            'name' => 'esyUserAgentBlackList',
            'type' => 'arr',
            'value' => [],
        ],
        [
            'group' => 'ESYCrawlerBlockSettings',
            'name' => 'isCrawlerDetectionDisabled',
            'type' => 'bool',
            'value' => false,
        ],
    ],
];
