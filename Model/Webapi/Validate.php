<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Model\Webapi;

use Exception;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Phrase;
use CaptchaFox\Core\Helper\Config;
use CaptchaFox\Core\Model\Validator;

/**
 * Shared CaptchaFox validation for the REST and GraphQL web APIs.
 */
class Validate
{
    /**
     * Request header carrying the CaptchaFox response for web API calls
     */
    public const HEADER_NAME = 'X-CaptchaFox';

    protected Config $config;

    protected Validator $validator;

    protected EndpointConfig $endpoints;

    protected UserContextInterface $userContext;

    /**
     * @param Config $config
     * @param Validator $validator
     * @param EndpointConfig $endpoints
     * @param UserContextInterface $userContext
     */
    public function __construct(
        Config $config,
        Validator $validator,
        EndpointConfig $endpoints,
        UserContextInterface $userContext
    ) {
        $this->config      = $config;
        $this->validator   = $validator;
        $this->endpoints   = $endpoints;
        $this->userContext = $userContext;
    }

    /**
     * Validate an endpoint call, returning an error message when it must be rejected
     *
     * @param string $serviceClass
     * @param string $serviceMethod
     * @param string $token
     * @return Phrase|null
     */
    public function getError(string $serviceClass, string $serviceMethod, string $token): ?Phrase
    {
        if (!$this->config->isEnabledOnFront()) {
            return null;
        }

        $form = $this->endpoints->getFormFor($serviceClass, $serviceMethod);
        if ($form === null || !in_array($form, $this->config->getFrontendForms(), true)) {
            return null;
        }

        if ($this->isExempt()) {
            return null;
        }

        try {
            if ($this->validator->isValid($token !== '' ? $token : null)) {
                return null;
            }

            return __('Security validation error: %1', join(', ', $this->validator->getErrorMessages()));
        } catch (Exception $exception) {
            return __('Security validation error: %1', $exception->getMessage());
        }
    }

    /**
     * Test if the caller is exempt from validation
     *
     * @return bool
     */
    protected function isExempt(): bool
    {
        $userType = $this->userContext->getUserType();

        if ($userType === UserContextInterface::USER_TYPE_INTEGRATION
            || $userType === UserContextInterface::USER_TYPE_ADMIN
        ) {
            return true;
        }

        return $userType === UserContextInterface::USER_TYPE_CUSTOMER
            && $this->config->isSkippedForLoggedInCustomers();
    }
}
