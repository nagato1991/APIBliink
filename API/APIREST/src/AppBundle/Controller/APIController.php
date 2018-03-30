<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Evenement;

class APIController extends Controller
{
    /**
     * @Route("get/api/allevents", name="requete")
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
$données= $this->get('jms_serializer')->serialize($event, 'json');
      $evenement = new Response($données);
      $evenement ->headers->set('Content-Type', 'application/Json');
    return $evenement;  
     
    }
  
    
    
     /**
     * @Route("get/api/dashboard", name="dash")
     */
    public function dashAction(Request $request)
    {
       $repository = $this->getDoctrine()->getRepository('AppBundle:Evenement');
        
        $event = $repository->findBy(array(), array('name' => 'asc'));

    if (!$event) {
        throw $this->createNotFoundException(
            'vide'
        );
    }
   $nbre = array('total'=> count($event));
    $send= array($nbre, $event);
$données= $this->get('jms_serializer')->serialize($send, 'json');
      $evenement = new Response($données);
      $evenement ->headers->set('Content-Type', 'application/Json');
    return $evenement;  
    }
    
    /**
     * @Route("post/api/events", name="envoi")
     */
    public function postAction(Request $request)
    {
        
       //recuperation de la date
          $time = new \DateTime();
       //recuperation de donnée
        $data = $request->getContent();
        //deserialisation
        $evenement= $this->get('jms_serializer')
                ->deserialize($data, 'AppBundle\Entity\Evenement', 'json');
       $test = new Evenement();
       $test= $evenement;
       
        $test->setCreatedAt ($time);
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
          $entityManager = $this->getDoctrine()->getManager();
       $entityManager->persist($test);

        // actually executes the queries (i.e. the INSERT query)
      $entityManager->flush();

       return new Response('Enregistrer avec l\id  '.$test->getId());
     
    }
    
        /**
 * @Route("delete/api/events/{id}", name="delete")
 */
public function deleteAction(Evenement $evenement)
{

$entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($evenement);
$entityManager->flush();
    
if (!$evenement) {
        throw $this->createNotFoundException(
   $reponse = new Response('cet evenement nexiste pas')   
        );
    }
    else
    {
        $reponse = new Response('Element supprimé');
    }
   
    return new Response($reponse);
   
}
    
     
    
    /**
 * @Route("get/api/events/{id}", name="events_show")
 */
public function getOneAction(Evenement $evenement)
{
    $données= $this->get('jms_serializer')->serialize($evenement, 'json');

    $reponse = new Response($données);
    $reponse ->headers->set('Content-Type', 'application/Json');
   
    return $reponse;
   
}

    /**
 * @Route("vider/api/events", name="vider")
 */
public function viderAction()
{
    $entityManager = $this->getDoctrine()->getManager();
            $connection = $entityManager->getConnection();
$platform   = $connection->getDatabasePlatform();
  
$connection->executeUpdate($platform->getTruncateTableSQL('event', true /* whether to cascade */));
   
    return new Response("la Table a été vidée");
   
}

}
