<?php declare(strict_types=1);

namespace Custompricestock\Core\Content\Custom_prices;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void add(Custom_pricesEntity $entity)
 * @method void set(string $key, Custom_pricesEntity $entity)
 * @method Custom_pricesEntity[] getIterator()
 * @method Custom_pricesEntity[] getElements()
 * @method Custom_pricesEntity|null get(string $key)
 * @method Custom_pricesEntity|null first()
 * @method Custom_pricesEntity|null last()
 */
class Custom_pricesCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return Custom_pricesEntity::class;
    }
}
