<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Observer;

use Exception;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\App\Response\Http as Response;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use CaptchaFox\Core\Helper\Config;
use CaptchaFox\Core\Model\PersistorInterface;
use CaptchaFox\Core\Model\Validator;

abstract class Validate implements ObserverInterface
{
    protected ManagerInterface $messageManager;

    protected Response $response;

    protected Validator $validator;

    protected Json $json;

    protected Config $config;

    protected RedirectInterface $redirect;

    protected ?PersistorInterface $persistor = null;

    protected array $actions = [];

    public ?ActionInterface $action = null;

    public ?Request $request = null;

    /**
     * @param ManagerInterface $messageManager
     * @param Response $response
     * @param Validator $validator
     * @param Json $json
     * @param Config $config
     * @param RedirectInterface $redirect
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
        ?PersistorInterface $persistor = null,
        array $data = []
    ) {
        $this->messageManager = $messageManager;
        $this->response       = $response;
        $this->validator      = $validator;
        $this->json           = $json;
        $this->config         = $config;
        $this->redirect       = $redirect;
        $this->persistor      = $persistor;
        $this->actions        = $data['actions'] ?? [];
    }

    /**
     * Validate CaptchaFox
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $this->action = $observer->getEvent()->getData('controller_action');
        $this->request = $observer->getEvent()->getData('request');

        if ($this->canValidate()) {
            try {
                if (!$this->validator->isValid($this->getCfResponse())) {
                    $this->persistor?->persist($this->request, $this->action);
                    $this->error(
                        __('Security validation error: %1', join(', ', $this->validator->getErrorMessages()))
                    );
                }
            } catch (Exception $exception) {
                $this->error(__('Security validation error: %1', $exception->getMessage()));
            }
        }
    }

    /**
     * Retrieve CaptchaFox response
     *
     * @return string|null
     */
    public function getCfResponse(): ?string
    {
        $response = $this->request?->getParam('cf-captcha-response');

        return is_string($response) ? $response : null;
    }

    /**
     * Send error
     *
     * @param Phrase $message
     * @return void
     */
    protected function error(Phrase $message): void
    {
        $this->messageManager->addErrorMessage($message);
        $this->response->setRedirect($this->redirect->getRefererUrl());

        $this->response->sendResponse();
        exit();
    }

    /**
     * Can validate action
     *
     * @return bool
     */
    public function canValidate(): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }
        if (!$this->request?->isPost()) {
            return false;
        }

        foreach ($this->actions as $form => $instance) {
            if ($this->isFormEnabled($form) && is_a($this->action, $instance)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Test if the form is enabled
     *
     * @param string $form
     * @return bool
     */
    abstract public function isFormEnabled(string $form): bool;

    /**
     * Retrieve if validator is globally enabled
     *
     * @return bool
     */
    abstract public function isEnabled(): bool;
}
