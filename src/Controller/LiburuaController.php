<?php

namespace App\Controller;

use App\Entity\Egoera;
use App\Entity\Liburua;
use App\Entity\Mota;
use App\Form\LiburuaType;
use App\Repository\LiburuaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LiburuaSearchFormType;

/**
 * @Route("/kudeatu/liburua")
 */
class LiburuaController extends AbstractController
{
    /**
     * @Route("/", name="liburua_index", methods={"GET"})
     * @param Request            $request
     * @param LiburuaRepository  $liburuaRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(Request $request, LiburuaRepository $liburuaRepository, PaginatorInterface $paginator): Response
    {
        $searchForm = $this->createForm( LiburuaSearchFormType::class, null, [
            'action' => $this->generateUrl( 'liburua_index' ),
            'method' => 'GET',
        ] );

        $searchForm->handleRequest( $request );
        $filter = null;
        $extra = null;

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $filter = $searchForm->getData();
            $extra['hasieratik'] = $searchForm->get( 'hasieratik' )->getData();
            $extra['hasierara'] = $searchForm->get( 'hasierara' )->getData();
            $extra['amaieratik'] = $searchForm->get( 'amaieratik' )->getData();
            $extra['amaierara'] = $searchForm->get( 'amaierara' )->getData();
        }

        $query = $liburuaRepository->getAllQuery($filter, $extra);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('liburua/index.html.twig', [
            'liburuas'      => $pagination,
            'searchform'    => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/new", name="liburua_new", methods={"GET","POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $liburua = new Liburua();
        $form = $this->createForm(LiburuaType::class, $liburua);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($liburua);
            $entityManager->flush();

            return $this->redirectToRoute('liburua_index');
        }

        return $this->render('liburua/new.html.twig', [
            'liburua' => $liburua,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="liburua_show", methods={"GET"})
     * @param Liburua $liburua
     *
     * @return Response
     */
    public function show(Liburua $liburua): Response
    {
        return $this->render('liburua/show.html.twig', [
            'liburua' => $liburua,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="liburua_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Liburua $liburua
     *
     * @return Response
     */
    public function edit(Request $request, Liburua $liburua): Response
    {
        $form = $this->createForm(LiburuaType::class, $liburua);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('liburua_index');
        }

        return $this->render('liburua/edit.html.twig', [
            'liburua' => $liburua,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="liburua_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Liburua $liburua): Response
    {
        if ($this->isCsrfTokenValid('delete'.$liburua->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($liburua);
            $entityManager->flush();
        }

        return $this->redirectToRoute('liburua_index');
    }
}
