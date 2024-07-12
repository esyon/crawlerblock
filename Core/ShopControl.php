<?php

/**
 * This Software is the property of ESYON and is protected by copyright law - it is NOT Freeware.
 * Any unauthorized use of this software without a valid license is a violation of the license agreement and will be
 * prosecuted by civil and criminal law.
 *
 * @copyright      (C) ESYON GmbH
 * @since              Version 1.0
 * @author             Alexander Hirschfeld <support@esyon.de>
 * @link               https://www.esyon.de
 */

declare(strict_types=1);

namespace Esyon\CrawlerBlock\Core;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use OxidEsales\Eshop\Core\Registry;

/**
 *
 */
class ShopControl extends ShopControl_parent
{
    /**
     * @var string
     */
    private const USER_AGENT_WHITE_LIST = 'esyUserAgentWhiteList';

    /**
     * @var string
     */
    private const USER_AGENT_BLACK_LIST = 'esyUserAgentBlackList';

    /**
     * @var boolean
     */
    private const BLOCK_ONLY_BLACKLIST = 'esyBlockOnlyBlackListed';


    /**
     * @var string|null
     */
    private string|null $userAgent = null;

    /**
     * Get current user agent.
     *
     * @return string
     */
    private function getCurrentUserAgent(): string
    {
        if($this->userAgent === null) {
            $this->userAgent = strtolower(
                Registry::getUtilsServer()->getServerVar('HTTP_USER_AGENT') ?? ''
            );
        }

        return $this->userAgent;
    }

    /**
     * Check if user agent is listed in the given list.
     *
     * @param string $listName
     * @return bool
     */
    private function isUserAgentListed(string $listName): bool
    {
        $userAgentList = Registry::getConfig()->getConfigParam($listName) ?? [];
        if (empty($userAgentList)) {
            return false;
        }

        $userAgent = $this->getCurrentUserAgent();
        foreach ($userAgentList as $listUserAgent) {
            if (str_contains($userAgent, strtolower($listUserAgent))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Block request.
     *
     * @return void
     */
    private function blockRequest(): void
    {
        http_response_code(403);
        Registry::getUtils()->showMessageAndExit('');
    }

    /**
     * @param $sClass
     * @param $sFunction
     * @param $aParams
     * @param $aViewsChain
     * @return void
     */
    public function start($sClass = null, $sFunction = null, $aParams = null, $aViewsChain = null)
    {
        if (!$this->isAdmin()) {
            if ($this->isUserAgentListed(self::USER_AGENT_BLACK_LIST)) {
                $this->blockRequest();
            }

            if (Registry::getConfig()->getConfigParam(self::BLOCK_ONLY_BLACKLIST) === true) {
                parent::start($sClass, $sFunction, $aParams, $aParams, $aViewsChain);
                return;
            }

            if (!$this->isUserAgentListed(self::USER_AGENT_WHITE_LIST)) {
                $crawlerDetect = new CrawlerDetect();
                if ($crawlerDetect->isCrawler()) {
                    $this->blockRequest();
                }
            }
        }

        parent::start($sClass, $sFunction, $aParams, $aParams, $aViewsChain);
    }
}
