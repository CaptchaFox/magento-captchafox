<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Observer\Validate;

use Exception;
use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\Plugin\AuthenticationException;
use CaptchaFox\Core\Helper\Config;
use CaptchaFox\Core\Model\Config\Source\Forms\Adminhtml as Forms;
use CaptchaFox\Core\Model\Validator;

/**
 * Validate CaptchaFox on the admin login.
 *
 * The credentials are posted to whichever admin URL the user landed on, so there is no single
 * controller action to observe. admin_user_authenticate_before fires wherever the login happens.
 */
class AdminLogin implements ObserverInterface
{
    protected Config $config;

    protected Validator $validator;

    protected Request $request;

    /**
     * @param Config $config
     * @param Validator $validator
     * @param Request $request
     */
    public function __construct(
        Config $config,
        Validator $validator,
        Request $request
    ) {
        $this->config    = $config;
        $this->validator = $validator;
        $this->request   = $request;
    }

    /**
     * Validate CaptchaFox before the admin user is authenticated
     *
     * @param Observer $observer
     * @return void
     * @throws AuthenticationException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer): void
    {
        if (!$this->config->isEnabledOnAdmin()) {
            return;
        }
        if (!in_array(Forms::FORM_LOGIN, $this->config->getAdminForms(), true)) {
            return;
        }

        $response = $this->request->getParam('cf-captcha-response');
        $response = is_string($response) ? $response : null;

        try {
            if ($this->validator->isValid($response)) {
                return;
            }

            $message = __(
                'Security validation error: %1',
                join(', ', $this->validator->getErrorMessages())
            );
        } catch (Exception $exception) {
            $message = __('Security validation error: %1', $exception->getMessage());
        }

        throw new AuthenticationException($message);
    }
}
