<?php declare(strict_types=1);

namespace Custompricestock\Service;

use Shopware\Storefront\Controller\CheckoutController;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\Checkout\Confirm\CheckoutConfirmPageLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckoutControllerDecorator extends CheckoutController
{
    /**
     * @var AbstractProductPriceCalculator
     */
    public function __construct(
        private readonly CheckoutController $decorated,
        private readonly CartService $cartService,
        private readonly CheckoutConfirmPageLoader $confirmPageLoader,
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
		$this->addFlash(self::DANGER, "Availabel quantity less then your placed order !!!");
        return $this->redirectToRoute('frontend.checkout.cart.page');
    }
    public function offcanvas(Request $request, SalesChannelContext $context): Response
    {
        return $this->decorated->offcanvas($request, $context);
    }
}