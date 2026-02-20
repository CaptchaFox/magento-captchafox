<?php

\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'ScoriaLabs_CaptchaFox',
    isset($file) ? dirname($file) : __DIR__
);
