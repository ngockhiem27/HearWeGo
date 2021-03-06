<?php

namespace HearWeGo\HearWeGoBundle\Controller;

use HearWeGo\HearWeGoBundle\Entity\Comment;
use HearWeGo\HearWeGoBundle\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use HearWeGo\HearWeGoBundle\Entity\User;
use HearWeGo\HearWeGoBundle\Form;


class UserController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('homepage'));
        }
        $session = $this->get('session');
        $request = $this->get('request');
        if ($request->getMethod() == 'GET') {
            $routeName = $request->get('_route');
            if ($routeName == 'register') return $this->redirect($this->generateUrl('homepage'));
        }
        $user = new User();
        $form = $this->createForm(new Form\UserRegisterType(), $user, array(
            'method' => 'POST',
            'action' => $this->generateUrl("register")
        ));
        $form->add('confirm', 'password', array('mapped' => false));
        $form->add('submit', 'submit', array(
            'label' => '',
            'attr' => array('class' => 'reg-submit')
        ));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $roleUser = $em->getRepository('HearWeGoHearWeGoBundle:Role')->getRoleUser();
                $roleUser->addUser($user);
                $user->addRole($roleUser);
                $em->persist($roleUser);
                $em->persist($user);
                $em->flush();
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                return $this->redirect($this->generateUrl('homepage'));
            } else {
                $session -> getFlashBag()->add('error', $form->getErrors());
            }
        }
        return $this->render('HearWeGoHearWeGoBundle:Default/SubView:register.html.twig', array(
            'form' => $form->createView()
        ));

    }

    /**
     * @Route("/login", name="user_login")
     */
    public function userLoginAction()
    {
        $session = $this->get('session');
        $request = $this->get('request');

        $routeName = $request->get('_route');
        if ($routeName == 'user_login') return $this->redirect($this->generateUrl('homepage'));
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return $this->redirectToRoute('homepage');

        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->has(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        $user = new User();
        $form = $this->createForm(new Form\UserLoginType(), $user, array(
            'method' => 'POST',
            'action' => $this->generateUrl('user_login_check')
        ));
        $form->add('submit', 'submit');
        return $this->render('HearWeGoHearWeGoBundle:Default/SubView:login.html.twig', array(
            'form' => $form->createView(),
            'error' => $error
        ));


    }

    /**
     * @Route("/login_check", name="user_login_check")
     */
    public function userLoginCheckAction()
    {

    }

    /**
     * @Route("/logout", name="user_logout")
     */
    public function userLogoutAction()
    {

    }


    /**
     * @Route("/profile",name="edit_profile")
     */
    public function editProfile()
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');

        $request = $this->get('request');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $pass = $user->getPassword();
        $form = $this->createForm(new Form\UserEditType($user), $user, array(
            'method' => 'POST',
            'action' => $this->generateUrl('edit_profile')
        ));
        $form->add('confirm', 'password', array('mapped' => false));
        $form->add('submit', 'submit', array(
            'label' => '',
            'attr' => array('class' => 'my-custom-button')
        ));
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($pass == $form->get('confirm')->getData()) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $em->persist($user);
                    $em->flush();
                    return $this->render('HearWeGoHearWeGoBundle:Default/Profile:profile.html.twig', array(
                        "form" => $form->createView(),
                        'orders' => $user->getOrders()->toArray()
                    ));
                }
            }
        }

        return $this->render('HearWeGoHearWeGoBundle:Default/Profile:profile.html.twig', array(
            "form" => $form->createView(),
            'orders' => $user->getOrders()->toArray()
        ));
    }

    /**
     * @Route("/comment/{desID}",name="create_comment")
     */
    public function commentAction( $desID ){
        $request = $this->get('request');
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('homepage'));
        }
        $comment = new Comment(  );
        $commentForm = $this->createForm(new Form\CommentType(), $comment, array(
            'method' => 'POST',
            'action'=> $this->generateUrl('create_comment', array(
                'desID'=>$desID
            ))
        ));
        $commentForm->add('submit', 'submit');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('HearWeGoHearWeGoBundle:User')->find($this->get('security.token_storage')->getToken()->getUser()->getID());
        $destination = $em->getRepository('HearWeGoHearWeGoBundle:Destination')->find($desID);

        $comment->setUser($user);
        $comment->setDestination($destination);
        if ($request->getMethod() == 'POST') {
            $commentForm->handleRequest($request);
            if ($commentForm->isValid()){
                $em->persist($comment);
                $em->flush();
                return $this->redirect($this->generateUrl('detail', array(
                    'id' => $desID
                )));
            }
        }
        return $this->redirect($this->generateUrl('detail', array(
            'id' => $desID
        )));

    }


    /**
     * @Route("/checkout/{audioid}",name="checkout")
     */
    public function checkOutAction( $audioid ){
        $session = $this->get('session');
        $request = $this->get('request');
        if ($request->getMethod() == 'GET') {
            $routeName = $request->get('_route');
            if ($routeName == 'checkout') return $this->redirect($this->generateUrl('homepage'));
        }
        $em = $this->getDoctrine()->getManager();
        $audio = $em->getRepository('HearWeGoHearWeGoBundle:Audio')->find($audioid);
        $detail = array(
            'name' => $audio->getName(),
            'price' => $audio->getPrice(),
            'destination' => $audio->getDestination()->getName()
        );
        $form = $this->createFormBuilder($detail, array(
            'method' => 'POST',
            'action' => $this->generateUrl('checkout', array(
                'audioid' => $audioid
            ))
        ))
                    ->add('name', 'text')
                    ->add('price', 'text')
                    ->add('destination', 'text')
                    ->add('submit', 'submit')
        ;
        if ($request->getMethod() == 'POST'){

            if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
                return $this->redirect($this->generateUrl('homepage'));
            }
            if ($audio) {
                $order = new Orders();
                $order->setUser($this->get('security.token_storage')->getToken()->getUser());
                $order->setAudios($audio);
                $em->persist($order);
                $em->flush();
                return $this->render('HearWeGoHearWeGoBundle:Default/SubView:download.html.twig');
            }
        }

        return $this->render('HearWeGoHearWeGoBundle:Payment:checkout.html.twig', array(
            'form' => $form->getForm()->createView()
        ));
    }

}
