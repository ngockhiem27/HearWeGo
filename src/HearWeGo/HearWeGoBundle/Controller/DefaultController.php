<?php

namespace HearWeGo\HearWeGoBundle\Controller;

use Doctrine\ORM\NoResultException;
use HearWeGo\HearWeGoBundle\Entity\Comment;
use HearWeGo\HearWeGoBundle\Entity\Rating;
use HearWeGo\HearWeGoBundle\Entity\Repository\DoctrineHelp;
use HearWeGo\HearWeGoBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use HearWeGo\HearWeGoBundle\Entity\Company;
use HearWeGo\HearWeGoBundle\Entity\User;
use HearWeGo\HearWeGoBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function filerHot($array)
    {
        if (count($array) == 0) return array();
        $array_filter = array();
        $count = 0;
        $filter_ele = 0;
        while ($count < count($array)) {

            $array_filter[$filter_ele] = array();
            $array_filter[$filter_ele][] = $array[$count];
            if ($count + 1 < count($array))
                $array_filter[$filter_ele][] = $array[$count + 1];
            else break;
            $filter_ele++;
            $count += 2;
        }
        return $array_filter;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        /**
         * Hot Places Block
         */
        $audiorepo = $em->getRepository('HearWeGoHearWeGoBundle:Audio');
        $hotaudio = $audiorepo->findHotAudio(10);
        $hotplaces = array();
        foreach ($hotaudio as $audio) {
            $place = array();
            $destination = $audio->getDestination();
            $photos = $destination->getPhotos()->toArray();
            $place[0] = $destination->getName();
            $place[1] = (count($photos) > 0) ? $photos[array_rand($photos)]->getWebPath() : "/bundles/hearwegohearwego/images/No image.jpg";
            $place[2] = $this->generateUrl('detail', array(
                "id" => $destination->getID()
            ));
            $hotplaces[] = $place;
        }
        $hotplaces_filter = $this->filerHot($hotplaces);

        /**
         * New Tours Block
         */
        $toursrepo = $em->getRepository('HearWeGoHearWeGoBundle:Tour');

        $newtoursraw = $toursrepo->findNewTour(16);
        $newtours = array();
        foreach ($newtoursraw as $tourraw ) {
            $tour = array();
            $destination = $tourraw->getDestination();
            $photos = $destination->getPhotos()->toArray();
            $tour[0] = $tourraw->getName();
            $tour[1] = (count($photos) > 0) ? $photos[array_rand($photos)]->getWebPath() : "/bundles/hearwegohearwego/images/No image.jpg";
            $tour[2] = $this->generateUrl('tour', array(
                "id" => $tourraw->getID()
            ));

            $newtours[] = $tour;
        }

        $newtoursfilter = $this->filerHot($newtours);

        /**
         * Sale Tours
         */
        $saletoursraw = $toursrepo->findSaleTour(16);
        $saletours = array();
        foreach ($saletoursraw as $tourraw ) {
            $tour = array();
            $destination = $tourraw->getDestination();
            $photos = $destination->getPhotos()->toArray();
            $tour[0] = $tourraw->getName();
            $tour[1] = (count($photos) > 0) ? $photos[array_rand($photos)]->getWebPath() : '/bundles/hearwegohearwego/images/No image.jpg';
            $tour[2] = $this->generateUrl('tour', array(
                "id" => $tourraw->getID()
            ));

            $saletours[] = $tour;
        }
        $saletoursfilter = $this->filerHot($saletours);


        return $this->render('HearWeGoHearWeGoBundle:Default/HomePage:homepage.html.twig', array(
            'hotplaces_filter' => $hotplaces_filter,
            'newtourfilter' => $newtoursfilter,
            'saletoursfilter' => $saletoursfilter

        ));
    }

    /**
     * @Route("/destination",name="destination")
     */
    public function destinationAction()
    {
        $regions=$this->getDoctrine()->getRepository('HearWeGoHearWeGoBundle:Region')->findAll();
        foreach ($regions as $region)
        {
            $destinations[$region->getId()]=$this->getDoctrine()->getRepository('HearWeGoHearWeGoBundle:Destination')->findByRegion($region->getId());
        }
        return $this->render('HearWeGoHearWeGoBundle:Default/Destination:destination.html.twig', array('destinations'=>$destinations));
    }
    /**
     * @Route("/destination/{id} ", name="detail")
     */
    public function detailAction($id)
    {

        $request = $this->get('request');
        $session = $this->get('session');

        $em = $this->getDoctrine()->getManager();
        $destinationrepo = $em->getRepository('HearWeGoHearWeGoBundle:Destination');

        $destination = $destinationrepo->find($id);

        if ($destination == NULL)
            return $this->redirect($this->generateUrl('destination'));
        else {
            $photos = $destination->getPhotos();
            $tours = $destination->getTours();
            $comments = $destination->getComments();
            //$articles=$this->getDoctrine()->getRepository('HearWeGoHearWeGoBundle:Article')->findByDestinationId($destination->getId());
            //$destination->addArticle($articles);

            $comment = new Comment();
            $commentForm = $this->createForm(new CommentType(), $comment, array(
                'method' => 'POST',
                'action'=> $this->generateUrl('create_comment', array(
                    'desID' => $id
                ))
            ));
            $commentForm->add('submit', 'submit');

            return $this->render('HearWeGoHearWeGoBundle:Default/Detail:detail.html.twig', array(
                'destination' => $destination,
                'photos' => $photos->toArray(),
                'tours' => $tours->toArray(),
                'comments' => $comments->toArray(),
                'commentForm' => $commentForm->createView()
            ));
        }

    }

    /**
     * @Route("/blog/{page}", name="blog")
     */
    public function blogAction($page)
    {
        $pageSize=6;
        $articlesQuery=$this->getDoctrine()->getRepository('HearWeGoHearWeGoBundle:Article')->queryAll();
        $articlesCount=count($articlesQuery->getResult());
        $paginator=DoctrineHelp::paginate($articlesQuery,$pageSize,$page);
        $articles=$articlesQuery->getResult();
        $articles1=array();
        $articles2=array();
        $count=1;
        foreach ($articles as $article)
        {
            if ($count%2==0)
            {
                $articles1[]=$article;
            }
            else
            {
                $articles2[]=$article;
            }
            $count++;
        }
        $numPages=ceil(($articlesCount)/$pageSize);
        if ($numPages==0)
        {
            $numPages = 1;
        }
        return $this->render('@HearWeGoHearWeGo/Default/Blog/blog.html.twig',array(
            'current'=>$page,
            'numPages'=>$numPages,
            'articlesQuery'=>$paginator,
            'articles1'=>$articles1,
            'articles2'=>$articles2));
    }

    /**
     * @Route("/article/{id}", name="article")
     */
    public function articleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('HearWeGoHearWeGoBundle:Article')->find($id);
        $relatives=$this->getDoctrine()->getRepository('HearWeGoHearWeGoBundle:Article')->findRelativeArticle($id);
        return $this->render('HearWeGoHearWeGoBundle:Default/Article:article.html.twig', array(
            'article' => $article,
            'relatives'=>$relatives
        ));
    }

    /**
     * @Route("/map", name="map")
     */
    public function mapAction()
    {
        return $this->render('HearWeGoHearWeGoBundle:Default/Map:map.html.twig');
    }

    /**
     * @Route("/tour/{id}", name="tour")
     */
    public function tourAction( $id ){
        $em = $this->getDoctrine()->getManager();
        $tour = $em->getRepository('HearWeGoHearWeGoBundle:Tour')->find($id);
        return $this->render('HearWeGoHearWeGoBundle:Tour:tour.html.twig', array(
            'tour' => $tour,
            'photos' => $tour->getDestination()->getPhotos()->toArray()
        ));
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $b=$request->request->get('id1');
            $em=$this->getDoctrine()->getEntityManager();
            $rating=new Rating();
            $rating->setAudio($this->getDoctrine()->getRepository('HearWeGoHearWeGoBundle:Audio')->findById(1));
            $rating->setUser($this->getDoctrine()->getRepository('HearWeGoHearWeGoBundle:User')->findById(4));
            $rating->setStars($b);
            $em->persist($rating);
            $em->flush();
        }
        return $this->render('HearWeGoHearWeGoBundle::test.html.twig');
    }

    /**
     * @Route("/payment" , name="payment")
     */
    public  function paymentAction()
    {
        return $this->render('HearWeGoHearWeGoBundle:Payment:payment.html.twig');
    }

    /**
     * @Route("/about",name="about")
     */
    public function aboutAction()
    {
        return $this->render('@HearWeGoHearWeGo/Default/about.html.twig');
    }

    /**
     * @Route("/contact",name="contact")
     */
    public function contactAction()
    {
        return $this->render('@HearWeGoHearWeGo/Default/contact.html.twig');

    }

    /**
     * @Route("/rating/{id}",name="rating")
     */
    public function ratingAction($id,Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('user_login'));
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if($request->isXmlHttpRequest()) {
            $b=$request->request->get('star');
            $em=$this->getDoctrine()->getEntityManager();
            $rating=$this->getDoctrine()->getRepository('HearWeGoHearWeGoBundle:Rating')->findByAudioAndUser($user->getId(),$id);
            if ($rating==null)
            {
                $rating = new Rating();
                $rating->setAudio($this->getDoctrine()->getRepository('HearWeGoHearWeGoBundle:Audio')->findById($id));
                $rating->setUser($user);
            }
            $rating->setStars($b);
            $em->persist($rating);
            $em->flush();
        }
        return new Response();
    }
}
