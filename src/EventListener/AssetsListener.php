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

        // Add CSS files
        foreach ($this->collections[$layout->assets_collection]['css'] as $css) {
            $GLOBALS['TL_CSS'][] = $this->computeFilename($css);
        }

        // Add JS files
        foreach ($this->collections[$layout->assets_collection]['js'] as $js) {
            if ($js['section'] === 'footer') {
                $GLOBALS['TL_BODY'][] = Template::generateScriptTag($this->computeFilename($js));
            } else {
                $GLOBALS['TL_JAVASCRIPT'][] = $this->computeFilename($js);
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
