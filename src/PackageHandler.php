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

namespace Terminal42\AssetsBundle;

use Webmozart\PathUtil\Path;

class PackageHandler
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * PackageHandler constructor.
     *
     * @param string $rootDir
     */
    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * Add package to the page.
     *
     * @param array $package
     */
    public function addToPage(array $package): void
    {
        // Add CSS files
        foreach ($package['css'] as $css) {
            $GLOBALS['TL_CSS'][] = $this->computeFilename($css);
        }

        // Add JS files
        foreach ($package['js'] as $js) {
            if ($js['section'] === 'footer') {
                $GLOBALS['TL_BODY'][] = sprintf('<script src="%s"></script>', $this->computeFilename($js));
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
        return sprintf('%s?v=%s', Path::makeRelative($file['name'], $this->rootDir.'/web/'), substr($file['version'], 0, 8));
    }
}
