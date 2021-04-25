<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\MaterielRepository;
use App\Repository\CategorieRepository;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", name="client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/nouveau", name="client_new", methods={"GET","POST"})
     */
    public function new(Request $request,MaterielRepository $materielRepository,
    CategorieRepository $categorieRepository,
    DepartementRepository $departementRepository): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager(); $qte = $form->get('materiel')->getData()->getQte() - $form->get('qteAchete')->getData();
            // dd($qte);

            // dd($form->getData());
            $id = $form->get('materiel')->getData()->getId();
            $materiels = $materielRepository->find($id);
            // dd($materiels);
            // dd($materiels->getCategorie()->getId());

            $dateApprovisionnement = $materiels->getDateApprovisionnement();
            $designation = $materiels->getDesignation();
            $prixUnitaire = $materiels->getPrixUnitaire();
            $type = $materiels->getType();
            $categorie = $materiels->getCategorie()->getId();
            $departement = $materiels->getDepartement()->getId();
            // dd($departement);

            $categories = $categorieRepository->find($categorie);
            // dd($categories);

            $departements = $departementRepository->find($departement);
            // dd($departements);

            $materiels->setQte($qte);
            $materiels->setDateApprovisionnement($dateApprovisionnement);
            $materiels->setDesignation($designation);
            $materiels->setPrixUnitaire($prixUnitaire);
            $materiels->setType($type);
            $materiels->setCategorie($categories);
            $materiels->setDepartement($departements);
            $entityManager->persist($materiels);
            
            

            $entityManager->persist($client);
            $this->addFlash('success','Client ajouté avec succès');
            $entityManager->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{code}", name="client_show", methods={"GET"})
     */
    public function show(Client $client,ClientRepository $clientRepository): Response
    {
        
        $achats = $clientRepository->findByCode($client->getCode());
        // dd($achats);

        //  $total = 0;

        // foreach($this->getFullCart() as $item){
        //     $totalItem = $item['product']->prixUnitaire() * $item['quantity'];
        //     $total += $totalItem;
        // }
        // $clients = $clientRepository->findOneByCode();
        $total = 0;
        foreach($clientRepository as $client) {
            $totalClient = $client['materiel']->getPrixUnitaire() * $client['quantitee'];
            $total += $totalClient;
        }
        return $this->render('client/show.html.twig', [
            'achats' => $achats,
            'client' => $client,
        ]);
        
    }

    /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success','Catégorie modifié avec succès');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="client_delete")
     */
    public function delete(Request $request, Client $client): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($client);
        $this->addFlash('success','Client supprimé avec succès');
        $entityManager->flush();

        return $this->redirectToRoute('client_index');
    }
}
