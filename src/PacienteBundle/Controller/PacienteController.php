<?php

namespace PacienteBundle\Controller;

use PacienteBundle\Entity\Paciente;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Paciente controller.
 *
 * @Route("paciente")
 */
class PacienteController extends Controller
{
    /**
     *
     * Busqueda
     *
     * @Route("/search" , name="busqueda")
     * @Method("POST")
     */
    public function busqueda(Request $request)
    {
        $busqueda = $request->get('busqueda');
            $repository = $this->getDoctrine()
                ->getRepository('PacienteBundle:Paciente');

            $query = $repository->createQueryBuilder('p')
                ->where('p.name LIKE :nombre OR p.lastName LIKE :apellido OR p.idNumber=:idNumber')
                ->setParameter('nombre', '%'.$busqueda.'%')
                ->setParameter('apellido', '%'.$busqueda.'%')
                ->setParameter('idNumber', $busqueda)
                ->orderBy('p.name', 'ASC')
                ->getQuery();
            $pacientes = $query->getResult();
            
            $repository = $this->getDoctrine()
                ->getRepository('AnalisisBundle:Analisis');

            $query = $repository->createQueryBuilder('p')
                ->where('p.name LIKE :nombre')
                ->setParameter('nombre', '%'.$busqueda.'%')
                ->orderBy('p.name', 'ASC')
                ->getQuery();
            $analisis = $query->getResult();
            return $this->render('PacienteBundle:Default:busqueda.html.twig',['pacientes' => $pacientes,
                                                                            'analisis' => $analisis]);
            
    }
    /**
     * Lists all paciente entities.
     *
     * @Route("/list", name="paciente_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $pacientes = $this->getDoctrine()
                 ->getRepository('PacienteBundle:Paciente')
                 ->findAll();

        return $this->render('paciente/index.html.twig', array(
            'pacientes' => $pacientes,
        ));
    }

    /**
     * Creates a new paciente entity.
     *
     * @Route("/new", name="paciente_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $paciente = new Paciente();
        $form = $this->createForm('PacienteBundle\Form\PacienteType', $paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($paciente);
            $em->flush();

            return $this->redirectToRoute('paciente_show', array('id' => $paciente->getId()));
        }

        return $this->render('paciente/new.html.twig', array(
            'paciente' => $paciente,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a paciente entity.
     *
     * @Route("/{id}", name="paciente_show")
     * @Method("GET")
     */
    public function showAction(Paciente $paciente)
    {
        $deleteForm = $this->createDeleteForm($paciente);

        return $this->render('paciente/show.html.twig', array(
            'paciente' => $paciente,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing paciente entity.
     *
     * @Route("/{id}/edit", name="paciente_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Paciente $paciente)
    {
        $deleteForm = $this->createDeleteForm($paciente);
        $editForm = $this->createForm('PacienteBundle\Form\PacienteType', $paciente);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('paciente_edit', array('id' => $paciente->getId()));
        }

        return $this->render('paciente/edit.html.twig', array(
            'paciente' => $paciente,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a paciente entity.
     *
     * @Route("/{id}", name="paciente_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Paciente $paciente)
    {
        $form = $this->createDeleteForm($paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($paciente);
            $em->flush();
        }

        return $this->redirectToRoute('paciente_index');
    }

    /**
     * Creates a form to delete a paciente entity.
     *
     * @param Paciente $paciente The paciente entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Paciente $paciente)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('paciente_delete', array('id' => $paciente->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
