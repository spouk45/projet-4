<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @Route("/register", name="registration", methods="GET|POST")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('user/register.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param AuthenticationUtils $helper
     * @return Response
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $helper): Response
    {

        return $this->render('Security/login.html.twig', [
            // dernier username saisi (si il y en a un)
            'last_username' => $helper->getLastUsername(),
            // La derniere erreur de connexion (si il y en a une)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     * @throws \Exception
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

//    /**
//     * @Route("/{id}", name="user_show", methods="GET")
//     */
//    public function show(User $user): Response
//    {
//        return $this->render('user/show.html.twig', ['user' => $user]);
//    }


    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @Route("/profil/edit", name="profil_edit", methods="GET|POST")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             $em = $this->getDoctrine()->getManager();
             $password = $passwordEncoder->encodePassword($user, $user->getPassword());
             $user->setPassword($password);
             $em->flush();

            return $this->redirectToRoute('profil_edit', ['id' => $user->getId()]);
        }

        return $this->render('user/profileEdit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="user_delete", methods="DELETE")
//     */
//    public function delete(Request $request, User $user): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
//            $em = $this->getDoctrine()->getManager();
//            $em->remove($user);
//            $em->flush();
//        }
//
//        return $this->redirectToRoute('user_index');
//    }
}
