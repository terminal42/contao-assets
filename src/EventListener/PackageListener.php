<?php

declare(strict_types = 1);

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
        $this->handler  = $handler;
        $this->registry = $registry;
    }

    /**
     * On generate the page
     *
     * @param PageModel   $page
     * @param LayoutModel $layout
     */
    public function onGeneratePage(PageModel $page, LayoutModel $layout): void
    {
        if (!$this->registry->has($layout->assets_package)) {
            return;
        }

        $this->handler->addToPage($this->registry->get($layout->assets_package));
    }

    /**
     * On DCA options callback
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
