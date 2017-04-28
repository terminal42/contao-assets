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
use Terminal42\AssetsBundle\PackageHandler;
use Terminal42\AssetsBundle\PackageRegistry;

class PackageListener
{
    /**
     * @var PackageHandler
     */
    private $handler;

    /**
     * @var PackageRegistry
     */
    private $registry;

    /**
     * PackageListener constructor.
     *
     * @param PackageHandler  $handler
     * @param PackageRegistry $registry
     */
    public function __construct(PackageHandler $handler, PackageRegistry $registry)
    {
        $this->handler = $handler;
        $this->registry = $registry;
    }

    /**
     * On generate the page.
     *
     * @param PageModel   $page
     * @param LayoutModel $layout
     */
    public function onGeneratePage(PageModel $page, LayoutModel $layout): void
    {
        if (!$this->registry->has((string) $layout->assets_package)) {
            return;
        }

        $this->handler->addToPage($this->registry->get((string) $layout->assets_package));
    }

    /**
     * On DCA options callback.
     *
     * @return array
     */
    public function onDcaOptionsCallback(): array
    {
        $options = [];

        foreach ($this->registry->getAll() as $key => $data) {
            $options[$key] = $data['name'];
        }

        return $options;
    }
}
