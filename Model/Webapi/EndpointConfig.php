<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Model\Webapi;

/**
 * Maps web API endpoints (REST service methods and GraphQL resolvers) to CaptchaFox form names.
 *
 * Endpoints are declared in di.xml so integrators can protect their own services without
 * overriding any class.
 */
class EndpointConfig
{
    /**
     * @var string[] form name indexed by "<class>::<method>"
     */
    protected array $map = [];

    /**
     * @param array $endpoints
     */
    public function __construct(array $endpoints = [])
    {
        foreach ($endpoints as $endpoint) {
            if (empty($endpoint['class']) || empty($endpoint['method']) || empty($endpoint['form'])) {
                continue;
            }

            $key = $this->getKey((string)$endpoint['class'], (string)$endpoint['method']);
            $this->map[$key] = (string)$endpoint['form'];
        }
    }

    /**
     * Retrieve the CaptchaFox form name protecting an endpoint, if any
     *
     * @param string $serviceClass
     * @param string $serviceMethod
     * @return string|null
     */
    public function getFormFor(string $serviceClass, string $serviceMethod): ?string
    {
        return $this->map[$this->getKey($serviceClass, $serviceMethod)] ?? null;
    }

    /**
     * Build the lookup key for an endpoint
     *
     * @param string $serviceClass
     * @param string $serviceMethod
     * @return string
     */
    protected function getKey(string $serviceClass, string $serviceMethod): string
    {
        return ltrim($serviceClass, '\\') . '::' . $serviceMethod;
    }
}
