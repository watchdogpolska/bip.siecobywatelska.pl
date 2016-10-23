<?php

namespace Sowp\RegistryBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sowp\RegistryBundle\Entity\Attribute;
use Sowp\RegistryBundle\Entity\Registry;
use Sowp\RegistryBundle\Entity\Row;
use Sowp\RegistryBundle\Entity\Value;
use Sowp\RegistryBundle\Entity\ValueFile;
use Sowp\RegistryBundle\Entity\ValueText;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * Api Registry controller.
 *
 * @Route("/api/registry")
 */
class ApiRegistryController extends Controller
{
    /**
     * Lists all Registry entities.
     *
     * @Route("/", name="api_registry_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $registries = $em->getRepository('SowpRegistryBundle:Registry')->findAll();

        $data = array_map([$this, 'serializeRegistry'], $registries);

        return $this->json($data);
    }

    /**
     * Detail Registry entities.
     *
     * @param Registry $registry
     * @return Response
     *
     * @Route("/{id}", name="api_registry_show")
     * @Method("GET")
     */
    public function showAction(Registry $registry)
    {
        $data = [
            'data' => $this->serializeRegistry($registry),
            '_references' => [
                'attributes' => $this->serializeAttributesInRegistry($registry)
            ]
        ];
        return $this->json($data);
    }

    /**
     * Detail Registry entities.
     *
     * @param Registry $registry
     * @return Response
     *
     * @Route("/{id}/row", name="api_registry_show_rows")
     * @Method("GET")
     */
    public function showRowsAction(Registry $registry)
    {
        $data = [
            'data' => $this->serializeRowsInRegistry($registry),
            '_references' => [
                'attributes' => $this->serializeAttributesInRegistry($registry)
            ]
        ];
        return $this->json($data);
    }

    /**
     * @param Registry $registry
     * @return array
     */
    private function serializeRegistry(Registry $registry)
    {
        $attributeIds = $registry->getAttributes()->map(function (Attribute $attribute) {
            return $attribute->getId();
        })->toArray();

        return array(
            'registry_id' => $registry->getId(),
            'name' => $registry->getName(),
            'attributes_ids' => $attributeIds,
            'links' => array(
                '_self' => $this->generateUrl(
                    'api_registry_show',
                    array('id' => $registry->getId())
                ),
                '_rows' => $this->generateUrl(
                    'api_registry_show_rows',
                    array('id' => $registry->getId())
                ),

            )
        );
    }

    /**
     * @param Attribute $attribute
     * @return array
     */
    private function serializeAttribute(Attribute $attribute)
    {
        return array(
            'attribute_id' => $attribute->getId(),
            'name' => $attribute->getName(),
            'description' => $attribute->getDescription(),
            'type' => $attribute->getType()
        );
    }

    /**
     * @param Registry $registry
     * @return array
     */
    private function serializeAttributesInRegistry(Registry $registry){
        $attributes = $registry->getAttributes()->toArray();
        return array_map([$this, 'serializeAttribute'], $attributes);
    }

    /**
     * @param Registry $registry
     * @return array
     */
    private function serializeRowsInRegistry(Registry $registry){
        return $registry->getRows()->map(function(Row $row){
            return $this->serializeRow($row);
        })->toArray();
    }

    /**
     * @param Row $row
     * @return array
     */
    private function serializeRow(Row $row){
        return [
            'row_id' => $row->getId(),
            'values' => $row->getValues()->map(function(Value $value){
                return $this->serializeValue($value);
            })
        ];
    }

    /**
     * @param Value $value
     * @return array
     */
    private function serializeValue(Value $value){
        $data = [
//            'value_id' => $value->getId(),
            'label' => $value->getLabel(),
            'attribute_id' => $value->getAttribute()->getId()
        ];
        if($value instanceof ValueFile){
            $data['path'] = $value->getPath();
            $data['mime-type'] = $value->getMimeType();
            $data['name'] = $value->getName();
            $data['size'] = $value->getSize();
        }else if($value instanceof ValueText){
            $data['text'] = $value->getText();
        }
        return $data;
    }
}