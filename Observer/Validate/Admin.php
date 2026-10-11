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

    /**
     * Test if the form is enabled
     *
     * @param string $form
     * @return bool
     * @deprecated 1.2.0 Kept for code written against 1.1.x; use getEnabledForms().
     */
    public function isFormEnabled(string $form): bool
    {
        return in_array($form, $this->getEnabledForms());
    }
}
