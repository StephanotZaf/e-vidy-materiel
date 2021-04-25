<?php

namespace App\Controller;

use App\Service\CartService;
use App\Repository\MaterielRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private $session;
    private $cartService;
    private $materielRepository;

    public function __construct(SessionInterface $session,
                                CartService $cartService,
                                MaterielRepository $materielRepository)
    {
        $this->session = $session;
        $this->cartService = $cartService;
        $this->materielRepository = $materielRepository;
    }
    

    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(): Response
    {
        $panierWithData = $this->cartService->getFullCart();
        $total = $this->cartService->getTotal();

        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    // /**
    //  * @Route("/cart/valid", name="cart_valid_purchase")
    //  */
    // public function validPurchase(Request $request): Response
    // {
    //     return $this->json($this->cartService->getFullCart(),201,[],['groups' => "materiel_read"]);
    // }

    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add($id)
    {
        $this->cartService->add($id);
        
        return $this->redirectToRoute("materiel_index");
    }

    /**
     * @Route("/cart/increase/{id}", name="cart_increase")
     */
    public function increase($id)
    {
        $this->cartService->add($id);
        
        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/cart/decrease/{id}", name="cart_decrease")
     */
    public function decrease($id)
    {
        $this->cartService->decreaseQuantity($id);
        
        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function remove($id)
    {
        $this->cartService->remove($id);

        return $this->redirectToRoute("materiel_index");
    }
}
