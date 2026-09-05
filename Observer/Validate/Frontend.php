<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Observer\Validate;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\Http as Response;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;
use CaptchaFox\Core\Helper\Config;
use CaptchaFox\Core\Model\Config\Source\Forms\Frontend as Forms;
use CaptchaFox\Core\Model\PersistorInterface;
use CaptchaFox\Core\Model\Validator;
use CaptchaFox\Core\Observer\Validate;

class Frontend extends Validate
{
    protected CustomerSession $customerSession;

    /**
     * @param ManagerInterface $messageManager
     * @param Response $response
     * @param Validator $validator
     * @param Json $json
     * @param Config $config
     * @param RedirectInterface $redirect
     * @param LoggerInterface $logger
     * @param CustomerSession $customerSession
     * @param PersistorInterface|null $persistor
     * @param array $data
     */
    public function __construct(
        ManagerInterface $messageManager,
        Response $response,
        Validator $validator,
        Json $json,
        Config $config,
        RedirectInterface $redirect,
        LoggerInterface $logger,
        CustomerSession $customerSession,
        ?PersistorInterface $persistor = null,
        array $data = []
    ) {
        $this->customerSession = $customerSession;

        parent::__construct(
            $messageManager,
            $response,
            $validator,
            $json,
            $config,
            $redirect,
            $logger,
            $persistor,
            $data
        );
    }


    /**
     * Can validate action
     *
     * @return bool
     */
    public function canValidate(): bool
    {
        if ($this->config->isSkippedForLoggedInCustomers() && $this->customerSession->isLoggedIn()) {
            return false;
        }

        return parent::canValidate();
    }

    /**
     * Retrieve the forms enabled in the configuration
     *
     * @return string[]
     */
    public function getEnabledForms(): array
    {
        return $this->config->getFrontendForms();
    }

    /**
     * Retrieve if validator is globally enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->config->isEnabledOnFront();
    }

    /**
     * Retrieve CaptchaFox response
     *
     * @return string|null
     */
    public function getCfResponse(): ?string
    {
        if ($this->getForm() === Forms::FORM_LOGIN_AJAX) {
            $response = $this->json->unserialize($this->request?->getContent() ?? '{}')['cf-captcha-response'] ?? null;
            return is_string($response) ? $response : null;
        }
        return parent::getCfResponse();
    }

    /**
     * Send error
     *
     * @param Phrase $message
     * @return void
     */
    protected function error(Phrase $message): void
    {
        if ($this->getForm() === Forms::FORM_LOGIN_AJAX) {
            $data = [
                'errors' => true,
                'message' => $message
            ];
            $this->response->representJson($this->json->serialize($data));

            $this->response->sendResponse();
            exit();
        }

        parent::error($message);
    }
}
