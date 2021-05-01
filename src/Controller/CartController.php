<?php

namespace App\Controller;

use App\Entity\Client;
use App\Service\CartService;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    private $manager;

    public function __construct(SessionInterface $session,
                                CartService $cartService,
                                MaterielRepository $materielRepository,
                                EntityManagerInterface $manager)
    {
        $this->session = $session;
        $this->cartService = $cartService;
        $this->materielRepository = $materielRepository;
        $this->manager = $manager;
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

    /**
     * @Route("/cart/valid", name="cart_valid_purchase")
     */
    public function validPurchase(Request $request)
    {
        //    dd($request->request->all());

        $quantities = $request->request->get("quantity");
        $materiel_id = $request->request->get("materiel_id");
        $nom = $request->request->get("nom");

        // dd($nom);

        $client = new Client();

        for ($m=0; $m < count($materiel_id); $m++) { 
            $materiel = $this->materielRepository->find($materiel_id[$m]);
            $client->setMateriel($materiel);
            $this->manager->persist($materiel);

        }
        
        for ($q=0; $q < count($quantities); $q++) { 
            $client->setQteAchete($quantities[$q]);
        }

        for ($n=0; $n < count($nom); $n++) { 
            $client->setNomCli($nom[$n]);
        }

        $this->manager->persist($client);
        dd($client);

        // $this->manager->flush();
    }

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
