<?php declare(strict_types=1);

namespace Custompricestock\Service;

use Shopware\Core\Content\Product\SalesChannel\Price\AbstractProductPriceCalculator;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;

class CustomProductPriceCalculator extends AbstractProductPriceCalculator
{
    /**
     * @var AbstractProductPriceCalculator
     */
    private AbstractProductPriceCalculator $productPriceCalculator;
	private EntityRepository $yourCustomEntityRepository;

    public function __construct(AbstractProductPriceCalculator $productPriceCalculator, EntityRepository $yourCustomEntityRepository)
    {
        $this->productPriceCalculator = $productPriceCalculator;
		$this->yourCustomEntityRepository = $yourCustomEntityRepository;
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
				$findprices = $this->getCustomCollection($product->getProductNumber(),$customerId);
				
				if(count($findprices) > 0){	
					$price = $product->getPrice();
					foreach ($findprices as $findprice) {
						$newprice =  $findprice->getPrice();
					}
					//echo $product->getProductNumber()."</br>";
					
					// Just an example!
					// A product can have more than one price, which you also have to consider.
					// Also you might have to change the value of "getCheapestPrice"!
					if($newprice != ""){
						$price->first()->setGross($newprice);		
					}
				}
			}
			$this->getDecorated()->calculate($products, $context);			
		}else{
			$this->getDecorated()->calculate($products, $context);
		}
		
    }
	
	public function getCustomCollection($productnumber,$customerId): array
    {
        $criteria = new Criteria();
		//echo $productnumber." function </br>";
        // Add filters, sorting, associations if needed
         $criteria->addFilter(new EqualsFilter('sku', $productnumber));
		 $criteria->addFilter(new EqualsFilter('customer_id', $customerId));
		 
		

        $context = Context::createDefaultContext(); // Or use a specific context
        $collection = $this->yourCustomEntityRepository->search($criteria, $context)->getEntities();


        return $collection->getElements(); // Returns an array of your custom entities
    }
}