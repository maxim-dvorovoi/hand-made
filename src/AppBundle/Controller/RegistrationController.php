<?php
namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        if ($this->getUser()) {
            $auth = $this->getUser()->getRoles();
            if (in_array('ROLE_USER', $auth)) return $this->redirectToRoute('login_homepage');
        }

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $password = $passwordEncoder->encodePassword($user , $user->getPlainPassword());
            $username = $user->getUsername();
            $email = $user->getEmail();

            $user->setPassword($password);
            $user->setUsername($username);
            $user->setEmail($email);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            $entityManager->flush();


            return $this->redirectToRoute('login');
        }

        return $this->render('@App/registration/registr.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }
}