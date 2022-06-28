<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ModalController extends AbstractController {

    /**
     * @Route("/show_delete_modal", name="show_delete_modal")
     */
    public function showDeleteModal (Request $request): Response
    {
        $cat_id = $request->query->get('cat_id');
        return $this->render('/admin/dashboard/modals/delete_confirm.html.twig', ['cat_id' => $cat_id]);
    }

}