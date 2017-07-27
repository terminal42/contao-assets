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
use Webmozart\PathUtil\Path;

class AssetsListener
{
    /**
     * @var array
     */
    private $collections;

    /**
     * Constructor.
     *
     * @param array  $collections
     */
    public function __construct(array $collections)
    {
        $this->collections = $collections;
    }

    /**
     * On DCA options callback.
     *
     * @return array
     */
    public function onDcaOptionsCallback(): array
    {
        return array_map(
            function (array $item) {
                return $item['name'];
            },
            $this->collections
        );
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

        $GLOBALS['TL_HEAD'] = is_array($GLOBALS['TL_HEAD']) ? $GLOBALS['TL_HEAD'] : [];
        $GLOBALS['TL_BODY'] = is_array($GLOBALS['TL_BODY']) ? $GLOBALS['TL_BODY'] : [];

        // Add <meta> tags
        foreach ($this->collections[$layout->assets_collection]['meta'] as $config) {
            $GLOBALS['TL_HEAD'][] = $this->generateTag('meta', $config['attributes']);
        }

        // Add <link> tags
        foreach ($this->collections[$layout->assets_collection]['link'] as $config) {
            $this->addVersion($config, 'href');
            $GLOBALS['TL_HEAD'][] = $this->generateTag('link', $config['attributes']);
        }

        // Add <script> tags
        foreach ($this->collections[$layout->assets_collection]['script'] as $config) {
            $this->addVersion($config, 'src');
            $tag = $this->generateTag('script', $config['attributes'], true);

            if ($config['section'] === 'header') {
                $GLOBALS['TL_HEAD'][] = $tag;
            } else {
                $GLOBALS['TL_BODY'][] = $tag;
            }
        }
    }

    private function generateTag(string $name, array $attributes, bool $close = false)
    {
        $attr = [];

        foreach ($attributes as $k => $v) {
            if (true === $v) {
                $attr[] = $k;
            } elseif (!is_bool($v)) {
                $attr[] = sprintf('%s="%s"', $k, htmlspecialchars($v, ENT_COMPAT, 'UTF-8', false));
            }
        }

        if (empty($attr)) {
            return '';
        }

        return sprintf(
            '<%s %s>%s',
            $name,
            implode(' ', $attr),
            $close ? '</'.$name.'>' : ''
        );
    }

    private function addVersion(array &$config, string $pathKey)
    {
        if (!isset($config['version'])) {
            return;
        }

        $config['attributes'][$pathKey] = sprintf('%s?v=%s', $config['attributes'][$pathKey], $config['version']);
    }
}
