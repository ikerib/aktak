<?php

namespace App\Controller;

use App\Entity\Egoera;
use App\Form\EgoeraType;
use App\Repository\EgoeraRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/kudeatu/egoera")
 */
class EgoeraController extends AbstractController
{
    /**
     * @Route("/", name="egoera_index", methods={"GET"})
     * @param EgoeraRepository $egoeraRepository
     *
     * @return Response
     */
    public function index(EgoeraRepository $egoeraRepository): Response
    {
        return $this->render('egoera/index.html.twig', [
            'egoeras' => $egoeraRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="egoera_new", methods={"GET","POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $egoera = new Egoera();
        $form = $this->createForm(EgoeraType::class, $egoera);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($egoera);
            $entityManager->flush();

            return $this->redirectToRoute('egoera_index');
        }

        return $this->render('egoera/new.html.twig', [
            'egoera' => $egoera,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="egoera_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Egoera  $egoera
     *
     * @return Response
     */
    public function edit(Request $request, Egoera $egoera): Response
    {
        $form = $this->createForm(EgoeraType::class, $egoera);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('egoera_index');
        }

        return $this->render('egoera/edit.html.twig', [
            'egoera' => $egoera,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="egoera_delete", methods={"DELETE"})
     * @param Request $request
     * @param Egoera  $egoera
     *
     * @return Response
     */
    public function delete(Request $request, Egoera $egoera): Response
    {
        if ($this->isCsrfTokenValid('delete'.$egoera->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($egoera);
            $entityManager->flush();
        }

        return $this->redirectToRoute('egoera_index');
    }
}
