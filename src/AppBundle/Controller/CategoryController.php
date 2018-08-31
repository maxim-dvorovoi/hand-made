<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/category/new/{id}", name="category_item")
     * @Template()
     */
    public function showAction($id,Request $request)
    {
        $auth = $this->getUser();
        if ($auth){
            $auth = $auth->getRoles();
            if ($auth[0] == 'ROLE_USER'){
                return $this->redirectToRoute('login_homepage');
            }
        }

        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p WHERE p.category=$id ORDER BY p.id DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return [
            'category' => $category,
            'pagination' => $pagination,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => $count,
        ];
    }

    /**
     * @Route("/category/top/{id}", name="category_top_item")
     */
    public function showTopAction($id,Request $request)
    {
        $auth = $this->getUser();
        if ($auth){
            $auth = $auth->getRoles();
            if ($auth[0] == 'ROLE_USER'){
                return $this->redirectToRoute('login_homepage');
            }
        }

        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p WHERE p.category=$id ORDER BY p.views DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return $this->render('@App/category/show_top.html.twig', [
            'category' => $category,
            'pagination' => $pagination,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => $count,
        ]);
    }

    /**
     * @Route("/category/all/new", name="category_all_new_item")
     */
    public function showAllNewAction(Request $request)
    {
        $auth = $this->getUser();
        if ($auth){
            $auth = $auth->getRoles();
            if ($auth[0] == 'ROLE_USER'){
                return $this->redirectToRoute('login_homepage');
            }
        }

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p  ORDER BY p.id DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return $this->render('@App/category/show_all_new.html.twig',[
            'categories' => $categories,
            'pagination' => $pagination,
            'cookProduct' => $cookProduct,
            'count' => $count,
        ]);
    }

    /**
     * @Route("/category/all/top", name="category_all_top_item")
     */
    public function showAllTopAction(Request $request)
    {
        $auth = $this->getUser();
        if ($auth){
            $auth = $auth->getRoles();
            if ($auth[0] == 'ROLE_USER'){
                return $this->redirectToRoute('login_homepage');
            }
        }

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p  ORDER BY p.views DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return $this->render('@App/category/show_all_top.html.twig',[
            'categories' => $categories,
            'pagination' => $pagination,
            'cookProduct' => $cookProduct,
            'count' => $count,
        ]);
    }

    /**
     * @Route("/user/category/new/{id}", name="login_category_item")
     */
    public function loginAction($id,Request $request)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p WHERE p.category=$id ORDER BY p.id DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return $this->render('@App/login/category/show.html.twig', [
            'category' => $category,
            'pagination' => $pagination,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => $count,
        ]);
    }

    /**
     * @Route("/user/category/top/{id}", name="login_category_top_item")
     */
    public function showToploginAction($id,Request $request)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p WHERE p.category=$id ORDER BY p.views DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return $this->render('@App/login/category/show_top.html.twig', [
            'category' => $category,
            'pagination' => $pagination,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => $count,


        ]);
    }

    /**
     * @Route("/user/category/all/new", name="login_category_all_new_item")
     */
    public function showAllNewloginAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p  ORDER BY p.id DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return $this->render('@App/login/category/show_all_new.html.twig',[
            'categories' => $categories,
            'pagination' => $pagination,
            'cookProduct' => $cookProduct,
            'count' => $count,

        ]);
    }

    /**
     * @Route("/user/category/all/top", name="login_category_all_top_item")
     */
    public function showAllToploginAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p  ORDER BY p.views DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return $this->render('@App/login/category/show_all_top.html.twig',[
            'categories' => $categories,
            'pagination' => $pagination,
            'cookProduct' => $cookProduct,
            'count' => $count,

        ]);
    }

    /**
     * @Route("/admin/category/new/{id}", name="admin_category_item")
     */
    public function adminAction($id,Request $request)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p WHERE p.category=$id ORDER BY p.id DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return $this->render('@App/admin/category/show.html.twig', [
            'category' => $category,
            'pagination' => $pagination,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => $count,

        ]);
    }

    /**
     * @Route("/admin/category/top/{id}", name="admin_category_top_item")
     */
    public function showTopAdminAction($id,Request $request)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p WHERE p.category=$id ORDER BY p.views DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }


        return $this->render('@App/admin/category/show_top.html.twig', [
            'category' => $category,
            'pagination' => $pagination,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => $count,

        ]);
    }

    /**
     * @Route("/admin/category/all/new", name="admin_category_all_new_item")
     */
    public function showAllNewAdminAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p  ORDER BY p.id DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return $this->render('@App/admin/category/show_all_new.html.twig',[
            'categories' => $categories,
            'pagination' => $pagination,
            'cookProduct' => $cookProduct,
            'count' => $count,
        ]);
    }

    /**
     * @Route("/admin/category/all/top", name="admin_category_all_top_item")
     */
    public function showAllTopAdminAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT p FROM AppBundle:Product p  ORDER BY p.views DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        $cookProduct = [];
        $count = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');
            $i = 0;
            foreach ($arrId as $value) {
                $cookProduct [$i] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
                $i++;
            }
            $count = count($cookProduct);
        }

        return $this->render('@App/admin/category/show_all_top.html.twig',[
            'categories' => $categories,
            'pagination' => $pagination,
            'cookProduct' => $cookProduct,
            'count' => $count,
        ]);
    }

    /**
     * @Route("/admin/category/edit", name="admin_category_edit")
     */
    public function editAdminAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if (isset($_POST['newCategory'])) {
            $name = $_POST['newCategory'];
            $Category = new Category();
            $Category->setName($name);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Category);

            $entityManager->flush();
            return $this->redirectToRoute('admin_category_edit');
        }

        if (isset($_POST['change'])) {
            $cat_id = $_POST['change'];
            $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findBy(['id' => $cat_id])[0];
            $category->setName($_POST['text']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);

            $entityManager->flush();

            return $this->redirectToRoute('admin_category_edit');
        }


        return $this->render('@App/admin/category/edit.html.twig',[
            'categories' => $categories,
        ]);
    }

}