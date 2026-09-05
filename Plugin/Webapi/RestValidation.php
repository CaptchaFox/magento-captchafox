<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Plugin\Webapi;

use Magento\Framework\Webapi\Exception as WebapiException;
use Magento\Framework\Webapi\Rest\Request as RestRequest;
use Magento\Webapi\Controller\Rest\RequestValidator;
use Magento\Webapi\Controller\Rest\Router;
use CaptchaFox\Core\Model\Webapi\Validate;

/**
 * Enable CaptchaFox validation for the RESTful web API.
 */
class RestValidation
{
    protected Validate $validate;

    protected RestRequest $request;

    protected Router $router;

    /**
     * @param Validate $validate
     * @param RestRequest $request
     * @param Router $router
     */
    public function __construct(
        Validate $validate,
        RestRequest $request,
        Router $router
    ) {
        $this->validate = $validate;
        $this->request  = $request;
        $this->router   = $router;
    }

    /**
     * Validate CaptchaFox for the matched route if needed
     *
     * @param RequestValidator $subject
     * @param callable $proceed
     * @return void
     * @throws WebapiException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundValidate(RequestValidator $subject, callable $proceed): void
    {
        // The router consumes the request while matching, so keep a copy taken before the
        // original validation runs.
        $request = clone $this->request;

        $proceed();

        $route = $this->router->match($request);

        $error = $this->validate->getError(
            (string)$route->getServiceClass(),
            (string)$route->getServiceMethod(),
            (string)$this->request->getHeader(Validate::HEADER_NAME)
        );

        if ($error !== null) {
            throw new WebapiException($error);
        }
    }
}
