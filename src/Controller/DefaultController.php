<?php

namespace App\Controller;

use App\Form\ErabakiaSearchFormType;
use App\Repository\ErabakiaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     * @param Request            $request
     * @param ErabakiaRepository $erabakiaRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(Request $request, ErabakiaRepository $erabakiaRepository, PaginatorInterface $paginator): Response
    {
        $searchForm = $this->createForm( ErabakiaSearchFormType::class, null, [
            'action' => $this->generateUrl( 'default' ),
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

        return $this->render( 'default/index.html.twig' , [
            'erabakias'      => $pagination,
            'searchform'    => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/vue", name="vue")
     */
    public function vue(): Response
    {
        return $this->render( 'vue.html.twig' );
    }
}
