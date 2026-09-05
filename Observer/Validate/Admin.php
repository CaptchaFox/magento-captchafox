<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Observer\Validate;

use CaptchaFox\Core\Observer\Validate;

class Admin extends Validate
{
    /**
     * Retrieve if validator is globally enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isEnabledOnAdmin();
    }

    /**
     * Retrieve the forms enabled in the configuration
     *
     * @return string[]
     */
    public function getEnabledForms(): array
    {
        return $this->config->getAdminForms();
    }
}
