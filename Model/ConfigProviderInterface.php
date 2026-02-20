<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Model;

interface ConfigProviderInterface
{
    /**
     * Retrieve assoc array of captchafox configuration
     *
     * @return array
     */
    public function getConfig(): array;
}
