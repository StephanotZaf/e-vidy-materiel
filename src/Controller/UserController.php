<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\RoleUserType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin-compte-d-utilisateur")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user,RoleRepository $roleRepository): Response
    {
        $form = $this->createForm(RoleUserType::class, $user);
        $form->handleRequest($request);
        $roleOneUser = $user->getRoles();

        if ($form->isSubmitted() && $form->isValid()) {
            $nomRole = $form->get('roleUser')->getData()->getNomRole();
            $id = $form->get('roleUser')->getData()->getId();
            $role = $roleRepository->find($id);
            try
            {
                $user->addRole($role);
            }
            catch(\Throwable $e)
            {
                $this->addFlash('warning',$e->getMessage());
            }

            if(in_array($nomRole,$roleOneUser)) {
                $this->addFlash('danger','Cet utitlisateur a déjà ce rôle');
                return $this->redirectToRoute('user_index');
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete")
     */
    public function delete(User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('user_index');
    }
}
