<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Model\ConfigProvider;

use CaptchaFox\Core\Helper\Config;
use CaptchaFox\Core\Model\ConfigProviderInterface;

class Frontend implements ConfigProviderInterface
{
    protected Config $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function getConfig(): array
    {
        return [
            'config' => [
                'enabled' => $this->config->isEnabledOnFront(),
                'sitekey' => $this->config->getSiteKey(),
                'theme'   => $this->config->getFrontendTheme(),
                'lang'   => $this->config->getFrontendLanguage(),
                'mode'    => $this->config->getFrontendMode(),
                'forms'   => $this->config->getFrontendForms(),
                'skipLoggedIn' => $this->config->isSkippedForLoggedInCustomers(),
            ]
        ];
    }
}
