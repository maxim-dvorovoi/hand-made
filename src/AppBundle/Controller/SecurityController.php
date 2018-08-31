<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $auth = $this->getUser();
        if ($auth){
            $auth = $auth->getRoles();
            if ($auth[0] == 'ROLE_USER'){
                return $this->redirectToRoute('login_homepage');
            }
        }

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
    public function loginAdminAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $auth = $this->getUser();
        if ($auth){
            $auth = $auth->getRoles();
            if ($auth[0] == 'ROLE_USER'){
                return $this->redirectToRoute('login_homepage');
            }
        }

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
}