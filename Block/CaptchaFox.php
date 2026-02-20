<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Block;

use CaptchaFox\Core\Helper\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Filter\FilterManager;

class CaptchaFox extends Template
{
    /**
     * Path to template file in theme.
     *
     * @var string $_template
     */
    protected $_template = 'ScoriaLabs_CaptchaFox::captchafox.phtml';

    protected FilterManager $filter;

    protected Config $config;

    /**
     * @param Context $context
     * @param FilterManager $filter
     * @param Config $config
     * @param mixed[] $data
     */
    public function __construct(
        Context $context,
        FilterManager $filter,
        Config $config,
        array $data = []
    ) {
        $this->filter = $filter;
        $this->config = $config;

        parent::__construct($context, $data);
    }

    /**
     * Retrieve action
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->getData('action') ?: $this->config->getAction();
    }

    /**
     * Retrieve mode, will override the config if set for block in layout
     *
     * @return string|null
     */
    public function getMode(): ?string
    {
        return $this->getData('mode');
    }

    /**
     * Retrieve language code.
     *
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->getData('language');
    }

    /**
     * Retrieve theme, will override the config if set for block in layout
     *
     * @return string|null
     */
    public function getTheme(): ?string
    {
        return $this->getData('theme');
    }

    /**
     * Retrieve id
     *
     * @return string
     */
    public function getId(): string
    {
        return 'captchafox-' . $this->filter->translitUrl($this->getAction());
    }
}
