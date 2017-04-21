<?php

declare(strict_types = 1);

namespace Terminal42\AssetsBundle;

class PackageRegistry
{
    /**
     * Packages
     * @var array
     */
    private $packages = [];

    /**
     * PackageRegistry constructor.
     *
     * @param array $packages
     */
    public function __construct(array $packages)
    {
        $this->packages = $packages;
    }

    /**
     * Return true if the package is registered
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
     * Get the package
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
     * Get all packages
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->packages;
    }
}
