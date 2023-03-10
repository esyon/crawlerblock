<?php

/**
 * This Software is the property of ESYON and is protected by copyright law - it is NOT Freeware.
 * Any unauthorized use of this software without a valid license is a violation of the license agreement and will be
 * prosecuted by civil and criminal law.
 *
 * @copyright      (C) ESYON GmbH
 * @since              Version 1.0
 * @author             Alexander Hirschfeld <support@esyon.de>
 * @link               http://www.esyon.de
 */

declare(strict_types=1);

namespace Esyon\CrawlerBlock\Core;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class ViewConfig
 * @package Esyon\MultiBaskets\Core
 */
class ShopControl extends ShopControl_parent
{
    /**
     * @return bool
     */
    private function isUserAgentWhiteListed(): bool
    {
        $userAgentWhiteList = Registry::getConfig()->getConfigParam('esyUserAgentWhiteList');
        if (is_array($userAgentWhiteList) && count($userAgentWhiteList) > 0) {
            $userAgent = strtolower(Registry::getUtilsServer()->getServerVar('HTTP_USER_AGENT'));
            foreach ($userAgentWhiteList as $listUserAgent) {
                $listUserAgent = strtolower($listUserAgent);
                if (str_contains($userAgent, $listUserAgent)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * start
     *
     * @param  mixed $sClass
     * @param  mixed $sFunction
     * @param  mixed $aParams
     * @param  mixed $aViewsChain
     * @return void
     */
    public function start($sClass = null, $sFunction = null, $aParams = null, $aViewsChain = null)
    {
        if (!$this->isAdmin() && !$this->isUserAgentWhiteListed()) {
            $crawlerDetect = new CrawlerDetect();
            if ($crawlerDetect->isCrawler() === true) {
                Registry::getUtils()->showMessageAndExit('');
            }
        }

        parent::start($sClass, $sFunction, $aParams, $aParams, $aViewsChain);
    }
}
