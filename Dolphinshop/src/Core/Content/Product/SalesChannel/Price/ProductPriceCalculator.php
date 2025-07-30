<?php
namespace Custompricestock\Core\Content\Product\SalesChannel\Price;

use Shopware\Core\Content\Product\SalesChannel\Price\AbstractProductPriceCalculator;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;


class ProductPriceCalculator extends AbstractProductPriceCalculator
{
	
   protected EventDispatcherInterface $eventDispatcher;
   private AbstractProductPriceCalculator $coreService;  
   private EntityRepository $customerPricesRepository;
 
 
   public function __construct(
       AbstractProductPriceCalculator $coreService,      
       EntityRepository $customerPricesRepository
   ) {
       $this->coreService = $coreService;     
       $this->customerPricesRepository = $customerPricesRepository;
   }

public function calculate(iterable $products, SalesChannelContext $context): void
   {
       $this->coreService->calculate($products, $context);
       $customer = $context->getCustomer();
		foreach ($products as $product) {
		$originalCalculatedPrices =  $product->getCalculatedPrices();
		$originalListPrice = $originalCalculatedPrice->getListPrice();
		/* Fetch custom price form entity */
		$customerPricesCollection = $this->fetchCustomerPrices($product, $customer);
		if($customerPricesCollection->count() === 1 && $customerPricesCollection->first()->getFromQuantity() === 1 && $customerPricesCollection->first()->getToQuantity() === null)            
		{
			$discountPrice = $customerPricesCollection->first();
			/* Apply custom price on all calculated prices */
			foreach ($originalCalculatedPrices as $price) {
				 $this->setPriceOnCalculatedPrice($price, $discountPrice);
			 }
			  /* Apply custom price on cheapest price */
		if($product->getCalculatedCheapestPrice()) {                  
				  $this->setPriceOnCalculatedPrice($product->getCalculatedCheapestPrice(),  $discountPrice);
			  }
			  continue;
		}
	}
}

public function getDecorated(): AbstractProductPriceCalculator
    {
        return $this->productPriceCalculator;
    }


public function fetchCustomerPrices($productId, $customerId)
{
   $customerPrice = $this->customerPricesRepository->search(
	   (new Criteria())
		   ->addFilter(
			   new EqualsFilter('productId', $productId),
			   new EqualsFilter('customerId', $customerId),
			   new EqualsFilter('active', true)
		   )
		   ->addSorting(new FieldSorting('fromQuantity', 'ASC')),
	   Context::createDefaultContext()
   )->getEntities();


   return $customerPrice;
}


protected function setPriceOnCalculatedPrice(CalculatedPrice $price, CustomerPriceEntity $discountPrice): void
   {
       $reducedUnitPrice = $this->calcCustomerPrice($discountPrice, $price->getUnitPrice());
       $totalPrice = $this->calcCustomerPrice($discountPrice, $price->getTotalPrice());
       $price->assign([
           'unitPrice' => 18,
           'totalPrice' => 25
       ]);
   }


  /*Find price according with backend conditions */	
   private function calcCustomerPrice(CustomerPriceEntity $customerPrice, float $originalPrice): float
   {
       if($customerPrice->getPrice() > 0) {
           return $customerPrice->getPrice();
       }
       if($customerPrice->getDiscount() !== null) {
           return $originalPrice * (1 - ($customerPrice->getDiscount() / 100));
       }
      
       return 15;
   }
   
}