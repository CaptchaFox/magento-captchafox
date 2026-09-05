<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    public const CAPTCHAFOX_CONFIG_PATH_SECRET_KEY = 'captchafox/settings/secret_key';
    public const CAPTCHAFOX_CONFIG_PATH_SITEKEY = 'captchafox/settings/sitekey';

    public const CAPTCHAFOX_CONFIG_PATH_FRONTEND_ENABLED = 'captchafox/frontend/enabled';
    public const CAPTCHAFOX_CONFIG_PATH_FRONTEND_THEME = 'captchafox/frontend/theme';
    public const CAPTCHAFOX_CONFIG_PATH_FRONTEND_LANGUAGE = 'captchafox/frontend/language';
    public const CAPTCHAFOX_CONFIG_PATH_FRONTEND_MODE = 'captchafox/frontend/mode';
    public const CAPTCHAFOX_CONFIG_PATH_FRONTEND_FORMS = 'captchafox/frontend/forms';
    public const CAPTCHAFOX_CONFIG_PATH_FRONTEND_SKIP_LOGGED_IN = 'captchafox/frontend/skip_logged_in';

    public const CAPTCHAFOX_CONFIG_PATH_ADMINHTML_ENABLED = 'captchafox/adminhtml/enabled';
    public const CAPTCHAFOX_CONFIG_PATH_ADMINHTML_THEME = 'captchafox/adminhtml/theme';
    public const CAPTCHAFOX_CONFIG_PATH_ADMINHTML_MODE = 'captchafox/adminhtml/mode';
    public const CAPTCHAFOX_CONFIG_PATH_ADMINHTML_FORMS = 'captchafox/adminhtml/forms';
    public const CAPTCHAFOX_CONFIG_PATH_ADMINHTML_LANGUAGE = 'captchafox/adminhtml/language';

    /**
     * Is CaptchaFox enabled on front
     *
     * @return bool
     */
    public function isEnabledOnFront(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::CAPTCHAFOX_CONFIG_PATH_FRONTEND_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is CaptchaFox enabled on admin
     *
     * @return bool
     */
    public function isEnabledOnAdmin(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::CAPTCHAFOX_CONFIG_PATH_ADMINHTML_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve Secret Key
     *
     * @return string
     */
    public function getSecretKey(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CAPTCHAFOX_CONFIG_PATH_SECRET_KEY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve Sitekey
     *
     * @return string
     */
    public function getSiteKey(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CAPTCHAFOX_CONFIG_PATH_SITEKEY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve frontend theme
     *
     * @return string
     */
    public function getFrontendTheme(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CAPTCHAFOX_CONFIG_PATH_FRONTEND_THEME,
            ScopeInterface::SCOPE_STORE
        );
    }

        /**
     * Retrieve frontend language
     *
     * @return string
     */
    public function getFrontendLanguage(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CAPTCHAFOX_CONFIG_PATH_FRONTEND_LANGUAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve admin theme
     *
     * @return string
     */
    public function getAdminTheme(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CAPTCHAFOX_CONFIG_PATH_ADMINHTML_THEME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve admin language
     *
     * @return string
     */
    public function getAdminLanguage(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CAPTCHAFOX_CONFIG_PATH_ADMINHTML_LANGUAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve frontend mode
     *
     * @return string
     */
    public function getFrontendMode(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CAPTCHAFOX_CONFIG_PATH_FRONTEND_MODE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve admin mode
     *
     * @return string
     */
    public function getAdminMode(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CAPTCHAFOX_CONFIG_PATH_ADMINHTML_MODE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve enabled frontend forms
     *
     * @return string[]
     */
    public function getFrontendForms(): array
    {
        $forms = $this->scopeConfig->getValue(
            self::CAPTCHAFOX_CONFIG_PATH_FRONTEND_FORMS,
            ScopeInterface::SCOPE_STORE
        );

        return $forms ? array_filter(explode(',', $forms)) : [];
    }

    /**
     * Retrieve enabled admin forms
     *
     * @return string[]
     */
    public function getAdminForms(): array
    {
        $forms = $this->scopeConfig->getValue(
            self::CAPTCHAFOX_CONFIG_PATH_ADMINHTML_FORMS,
            ScopeInterface::SCOPE_STORE
        );

        return $forms ? array_filter(explode(',', $forms)) : [];
    }

    /**
     * Skip the storefront validation for logged in customers
     *
     * Disabled by default: skipping the validation means an authenticated session is enough
     * to submit any protected storefront form without a captcha response.
     *
     * @return bool
     */
    public function isSkippedForLoggedInCustomers(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::CAPTCHAFOX_CONFIG_PATH_FRONTEND_SKIP_LOGGED_IN,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve API URL
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        return 'https://api.captchafox.com/siteverify';
    }

    /**
     * Retrieve default action
     *
     * @return string
     */
    public function getAction(): string
    {
        return 'default';
    }
}
