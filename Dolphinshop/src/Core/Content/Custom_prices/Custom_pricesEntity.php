<?php declare(strict_types=1);

namespace Custompricestock\Core\Content\Custom_prices;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class Custom_pricesEntity extends Entity
{
    use EntityIdTrait;

    protected ?string $sku;

    protected ?string $customer_id;

    protected ?Float $price;

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): void
    {
        $this->sku = $sku;
    }

    public function getCustomerId(): ?string
    {
        return $this->customer_id;
    }

    public function setCustomerId(?string $customer_id): void
    {
        $this->customer_id = $customer_id;
    }

    public function getPrice(): ?Float 
    {
        return $this->price;
    }

    public function setPrice(?Float $price): void
    {
        $this->price = $price;
    }
}
