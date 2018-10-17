<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        $this->auth();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@App/security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'categories' => $categories,
        ));
    }

    /**
     * @Route("/admin/login", name="adminslogin")
     */
    public function loginAdminAction(AuthenticationUtils $authenticationUtils)
    {
        $this->auth();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@App/security/admins.login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'categories' => $categories,
        ));
    }

    private function auth()
    {
        if ($this->getUser()) {
            $auth = $this->getUser()->getRoles();
            if (in_array('ROLE_USER', $auth)) return $this->redirectToRoute('login_homepage');
        }
    }
}