<?php


namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Comments;

class ProductController extends Controller
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
     * @Route("/product/{id}", name="product_item")
     */
    public function showAction($id)
    {
        $this->auth();
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        $products3 = $this->getDoctrine()->getRepository('AppBundle:Product')->get3Products();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if (!$product) {
            throw $this->createNotFoundException('Post not found');
        }

        $views = $product->getViews();
        $product->setViews( $views + 1);
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();
        $comments = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['product' => $id]);

        if (isset($_POST['like'])) {
            $votes_id = $_POST['like'];
            $comment = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['id' => $votes_id])[0];
            $votes = $comment->getLikes();
            $comment->setLikes($votes + 1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);

            $entityManager->flush();
            return $this->redirectToRoute('product_item', ['id' => $id]);
        }

        if (isset($_POST['dislike'])) {
            $votes_id = $_POST['dislike'];
            $comment = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['id' => $votes_id])[0];
            $votes = $comment->getDislikes();
            $comment->setDislikes($votes + 1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);

            $entityManager->flush();
            return $this->redirectToRoute('product_item', ['id' => $id]);
        }

        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/default/show.html.twig', [
            'product' => $product,
            'products3' => $products3,
            'comments' => $comments,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct)
        ]);
    }

    /**
     * @Route("/user/product/{id}", name="login_product_item")
     */
    public function showloginAction($id)
    {
        $auth = $this->getUser()->getUsername();

        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $products3 = $this->getDoctrine()->getRepository('AppBundle:Product')->get3Products();

        if (!$product) {
            throw $this->createNotFoundException('Post not found');
        }

        $views = $product->getViews();
        $product->setViews( $views + 1);
        $this->getDoctrine()->getManager()->persist($product);
        $this->getDoctrine()->getManager()->flush();
        $comments = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['product' => $id]);

        if (isset($_POST['comment'])) {
            $commentary = $_POST['comment'];

            $com = explode(" ", $commentary);
            $i=0;

            foreach ($com as $value) {
                if ($value != '') {
                    $comm [$i] = $value;
                    $i++;
                }
            }

            if ($comm) {$commentary = implode(" ", $comm);}

            $userid = $this->getUser()->getId();
            $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($userid);
            $Comments = new Comments();
            $Comments->setUser($user);
            $Comments->setProduct($product);
            $Comments->setCommentary($commentary);
            $Comments->setLikes(0);
            $Comments->setDislikes(0);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Comments);

            $entityManager->flush();

            return $this->redirectToRoute('login_product_item', ['id' => $id]);
        }


        if (isset($_POST['like'])) {
            $votes_id = $_POST['like'];
            $comment = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['id' => $votes_id])[0];
            $votes = $comment->getLikes();
            $comment->setLikes($votes + 1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);

            $entityManager->flush();
            return $this->redirectToRoute('login_product_item', ['id' => $id]);
        }

        if (isset($_POST['dislike'])) {
            $votes_id = $_POST['dislike'];
            $comment = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['id' => $votes_id])[0];
            $votes = $comment->getDislikes();
            $comment->setDislikes($votes + 1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);

            $entityManager->flush();
            return $this->redirectToRoute('login_product_item', ['id' => $id]);
        }

        if (isset($_POST['delete'])) {
            $votes_id = $_POST['delete'];
            $comment = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['id' => $votes_id])[0];

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);

            $entityManager->flush();
            return $this->redirectToRoute('admin_product_item', ['id' => $id]);
        }

        $cookProduct = [];

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/login/default/show.html.twig', [
            'product' => $product,
            'products3' => $products3,
            'comments' => $comments,
            'categories' => $categories,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct),
            'auth' => $auth
        ]);
    }

    /**
     * @Route("/admin/product/{id}", name="admin_product_item")
     */
    public function showAdminAction($id)
    {

        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        $products3 = $this->getDoctrine()->getRepository('AppBundle:Product')->get3Products();

        if (!$product) {
            throw $this->createNotFoundException('Post not found');
        }

        $comments = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['product' => $id]);

        if (isset($_POST['comment'])) {
            $commentary = $_POST['comment'];

            $com = explode(" ", $commentary);
            $i=0;
            $comm = false;

            foreach ($com as $value) {
                if ($value != '') {
                    $comm [$i] = $value;
                    $i++;
                }
            }

            if ($comm) {
                $commentary = implode(" ", $comm);
                $user = $this->getDoctrine()->getRepository('AppBundle:User')->find(2);
                $Comments = new Comments();
                $Comments->setUser($user);
                $Comments->setProduct($product);
                $Comments->setCommentary($commentary);
                $Comments->setLikes(0);
                $Comments->setDislikes(0);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($Comments);

                $entityManager->flush();
            }

            return $this->redirectToRoute('admin_product_item', ['id' => $id]);
        }


        if (isset($_POST['like'])) {
            $votes_id = $_POST['like'];
            $comment = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['id' => $votes_id])[0];
            $votes = $comment->getLikes();
            $comment->setLikes($votes + 1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);

            $entityManager->flush();
            return $this->redirectToRoute('admin_product_item', ['id' => $id]);
        }

        if (isset($_POST['dislike'])) {
            $votes_id = $_POST['dislike'];
            $comment = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['id' => $votes_id])[0];
            $votes = $comment->getDislikes();
            $comment->setDislikes($votes + 1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);

            $entityManager->flush();
            return $this->redirectToRoute('admin_product_item', ['id' => $id]);
        }

        if (isset($_POST['delete'])) {
            $votes_id = $_POST['delete'];
            $comment = $this->getDoctrine()->getRepository('AppBundle:Comments')->findBy(['id' => $votes_id])[0];

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);

            $entityManager->flush();
            return $this->redirectToRoute('admin_product_item', ['id' => $id]);
        }

        $cookProduct = [];
        $price = 0;

        if (isset($_COOKIE["id"])) {
            $cookProduct = $this->cookieAction($_COOKIE["id"]);
        }

        return $this->render('@App/admin/default/show.html.twig', [
            'product' => $product,
            'comments' => $comments,
            'categories' => $categories,
            'products3' => $products3,
            'cookProduct' => $cookProduct,
            'count' => count($cookProduct),
            'price' => $price
        ]);
    }
}