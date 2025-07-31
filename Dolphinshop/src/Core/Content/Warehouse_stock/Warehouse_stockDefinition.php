<?php declare(strict_types=1);

namespace Custompricestock\Core\Content\Warehouse_stock;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;

class Warehouse_stockDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'warehouse_stock';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return Warehouse_stockEntity::class;
    }

    public function getCollectionClass(): string
    {
        return Warehouse_stockCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new StringField('sku', 'sku')),
            (new StringField('warehouse_id', 'warehouse_id')),
            (new IntField('quantity', 'quantity'))
        ]);
    }
}
