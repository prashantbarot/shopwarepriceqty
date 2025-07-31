<?php declare(strict_types=1);

namespace Custompricestock\Core\Content\Warehouse_stock;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class Warehouse_stockEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $sku;

    protected ?string $warehouse_id;

    protected ?Int $quantity;

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): void
    {
        $this->sku = $sku;
    }

    public function getWarehouseId(): ?string
    {
        return $this->warehouse_id;
    }

    public function SetWarehouseId(?string $warehouse_id): void
    {
        $this->warehouse_id = $warehouse_id;
    }

    public function getQuantity(): ?Int 
    {
        return $this->quantity;
    }

    public function setQuantity(?Int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
