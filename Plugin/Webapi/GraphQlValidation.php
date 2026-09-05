<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Plugin\Webapi;

use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use CaptchaFox\Core\Model\Webapi\Validate;

/**
 * Enable CaptchaFox validation for GraphQL mutations.
 */
class GraphQlValidation
{
    protected Validate $validate;

    protected Request $request;

    /**
     * @param Validate $validate
     * @param Request $request
     */
    public function __construct(
        Validate $validate,
        Request $request
    ) {
        $this->validate = $validate;
        $this->request  = $request;
    }

    /**
     * Validate CaptchaFox for the resolved mutation if needed
     *
     * @param ResolverInterface $subject
     * @param Field $field
     * @param mixed $context
     * @param ResolveInfo $info
     * @return void
     * @throws GraphQlInputException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeResolve(
        ResolverInterface $subject,
        Field $field,
        $context,
        ResolveInfo $info
    ): void {
        if (($info->operation->operation ?? null) !== 'mutation') {
            return;
        }

        $error = $this->validate->getError(
            (string)$field->getResolver(),
            'resolve',
            (string)$this->request->getHeader(Validate::HEADER_NAME)
        );

        if ($error !== null) {
            throw new GraphQlInputException($error);
        }
    }
}
