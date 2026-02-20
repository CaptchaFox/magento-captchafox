<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Observer\Validate;

use Magento\Customer\Controller\Ajax\Login as AjaxLoginPost;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Response\Http as Response;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use CaptchaFox\Core\Helper\Config;
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
        CustomerSession $customerSession,
        ?PersistorInterface $persistor = null,
        array $data = []
    ) {
        $this->customerSession = $customerSession;

        parent::__construct($messageManager, $response, $validator, $json, $config, $persistor, $data);
    }


    /**
     * Can validate action
     *
     * @return bool
     */
    public function canValidate(): bool
    {
        if ($this->customerSession->isLoggedIn()) {
            return false;
        }

        return parent::canValidate();
    }

    /**
     * Test if the form is enabled
     *
     * @param string $form
     * @return bool
     */
    public function isFormEnabled(string $form): bool
    {
        return in_array($form, $this->config->getFrontendForms());
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
        if ($this->action instanceof AjaxLoginPost) {
            return $this->json->unserialize($this->request?->getContent() ?? '{}')['cf-captcha-response'] ?? null;
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
        if ($this->action instanceof AjaxLoginPost) {
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
