<?php declare(strict_types=1);

namespace Custompricestock\Storefront\Controller;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductPageController extends AbstractController
{
    /**
     * @Route("/product/{productId}", name="frontend.product.detail", methods={"GET"})
     */
    public function detail(string $productId, SalesChannelContext $context): Response
    {
        $customer = $context->getCustomer();

        if ($customer) {
            // Customer is logged in
            $isLoggedIn = true;
            $customerId = $customer->getId();
        } else {
            // Customer is not logged in
            $isLoggedIn = false;
            $customerId = null;
        }

        // ... your product page logic ...

        return $this->renderStorefront('@Storefront/storefront/page/product-detail/index.html.twig', [
            'productId' => $productId,
            'isLoggedIn' => $isLoggedIn,
            'customerId' => $customerId,
        ]);
    }
}