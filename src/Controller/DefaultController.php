<?php

namespace App\Controller;

use App\Entity\Erabakia;
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
     * @Route("/{_locale}", locale="eu", name="default")
     * @param Request $request
     * @param ErabakiaRepository $erabakiaRepository
     * @return Response
     */
    public function index(Request $request, ErabakiaRepository $erabakiaRepository): Response
    {
        $searchForm = $this->createForm( ErabakiaSearchFormType::class, null, [
            'action' => $this->generateUrl( 'default' ),
            'method' => 'POST',
        ] );

        $searchForm->handleRequest( $request );
        $filter = null;
        $extra = null;

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $filter = $searchForm->getData();
            $extra['testua'] = $searchForm->get('testua')->getData();
            $extra['datatik'] = $searchForm->get('datatik')->getData();
            $extra['datara'] = $searchForm->get('datara')->getData();
            $query = $erabakiaRepository->getAllInternet($filter, $extra);
        }

        if (!isset($query)){
            $filter = new Erabakia();
            $extra['testua'] = 'Bilaketa testua / Texo a buscar';
            $extra['datatik'] = '2222-01-01';
            $extra['datara'] = '3333-01-01';
            $query = $erabakiaRepository->getAllInternet($filter, $extra);
        }

        $erabakias = $query->getResult();
        return $this->render( 'default/index.html.twig' , [
            'erabakias'      => $erabakias,
            'searchform'    => $searchForm->createView()
        ]);
    }

}
