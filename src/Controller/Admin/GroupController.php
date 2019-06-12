<?php

namespace App\Controller\Admin;

use App\Template\Annotation\MenuItem;
use App\Entity\Group\Group;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Group controller.
 *
 * @Route("/admin/group")
 */
class GroupController extends AbstractController
{
    /**
     * Lists all groups.
     *
     * @MenuItem(title="Groepen")
     * @Route("/", name="admin_group_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groups = $em->getRepository(Group::class)->findAll();

        return $this->render('admin/group/index.html.twig', [
            'groups' => $groups,
        ]);
    }
}
