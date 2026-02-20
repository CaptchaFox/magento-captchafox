<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Model\Config\Source\Forms;

use CaptchaFox\Core\Model\Config\Source\Forms;

class Adminhtml extends Forms
{
    public const FORM_LOGIN = 'login';
    public const FORM_PASSWORD = 'password';

    public function toArray(): array
    {
        return [
            self::FORM_LOGIN,
            self::FORM_PASSWORD,
        ];
    }
}
