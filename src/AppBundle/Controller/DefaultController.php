<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Orders;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    private function cookieAction($cookie)
    {
        $arrId = explode(",", $cookie);
        $arrId = array_unique($arrId);
        setcookie("id", implode(",", $arrId),time()+86400,'/');

        foreach ($arrId as $value) {
            $cookProduct [] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
        }
        return $cookProduct;
    }

    private function auth()
    {
        if ($this->getUser()) {
            $auth = $this->getUser()->getRoles();
            if (in_array('ROLE_USER', $auth)) return $this->redirectToRoute('login_homepage');
        }
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $this->auth();
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->get9Products();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/default/index.html.twig',[
            'products' => $products,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct)
        ]);
    }


    /**
     * @Route("/photo", name="photo")
     */
    public function photoAction(Request $request)
    {
        $this->auth();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT g FROM AppBundle:Gallery g  ORDER BY g.id DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
        );

        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/default/photo.html.twig',[
            'categories' => $categories,
            'pagination' => $pagination,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct)
        ]);
    }

    /**
     * @Route("/delivery", name="delivery")
     */
    public function deliveryAction()
    {
        $this->auth();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/default/delivery.html.twig',[
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct)
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
        $this->auth();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/default/contact.html.twig',[
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct)
        ]);
    }

    /**
     * @Route("/cart", name="cart")
     */
    public function cartAction()
    {
        $this->auth();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $cookProduct = [];
        $price = 0;

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        if ($cookProduct != []) {
            foreach ($cookProduct as $value) {
                $price = $price + $value->getActualprice();
            }

        } else {
            return $this->redirectToRoute('homepage');
        }


        return $this->render('@App/default/cart.html.twig',[
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct),
            'price' => $price
        ]);
    }

    /**
     * @Route("/order", name="order")
     */
    public function orderAction()
    {
        if (!isset($_COOKIE["id"])) {
            return $this->redirectToRoute('homepage');
        }

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        dump($_COOKIE["id"]);

        if (isset($_POST['firstname']) && $_POST['firstname'] != "" && isset($_POST['lastname']) && $_POST['lastname'] != "" && isset($_POST['phone']) && $_POST['phone'] != "") {
            $totalprice = 0;
            $arrId = explode(",", $_COOKIE["id"]);

            foreach ($arrId as $value) {
                $totalprice = $totalprice + $this->getDoctrine()->getRepository('AppBundle:Product')->find($value)->getActualPrice();
            }

            $orders = new Orders();

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $phone = $_POST['phone'];

            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $orders->setEmail($email);
            }

            if (isset($_POST['suggestion'])) {
                $suggestion = $_POST['suggestion'];
                $orders->setSuggestion($suggestion);
            }

            $payment = $_POST['payment'];
            $delivery = $_POST['delivery'];

            $orders->setFirstname($firstname);
            $orders->setLastname($lastname);
            $orders->setPhone($phone);
            $orders->setOrderitems($_COOKIE["id"]);
            $orders->setPayment($payment);
            $orders->setDelivery($delivery);
            $orders->setActive(1);
            $orders->setTotalprice($totalprice);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orders);

            dump($orders);

            $entityManager->flush();

            setcookie("order", "1",time()+86400,'/');

            return $this->redirectToRoute('order_final');
        }

        return $this->render('@App/default/order.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/order/final", name="order_final")
     */
    public function orderFinalAction()
    {
        if (!isset($_COOKIE["order"]) && $_COOKIE["order"] != 1) {
            return $this->redirectToRoute('homepage');
        }

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $orderid = $this->getDoctrine()->getRepository('AppBundle:Orders')->getOrderid()[0]->getId();

        setcookie("order", null, -1, '/');
        setcookie("id", null, -1, '/');

        return $this->render('@App/default/order.id.html.twig', [
            'categories' => $categories,
            'orderid' => $orderid
        ]);
    }


    /**
     * @Route("/user", name="login_homepage")
     */
    public function loginAction()
    {
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->get9Products();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/login/default/index.html.twig',[
            'products' => $products,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct)
        ]);
    }

    /**
     * @Route("/user/photo", name="login_photo")
     */
    public function photologinAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT g FROM AppBundle:Gallery g  ORDER BY g.id DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
        );

        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/login/default/photo.html.twig',[
            'categories' => $categories,
            'pagination' => $pagination,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct)
        ]);
    }

    /**
     * @Route("/user/delivery", name="login_delivery")
     */
    public function deliveryloginAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/login/default/delivery.html.twig',[
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct)
        ]);
    }

    /**
     * @Route("/user/contact", name="login_contact")
     */
    public function contactloginAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/login/default/contact.html.twig',[
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct)
        ]);
    }

    /**
     * @Route("/user/cart", name="login_cart")
     */
    public function cartloginAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $cookProduct = [];
        $price = 0;

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        if ($cookProduct != []) {
            foreach ($cookProduct as $value) {
                $price = $price + $value->getActualprice();
            }

        } else {
            return $this->redirectToRoute('homepage');
        }


        return $this->render('@App/login/default/cart.html.twig',[
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct),
            'price' => $price
        ]);
    }

    /**
     * @Route("/user/order", name="login_order")
     */
    public function orderLoginAction()
    {
        if (!isset($_COOKIE["id"])) {
            return $this->redirectToRoute('login_homepage');
        }

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        dump($_COOKIE["id"]);

        if (isset($_POST['firstname']) && $_POST['firstname'] != "" && isset($_POST['lastname']) && $_POST['lastname'] != "" && isset($_POST['phone']) && $_POST['phone'] != "") {
            $totalprice = 0;
            $arrId = explode(",", $_COOKIE["id"]);

            foreach ($arrId as $value) {
                $totalprice = $totalprice + $this->getDoctrine()->getRepository('AppBundle:Product')->find($value)->getActualPrice();
            }

            $orders = new Orders();

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $phone = $_POST['phone'];

            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $orders->setEmail($email);
            }

            if (isset($_POST['suggestion'])) {
                $suggestion = $_POST['suggestion'];
                $orders->setSuggestion($suggestion);
            }

            $payment = $_POST['payment'];
            $delivery = $_POST['delivery'];

            $orders->setFirstname($firstname);
            $orders->setLastname($lastname);
            $orders->setPhone($phone);
            $orders->setOrderitems($_COOKIE["id"]);
            $orders->setPayment($payment);
            $orders->setDelivery($delivery);
            $orders->setActive(1);
            $orders->setTotalprice($totalprice);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orders);

            dump($orders);

            $entityManager->flush();

            setcookie("order", "1",time()+86400,'/');

            return $this->redirectToRoute('login_order_final');
        }

        return $this->render('@App/login/default/order.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/user/order/final", name="login_order_final")
     */
    public function orderFinalLoginAction()
    {
        if (!isset($_COOKIE["order"]) && $_COOKIE["order"] != 1) {
            return $this->redirectToRoute('login_homepage');
        }

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $orderid = $this->getDoctrine()->getRepository('AppBundle:Orders')->getOrderid()[0]->getId();

        setcookie("order", null, -1, '/');
        setcookie("id", null, -1, '/');

        return $this->render('@App/login/default/order.id.html.twig', [
            'categories' => $categories,
            'orderid' => $orderid
        ]);
    }

    /**
     * @Route("/admin", name="admin_homepage")
     */
    public function adminAction()
    {
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->get9Products();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            setcookie("id", implode(",", $arrId), time()+86400,'/');

            foreach ($arrId as $value) {
                $cookProduct [] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
            }
        }
        return $this->render('@App/admin/default/index.html.twig',[
            'products' => $products,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct)
        ]);
    }

    /**
     * @Route("/admin/edit/{id}", name="admin_edit_item")
     */
    public function editItemAdminAction($id)
    {

        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if (!$product) {
            throw $this->createNotFoundException('Post not found');
        }

        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findBy(['id' => $_POST['category']])[0];
            $image = $_POST['image'];
            $price = $_POST['price'];
            $active = $_POST['active'];

            $product->setTitle($title);
            if ($description !== "") {
                $product->setDescription($description);
            }
            $product->setCategory($category);
            $product->setImage($image);
            if ($price > $product->getPrice()) {
                $product->setPrice($price);
            }
            $product->setActualPrice($price);
            $product->setActive($active);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);

            $entityManager->flush();

            if ($active == 1) {
                return $this->redirectToRoute('admin_product_item', ['id' => $id]);
            } else {
                return $this->redirectToRoute('admin_homepage');
            }
        }

        return $this->render('@App/admin/default/edit.html.twig', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/photo", name="admin_photo")
     */
    public function photoAdminAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT g FROM AppBundle:Gallery g  ORDER BY g.id DESC";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
        );

        $cookProduct = [];
        $price = 0;

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/admin/default/photo.html.twig',[
            'categories' => $categories,
            'pagination' => $pagination,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct),
            'price' => $price
        ]);
    }

    /**
     * @Route("/admin/delivery", name="admin_delivery")
     */
    public function deliveryAdminAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $cookProduct = [];
        $price = 0;

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }


        return $this->render('@App/admin/default/delivery.html.twig',[
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct),
            'price' => $price
        ]);
    }

    /**
     * @Route("/admin/contact", name="admin_contact")
     */
    public function contactAdminAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $cookProduct = [];
        $price = 0;

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }


        return $this->render('@App/admin/default/contact.html.twig',[
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct),
            'price' => $price
        ]);
    }

    /**
     * @Route("/admin/cart", name="admin_cart")
     */
    public function cartAdminAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $cookProduct = [];
        $price = 0;

        if (isset($_COOKIE["id"])) {
            $arrId = explode(",", $_COOKIE["id"]);
            $arrId = array_unique($arrId);
            dump($arrId);
            setcookie("id", implode(",", $arrId),time()+86400,'/');

            foreach ($arrId as $value) {
                $cookProduct [] = $this->getDoctrine()->getRepository('AppBundle:Product')->find($value);
            }
        }

        dump($_COOKIE["id"]);

        if ($cookProduct != []) {
            foreach ($cookProduct as $value) {
                $price = $price + $value->getActualprice();
            }

        } else {
            return $this->redirectToRoute('admin_homepage');
        }


        return $this->render('@App/admin/default/cart.html.twig',[
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct),
            'price' => $price
        ]);
    }

    /**
     * @Route("/admin/order", name="admin_order")
     */
    public function orderAdminAction()
    {
        if (!isset($_COOKIE["id"])) {
            return $this->redirectToRoute('admin_homepage');
        }

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        dump($_COOKIE["id"]);

        if (isset($_POST['firstname']) && $_POST['firstname'] != "" && isset($_POST['lastname']) && $_POST['lastname'] != "" && isset($_POST['phone']) && $_POST['phone'] != "") {
            $totalprice = 0;
            $arrId = explode(",", $_COOKIE["id"]);

            foreach ($arrId as $value) {
                $totalprice = $totalprice + $this->getDoctrine()->getRepository('AppBundle:Product')->find($value)->getActualPrice();
            }

            $orders = new Orders();

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $phone = $_POST['phone'];

            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $orders->setEmail($email);
            }

            if (isset($_POST['suggestion'])) {
                $suggestion = $_POST['suggestion'];
                $orders->setSuggestion($suggestion);
            }

            $payment = $_POST['payment'];
            $delivery = $_POST['delivery'];

            $orders->setFirstname($firstname);
            $orders->setLastname($lastname);
            $orders->setPhone($phone);
            $orders->setOrderitems($_COOKIE["id"]);
            $orders->setPayment($payment);
            $orders->setDelivery($delivery);
            $orders->setActive(1);
            $orders->setTotalprice($totalprice);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orders);

            dump($orders);

            $entityManager->flush();

            setcookie("order", "1",time()+86400,'/');

            return $this->redirectToRoute('admin_order_final');
        }

        return $this->render('@App/admin/default/order.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/order/final", name="admin_order_final")
     */
    public function orderFinalAdminAction()
    {
        if (!isset($_COOKIE["order"]) && $_COOKIE["order"] != 1) {
            return $this->redirectToRoute('admin_homepage');
        }

        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $orderid = $this->getDoctrine()->getRepository('AppBundle:Orders')->getOrderid()[0]->getId();

        setcookie("order", null, -1, '/');
        setcookie("id", null, -1, '/');

        return $this->render('@App/admin/default/order.id.html.twig', [
            'categories' => $categories,
            'orderid' => $orderid
        ]);
    }

    /**
     * @Route("/admin/addproduct", name="admin_product_add")
     */
    public function addProductAdminAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if (isset($_POST['title'])) {
            $Product = new Product();
            $title = $_POST['title'];
            $description = $_POST['description'];
            $category = $this->getDoctrine()->getRepository('AppBundle:Category')->findBy(['id' => $_POST['category']])[0];
            $image = $_POST['image'];
            $price = $_POST['price'];
            $views = 0;
            $active = 1;

            $Product->setTitle($title);
            $Product->setDescription($description);
            $Product->setCategory($category);
            $Product->setImage($image);
            $Product->setPrice($price);
            $Product->setActualPrice($price);
            $Product->setViews($views);
            $Product->setActive($active);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Product);

            $entityManager->flush();
            return $this->redirectToRoute('admin_product_add');
        }


        return $this->render('@App/admin/default/add.html.twig', [
            'categories' => $categories,
        ]);
    }
}
