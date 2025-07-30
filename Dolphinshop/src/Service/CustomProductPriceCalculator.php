<?php declare(strict_types=1);

namespace Custompricestock\Service;

use Shopware\Core\Content\Product\SalesChannel\Price\AbstractProductPriceCalculator;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Checkout\Customer\CustomerEntity;

class CustomProductPriceCalculator extends AbstractProductPriceCalculator
{
    /**
     * @var AbstractProductPriceCalculator
     */
    private AbstractProductPriceCalculator $productPriceCalculator;

    public function __construct(AbstractProductPriceCalculator $productPriceCalculator)
    {
        $this->productPriceCalculator = $productPriceCalculator;
    }

    public function getDecorated(): AbstractProductPriceCalculator
    {
        return $this->productPriceCalculator;
    }

    public function calculate(iterable $products, SalesChannelContext $context): void
    {
		$customer = $context->getCustomer();
		if ($customer instanceof CustomerEntity) {
			if($customer->getGuest()){
				$customerId	= "";
			}else{
			$customerId	= $customer->getId();    
			}
		} else {
			$customerId = "";
		}		
	
		if($customerId != ""){
			/** @var SalesChannelProductEntity $product */
			foreach ($products as $product) {
				$price = $product->getPrice();
				// Just an example!
				// A product can have more than one price, which you also have to consider.
				// Also you might have to change the value of "getCheapestPrice"!
				$price->first()->setGross(25);
				$price->first()->setNet(12.5);
			}

			$this->getDecorated()->calculate($products, $context);
			
		}else{
			$this->getDecorated()->calculate($products, $context);
		}
		
    }
}