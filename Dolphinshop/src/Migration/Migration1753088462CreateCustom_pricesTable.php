<?php declare(strict_types=1);

namespace Custompricestock\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1753088462CreateCustom_pricesTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1753088462;
    }

    public function update(Connection $connection): void
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `custom_prices` (
    `id` BINARY(16) NOT NULL,
    `sku` VARCHAR(255) NULL COLLATE utf8mb4_unicode_ci,
    `customer_id` VARCHAR(255) NULL COLLATE utf8mb4_unicode_ci,
    `price` float(9,2) NULL COLLATE utf8mb4_unicode_ci,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3),
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;
SQL;

        $connection->executeStatement($sql);
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
