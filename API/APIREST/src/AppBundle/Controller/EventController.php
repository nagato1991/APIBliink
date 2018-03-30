<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Evenement;

class EventController extends Controller
{
    /**
     * @Route("/", name="allo")
     */
    public function getAction(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:Evenement');
        
        $event = $repository->findAll();

    if (!$event) {
        throw $this->createNotFoundException(
            'vide'
        );
    }
    $nbre = count($event);

    return $this->render('default/index.html.twig', array('name' => $nbre, 'evenement'=> $event)) ;  
     
    }
  
    

}
