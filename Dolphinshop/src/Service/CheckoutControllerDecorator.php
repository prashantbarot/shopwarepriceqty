<?php declare(strict_types=1);

namespace Custompricestock\Service;

use Shopware\Storefront\Controller\CheckoutController;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\Checkout\Confirm\CheckoutConfirmPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;



class CheckoutControllerDecorator extends CheckoutController
{
    /**
     * @var AbstractProductPriceCalculator
     */
    public function __construct(
        private readonly CheckoutController $decorated,
        private readonly CartService $cartService,
        private readonly CheckoutConfirmPageLoader $confirmPageLoader,
		private readonly EntityRepository $yourCustomEntityRepository,
    ) {
    }

    public function cartPage(Request $request, SalesChannelContext $context): Response
    {
        return $this->decorated->cartPage($request, $context);
    }

    public function cartJson(Request $request, SalesChannelContext $context): Response
    {
        return $this->decorated->cartJson($request, $context);
    }

    public function confirmPage(Request $request, SalesChannelContext $context): Response
    {
        return $this->decorated->confirmPage($request, $context);
    }   

    public function order(RequestDataBag $data, SalesChannelContext $context, Request $request): Response
    {
		$cart = $this->cartService->getCart($context->getToken(), $context);
		$lineItems = $cart->getLineItems();
		
		

		if ($lineItems !== null) {
			/** @var OrderLineItemEntity $lineItem */
			foreach ($lineItems as $lineItem) {
				if ($lineItem->getType() === 'product') { 
					
					//print_r($lineItem);
					//$lineItem->getPayload();					
					$findstocks = $this->getCustomCollection($lineItem->getPayload()['productNumber']);
					if(count($findstocks) > 0){
						foreach ($findstocks as $findstock) {
							if($findstock->getQuantity() == $lineItem->getQuantity() || $findstock->getQuantity() > $lineItem->getQuantity()){
								$this->addFlash(self::DANGER, "Availabel quantity placed order !!!");
								return $this->redirectToRoute('frontend.checkout.cart.page');				
							}else{
								$this->addFlash(self::DANGER, "Availabel quantity less then your placed order !!!");
								return $this->redirectToRoute('frontend.checkout.cart.page');	
							}
						}
					}
				}
			}
		}
		
		
    }
    public function offcanvas(Request $request, SalesChannelContext $context): Response
    {
        return $this->decorated->offcanvas($request, $context);
    }
	public function getCustomCollection($productnumber): array
    {
        $criteria = new Criteria();
		$productnumber." function </br>";
        // Add filters, sorting, associations if needed
        $criteria->addFilter(new EqualsFilter('sku', $productnumber));		

        $context = Context::createDefaultContext(); // Or use a specific context
        $collection = $this->yourCustomEntityRepository->search($criteria, $context)->getEntities();

        return $collection->getElements(); // Returns an array of your custom entities
    }
}