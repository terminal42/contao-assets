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

class PackageRegistry
{
    /**
     * Packages.
     *
     * @var array
     */
    private $packages = [];

    /**
     * PackageRegistry constructor.
     *
     * @param array $packages
     */
    public function __construct(array $packages = [])
    {
        $this->packages = $packages;
    }

    /**
     * Return true if the package is registered.
     *
     * @param string $package
     *
     * @return bool
     */
    public function has(string $package): bool
    {
        return array_key_exists($package, $this->packages);
    }

    /**
     * Get the package.
     *
     * @param string $package
     *
     * @return array
     */
    public function get(string $package): array
    {
        if (!$this->has($package)) {
            throw new \InvalidArgumentException(sprintf('The package "%s" does not exist', $package));
        }

        return array_merge($this->packages[$package]);
    }

    /**
     * Get all packages.
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->packages;
    }
}
