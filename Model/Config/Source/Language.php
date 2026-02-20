<?php
namespace CaptchaFox\Core\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Language
 * @package CaptchaFox\Core\Model\Config\Source
 */
class Language implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $languageOptionArray = [
            ['label' => __('-- Auto Detected --'), 'value' => 'auto'],
            ['label' => __('Chinese (Simplified)'), 'value' => 'zh-cn'],
            ['label' => __('Chinese (Traditional)'), 'value' => 'zh-tw'],
            ['label' => __('Czech'), 'value' => 'cs'],
            ['label' => __('Danish'), 'value' => 'da'],
            ['label' => __('Dutch'), 'value' => 'nl'],
            ['label' => __('English'), 'value' => 'en'],
            ['label' => __('Finnish'), 'value' => 'fi'],
            ['label' => __('French'), 'value' => 'fr'],
            ['label' => __('German'), 'value' => 'de'],
            ['label' => __('Indonesian'), 'value' => 'id'],
            ['label' => __('Irish'), 'value' => 'ga'],
            ['label' => __('Italian'), 'value' => 'it'],
            ['label' => __('Japanese'), 'value' => 'ja'],
            ['label' => __('Korean'), 'value' => 'ko'],
            ['label' => __('Norwegian'), 'value' => 'no'],
            ['label' => __('Portuguese'), 'value' => 'pt'],
            ['label' => __('Polish'), 'value' => 'pl'],
            ['label' => __('Russian'), 'value' => 'ru'],
            ['label' => __('Spanish'), 'value' => 'es'],
            ['label' => __('Swedish'), 'value' => 'sv'],
            ['label' => __('Turkish'), 'value' => 'tr'],
            ['label' => __('Ukrainian'), 'value' => 'uk'],
        ];

        return $languageOptionArray;
    }
}
