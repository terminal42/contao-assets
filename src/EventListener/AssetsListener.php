<?php

declare(strict_types=1);

/*
 * Assets Bundle for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2017-2017, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-assets
 */

namespace Terminal42\AssetsBundle\EventListener;

use Contao\LayoutModel;
use Contao\PageModel;
use Contao\Template;
use Webmozart\PathUtil\Path;

class AssetsListener
{
    /**
     * @var array
     */
    private $collections;

    /**
     * @var string
     */
    private $webDir;

    /**
     * AssetsListener constructor.
     *
     * @param array  $collections
     * @param string $webDir
     */
    public function __construct(array $collections, string $webDir)
    {
        $this->collections = $collections;
        $this->webDir = $webDir;
    }

    /**
     * On DCA options callback.
     *
     * @return array
     */
    public function onDcaOptionsCallback(): array
    {
        $options = [];

        foreach ($this->collections as $key => $data) {
            $options[$key] = $data['name'];
        }

        return $options;
    }

    /**
     * On generate the page.
     *
     * @param PageModel   $page
     * @param LayoutModel $layout
     */
    public function onGeneratePage(PageModel $page, LayoutModel $layout): void
    {
        if (!isset($this->collections[$layout->assets_collection])) {
            return;
        }

        $GLOBALS['TL_CSS'] = is_array($GLOBALS['TL_CSS']) ? $GLOBALS['TL_CSS'] : [];

        // Add CSS files
        foreach (array_reverse($this->collections[$layout->assets_collection]['css']) as $file) {
            array_unshift($GLOBALS['TL_CSS'], $this->computeFilename($file));
        }

        $GLOBALS['TL_BODY'] = is_array($GLOBALS['TL_BODY']) ? $GLOBALS['TL_BODY'] : [];
        $GLOBALS['TL_JAVASCRIPT'] = is_array($GLOBALS['TL_JAVASCRIPT']) ? $GLOBALS['TL_JAVASCRIPT'] : [];

        // Add JS files
        foreach (array_reverse($this->collections[$layout->assets_collection]['js']) as $file) {
            if ($file['section'] === 'footer') {
                array_unshift($GLOBALS['TL_BODY'], Template::generateScriptTag($this->computeFilename($file), $file['async']));
            } else {
                array_unshift($GLOBALS['TL_JAVASCRIPT'], $this->computeFilename($file).($file['async'] ? '|async' : ''));
            }
        }
    }

    /**
     * Compute the filename.
     *
     * @param array $file
     *
     * @return string
     */
    private function computeFilename(array $file): string
    {
        return sprintf('%s?v=%s', Path::makeRelative($file['name'], $this->webDir), substr($file['version'], 0, 8));
    }
}
