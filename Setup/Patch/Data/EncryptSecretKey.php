<?php
/**
 * Copyright (C) 2026 Scoria Labs GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace CaptchaFox\Core\Setup\Patch\Data;

use CaptchaFox\Core\Helper\Config;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Encrypt secret keys that were saved before the field had an encrypted backend model
 *
 * Only stored values are migrated. A key supplied through env.php or a CONFIG__ environment
 * variable is not in this table and stays readable through the plain text fallback in the
 * configuration helper.
 */
class EncryptSecretKey implements DataPatchInterface
{
    protected ResourceConnection $resource;

    protected EncryptorInterface $encryptor;

    /**
     * @param ResourceConnection $resource
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        ResourceConnection $resource,
        EncryptorInterface $encryptor
    ) {
        $this->resource  = $resource;
        $this->encryptor = $encryptor;
    }

    /**
     * @inheritdoc
     */
    public function apply(): self
    {
        $connection = $this->resource->getConnection();
        $table      = $this->resource->getTableName('core_config_data');

        $values = $connection->fetchPairs(
            $connection->select()
                ->from($table, ['config_id', 'value'])
                ->where('path = ?', Config::CAPTCHAFOX_CONFIG_PATH_SECRET_KEY)
        );

        foreach ($values as $configId => $value) {
            $value = (string)$value;

            // Encrypted values are prefixed with their key and cipher version, e.g. "0:3:<base64>".
            if ($value === '' || preg_match('/^\d+:\d+:/', $value)) {
                continue;
            }

            $connection->update(
                $table,
                ['value' => $this->encryptor->encrypt($value)],
                ['config_id = ?' => $configId]
            );
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
