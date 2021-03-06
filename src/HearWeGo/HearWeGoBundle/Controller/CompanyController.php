<?php
namespace HearWeGo\HearWeGoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use HearWeGo\HearWeGoBundle\Entity\Company;
use HearWeGo\HearWeGoBundle\Entity\Tour;
use HearWeGo\HearWeGoBundle\Form\CompanySignupType;
use HearWeGo\HearWeGoBundle\Form\CompanySubmitTourType;
use HearWeGo\HearWeGoBundle\Form\CompanyEditType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends Controller
{
    /**
     * @Route("/signup/company",name="company_signup")
     */
    public function signupAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $company=new Company();
        $form=$this->createForm(new CompanySignupType(),$company,array('method'=>'POST','action'=>$this->generateUrl('company_signup')));
        $form->add('submit','submit');
        if ($request->getMethod()=='POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em=$this->getDoctrine()->getEntityManager();
                $em->persist($company);
                $em->flush();
                return $this->redirect($this->generateUrl('homepage'));
            }
        }
        return $this->render('HearWeGoHearWeGoBundle:Company:companySignup.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Route("/company/submit",name="company_submit")
     *
     */
    public function submitTourAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('user_login'));
        }
        $this->denyAccessUnlessGranted('ROLE_COMPANY', null, 'Unable to access this page!');
        $company = $this->get('security.token_storage')->getToken()->getUser();


        $tour=new Tour();
        $form=$this->createForm(new CompanySubmitTourType($this->get('destination.transformer')),$tour,array(
            'method'=>'POST',
            'action'=>$this->generateUrl('company_submit')
        ));
        $form->add('submit', 'submit');

        if ($request->getMethod()=='POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $tour->setCompany($company);
                $em=$this->getDoctrine()->getEntityManager();
                $em->persist($tour);
                $em->flush();
                return $this->render('HearWeGoHearWeGoBundle:Company/tour:submit.html.twig',array('form'=>$form->createView()));
            }
        }
        return $this->render('HearWeGoHearWeGoBundle:Company/tour:submit.html.twig',array('form'=>$form->createView()));
    }

    /**
     * @Route("/company/profile",name="company_profile")
     */
    public function editCompanyAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirect($this->generateUrl('user_login'));
        }

        $this->denyAccessUnlessGranted('ROLE_COMPANY', null, 'Unable to access this page!');

        $company=$this->get('security.token_storage')->getToken()->getUser();

        $form=$this->createForm(new CompanyEditType($company),$company,array('method'=>'POST','action'=>$this->generateUrl('company_profile')));
        $form->add('submit','submit');
        if ($request->getMethod()=='POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em=$this->getDoctrine()->getEntityManager();
                $em->persist($company);
                $em->flush();
                return $this->render('HearWeGoHearWeGoBundle:Company/profile:company.html.twig',array('form'=>$form->createView()));
            }
        }
        return $this->render('HearWeGoHearWeGoBundle:Company/profile:company.html.twig',array('form'=>$form->createView()));
    }

    /**
     *@Route("/company/delete",name="company_delete")
     */
    public function deleteCompanyAction(Request $request){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirect($this->generateUrl('user_login'));
        }

        $this->denyAccessUnlessGranted('ROLE_COMPANY', null, 'Unable to access this page!');

        $company=$this->get('security.token_storage')->getToken()->getUser();

        $arr=array();
        $form=$this->createFormBuilder($arr,array('method'=>'DELETE','action'=>$this->generateUrl('company_delete')));
        $form->add('password','password')->add('delete','submit');
        if ($request->getMethod()=='DELETE')
        {
            $form=$form->getForm();
            $form->handleRequest($request);
            echo $form->get('password')->getData()."<br>".$company->getPassword();

            if ($form->get('password')->getData()==$company->getPassword())
            {
                $em=$this->getDoctrine()->getEntityManager();
                $em->remove($company);
                $em->flush();
                return new Response('Deleted!');
            }
            else
                return new Response('Wrong password!');
        }
        return $this->render('HearWeGoHearWeGoBundle:Company:companyDelete.html.twig',array('form'=>$form->getForm()->createView()));
    }

    /**
     *@Route("/company/tour",name="company_manage")
     */
    public function manageTourAction(){

        $request = $this->get('request');

        $company = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('HearWeGoHearWeGoBundle:Company/tour:managetour.html.twig', array(
            'tours' => $company->getTours()->toArray()
        ));
    }

    /**
     *@Route("/company/tour/{id}",name="company_update_tour")
     */
    public function updateTourAction( $id ){

        $request = $this->get('request');


        $em = $this->getDoctrine();
        $tour = $em->getRepository('HearWeGoHearWeGoBundle:Tour')->find( $id );
        $form=$this->createForm(new CompanySubmitTourType($this->get('destination.transformer')),$tour,array(
            'method'=>'POST',
            'action'=>$this->generateUrl('company_update_tour', array(
                'id' => $id
            ))
        ));
        $form->add('submit', 'submit');

        if ($request->getMethod()=='POST')
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em=$this->getDoctrine()->getEntityManager();
                $tour->setStatus(false);
                $em->persist($tour);
                $em->flush();
                $company = $this->get('security.token_storage')->getToken()->getUser();
                return $this->render('HearWeGoHearWeGoBundle:Company/tour:managetour.html.twig', array(
                    'tours' => $company->getTours()->toArray()
                ));
            }
        }


        return $this->render('HearWeGoHearWeGoBundle:Company/tour:updatetour.html.twig', array(
            'form' => $form->createView()
        ));

    }

    /**
     * @Route("/company/delete/{id}",name="delete_tour")
     */
    public function deleteTourAction( $id ){
        $request = $this->get('request');

        $em = $this->getDoctrine()->getManager();
        $tourrepo = $em->getRepository('HearWeGoHearWeGoBundle:Tour');
        $tour = $tourrepo->find($id);
        $em->remove($tour);
        $em->flush();
        $company = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('HearWeGoHearWeGoBundle:Company/tour:managetour.html.twig', array(
            'tours' => $company->getTours()->toArray()
        ));

    }
}
