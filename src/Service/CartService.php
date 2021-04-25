<?php

namespace App\Service;

use App\Repository\MaterielRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService 
{
    private $session;
    private $materielRepository;
    
    public function __construct(SessionInterface $session,MaterielRepository $materielRepository)
    {
        $this->session = $session;
        $this->materielRepository = $materielRepository;
    }
    
    public function add(int $id)
    {
        $panier = $this->session->get('panier',[]);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }
        else{
            $panier[$id] = 1;
        }
        $this->session->set('panier',$panier);
    }

    public function decreaseQuantity(int $id)
    {
        $panier = $this->session->get('panier',[]);
        if(!empty($panier[$id])){
            if($panier[$id] > 0){
                $panier[$id]--;
            }
        }
        $this->session->set('panier',$panier);
    }
    
    public function remove(int $id)
    {
        $panier = $this->session->get('panier',[]);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $this->session->set('panier',$panier);
    }
    
    public function getFullCart():array
    {
        $panier = $this->session->get('panier',[]);
        $panierWithData = [];
        
        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'materiel' => $this->materielRepository->find($id),
                'quantity' => $quantity
            ]; 
        }

        return $panierWithData;
    }

    // public function getCart()
    // {
    //     $panier = $this->session->get('panier',[]);
    //     $panierWithDataa = [];
        
    //     foreach ($panier as $id => $quantity) {
    //         $panierWithDataa['materiel'] = $this->materielRepository->find($id);
    //         $panierWithDataa['quantity'] = $quantity;
    //     }

    //     return $panierWithDataa;
    // }
    
    public function getTotal():float
    {
        $total = 0;

        foreach ($this->getFullCart() as $item) {
            $totalItem = $item['materiel']->getPrixUnitaire() *  $item['quantity'];
            $total += $totalItem;
        }

        return $total;
    }
}

