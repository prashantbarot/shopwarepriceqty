<?php declare(strict_types=1);

namespace Custompricestock\Core\Content\Custom_prices;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;

class Custom_pricesDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'custom_prices';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return Custom_pricesEntity::class;
    }

    public function getCollectionClass(): string
    {
        return Custom_pricesCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new StringField('sku', 'sku')),
            (new IdField('customer_id', 'customer_id')),
            (new FloatField('price', 'price'))
        ]);
    }
}
