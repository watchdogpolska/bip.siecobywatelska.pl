<?php

namespace Sowp\RegistryBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sowp\RegistryBundle\Entity\Attribute;
use Sowp\RegistryBundle\Entity\Row;
use Sowp\RegistryBundle\Entity\Value;
use Sowp\RegistryBundle\Http\FileDownloadResponse;
use Sowp\RegistryBundle\Mapper\RegistryToCsvMapper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sowp\RegistryBundle\Entity\Registry;

/**
 * Registry controller.
 *
 * @Route("/registry")
 */
class RegistryController extends Controller
{
    /**
     * Lists all Registry entities.
     *
     * @Route("/", name="registry_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $registries = $em->getRepository('SowpRegistryBundle:Registry')->findAll();

        return $this->render('@SowpRegistry/registry/index.html.twig', array(
            'registries' => $registries,
        ));
    }

    /**
     * Lists all Row entities.
     *
     * @Route("/{registry_id}", name="registry_show")
     * @ParamConverter("registry", options={"id" = "registry_id"})
     * @Method("GET")
     */
    public function showAction(Registry $registry)
    {
        $rows = $registry->getRows();

        return $this->render('@SowpRegistry/registry/show.html.twig', array(
            'registry' => $registry,
            'rows' => $rows,
        ));
    }

    /**
     * Download a Row entity as CSV.
     *
     * @Route("/{id}/csv", name="registry_export_csv")
     */
    public function downloadCsvAction(Request $request, Registry $registry)
    {
        $mapper = new RegistryToCsvMapper($registry);
        return $mapper->getResponse();
    }
}
