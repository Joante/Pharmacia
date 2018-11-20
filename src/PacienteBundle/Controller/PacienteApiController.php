<?php 

namespace PacienteBundle\Controller;

use PacienteBundle\Entity\Paciente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PacienteApiController extends Controller
{
	/**
     * @Route("/paciente/api/list" , name="paciente_api_list")
     */
    public function listAction()
    {
        $pacientes = $this->getDoctrine()
                 ->getRepository('PacienteBundle:Paciente')
                 ->findAll();
        $response= new Response();
        $response->headers->add(['Content-Type'=>'application/json']);
        $response->setContent(json_encode($pacientes));
        return $response;
    }
	/**
     * Creates a new paciente entity.
     *
     * @Route("/pacient/api/new", name="paciente_api_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $paciente = new Paciente();
        $form = $this->createForm('PacienteBundle\Form\PacienteApiType', $paciente);
        $form->handleRequest($request);
        $response = new Response();
        $response->headers->add(['Content-Type'=>'application/json']);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paciente);
            $em->flush();

            $response->setContent(json_encode($paciente));
            
        }
        return $response;
    }
}