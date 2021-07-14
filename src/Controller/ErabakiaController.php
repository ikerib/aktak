<?php

namespace App\Controller;

use App\Entity\Erabakia;
use App\Form\AdminErabakiaSearchFormType;
use App\Form\ErabakiaSearchFormType;
use App\Form\ErabakiaType;
use App\Form\LiburuaSearchFormType;
use App\Repository\ErabakiaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/kudeatu/erabakia")
 */
class ErabakiaController extends AbstractController
{
    /**
     * @Route("/", name="erabakia_index", methods={"GET"})
     * @param Request            $request
     * @param ErabakiaRepository $erabakiaRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(Request $request, ErabakiaRepository $erabakiaRepository, PaginatorInterface $paginator): Response
    {
        $searchForm = $this->createForm( AdminErabakiaSearchFormType::class, null, [
            'action' => $this->generateUrl( 'erabakia_index' ),
            'method' => 'GET',
        ] );

        $searchForm->handleRequest( $request );
        $filter = null;
        $extra = null;

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $filter = $searchForm->getData();
            $extra['testua'] = $searchForm->get( 'testua' )->getData();
            $extra['datatik'] = $searchForm->get( 'datatik' )->getData();
            $extra['datara'] = $searchForm->get( 'datara' )->getData();
        }

        $query = $erabakiaRepository->getAllQuery($filter, $extra);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('erabakia/index.html.twig', [
            'erabakias'      => $pagination,
            'searchform'    => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/new", name="erabakia_new", methods={"GET","POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $erabakium = new Erabakia();
        $form = $this->createForm(ErabakiaType::class, $erabakium);
        $form->handleRequest($request);

        $errors = $validator->validate($erabakium);

        if ( count($errors) > 0 ) {
            return $this->render('erabakia/new.html.twig', [
                'errors' => $errors,
                'erabakium' => $erabakium,
                'form' => $form->createView(),
            ]);
        } else {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($erabakium);
                $entityManager->flush();

                return $this->redirectToRoute('erabakia_index');
            }
        }

        return $this->render('erabakia/new.html.twig', [
            'erabakium' => $erabakium,
            'form' => $form->createView(),
            'errors' => null
        ]);
    }

    /**
     * @Route("/{id}", name="erabakia_show", methods={"GET"})
     * @param Erabakia $erabakium
     *
     * @return Response
     */
    public function show(Erabakia $erabakium): Response
    {
        return $this->render('erabakia/show.html.twig', [
            'erabakium' => $erabakium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="erabakia_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Erabakia $erabakium): Response
    {
        $form = $this->createForm(ErabakiaType::class, $erabakium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('erabakia_index');
        }

        return $this->render('erabakia/edit.html.twig', [
            'erabakium' => $erabakium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="erabakia_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Erabakia $erabakium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$erabakium->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($erabakium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('erabakia_index');
    }
}
