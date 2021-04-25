<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Materiel;
use App\Entity\MaterielEntree;
use App\Form\MaterielEntreeType;
use App\Repository\MaterielRepository;
use App\Repository\CategorieRepository;
use App\Repository\DepartementRepository;
use App\Repository\MaterielEntreeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/materiel-entree")
 */
class MaterielEntreeController extends AbstractController
{
    /**
     * @Route("/", name="materiel_entree_index", methods={"GET"})
     */
    public function index(MaterielEntreeRepository $materielEntreeRepository): Response
    {
        return $this->render('materiel_entree/index.html.twig', [
            'materiel_entrees' => $materielEntreeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/nouveau", name="materiel_entree_new", methods={"GET","POST"})
     */
    public function new(Request $request,
                        MaterielRepository $materielRepository,
                        CategorieRepository $categorieRepository,
                        DepartementRepository $departementRepository): Response
    {
        $materielEntree = new MaterielEntree();
        $form = $this->createForm(MaterielEntreeType::class, $materielEntree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $qte = $form->get('materiel')->getData()->getQte() + $form->get('qteEntree')->getData();
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
            $entityManager->persist($materielEntree);

            // dd($materielEntree);

            $this->addFlash('success','Matériel ajouté avec succès');
            $entityManager->flush();
            return $this->redirectToRoute('materiel_entree_index');
        }

        return $this->render('materiel_entree/new.html.twig', [
            'materiel_entree' => $materielEntree,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="materiel_entree_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MaterielEntree $materielEntree): Response
    {
        $form = $this->createForm(MaterielEntreeType::class, $materielEntree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success','Matériel modifié avec succès');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('materiel_entree_index');
        }

        return $this->render('materiel_entree/edit.html.twig', [
            'materiel_entree' => $materielEntree,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="materiel_entree_delete")
     */
    public function delete(MaterielEntree $materielEntree): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($materielEntree);
        $this->addFlash('success','Matériel entrée supprimé avec succès');
        $entityManager->flush();

        return $this->redirectToRoute('materiel_entree_index');
    }
}
