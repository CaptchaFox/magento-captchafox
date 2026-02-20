<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Block\CaptchaFox;

use CaptchaFox\Core\Model\ConfigProviderInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Config extends Template
{
    protected SerializerInterface $serializer;

    protected ConfigProviderInterface $configProvider;

    /**
     * @param Context $context
     * @param SerializerInterface $serializer
     * @param ConfigProviderInterface $configProvider
     * @param mixed[] $data
     */
    public function __construct(
        Context $context,
        SerializerInterface $serializer,
        ConfigProviderInterface $configProvider,
        array $data = []
    ) {
        $this->serializer     = $serializer;
        $this->configProvider = $configProvider;

        parent::__construct($context, $data);
    }

    /**
     * Retrieve configuration
     *
     * @return string[]
     */
    public function getCaptchaFoxConfig(): array
    {
        return $this->configProvider->getConfig();
    }

    /**
     * Retrieve serialized config
     *
     * @return string
     */
    public function getSerializedCaptchaFoxConfig(): string
    {
        return (string)$this->serializer->serialize($this->getCaptchaFoxConfig());
    }
}
