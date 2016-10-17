<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Sowp\RegistryBundle\Entity\Attribute;
use Sowp\RegistryBundle\Entity\Registry;
use Sowp\RegistryBundle\Entity\Row;
use Sowp\RegistryBundle\Entity\ValueFile;
use Sowp\RegistryBundle\Entity\ValueText;

class RegistryContext implements Context
{
    use Behat\Symfony2Extension\Context\KernelDictionary;
    use DoctrineDictrionary;

    private $faker;
    /** @var $lastRegistry Registry */
    private $lastRegistry;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('pl_PL');
    }

    /**
     * @Given /^The registry "([^"]*)" should exists$/
     */
    public function theRegistryExists($name)
    {
        $this->lastRegistry = $this->createRegistry($name);
    }


    /**
     * @Given have attributes:
     */
    public function theRegistryShouldHaveAttributes(TableNode $table)
    {
        $em = $this->getManager();
        $register = $this->lastRegistry;
        $attributes = array_map(function($row){
            $attribute = new Attribute();
            $type = isset($row['Type']) ? $row['Type'] : Attribute::TYPE_TEXT;
            $attribute->setName($row['Name']);
            $attribute->setType($type);
            if(isset($row['Description']) && !empty($row['Description'])){
                $attribute->setDescription($row['Description']);
            }

            return $attribute;
        }, $table->getColumnsHash());

        foreach($attributes as $attribute){
            $register->addAttribute($attribute);
            $em->persist($attribute);
        }
        $em->flush();
    }

    /**
     * @Given have :count rows
     */
    public function theRegistryShouldHaveCountRows($count)
    {
        $em = $this->getManager();
        $register = $this->lastRegistry;

        $attributes = $register->getAttributes();

        $values = $attributes->map(function(Attribute $attr) use ($em) {
            $value = $attr->createValue();
            if($value== Attribute::TYPE_TEXT){
                $value->setText($this->faker->text());
            }
            $em->persist($value);
        });

        foreach($values as $value){
            $register->addRow($value);
        }

        $em->flush();
    }

    /**
     * @Given have rows:
     */
    public function theRegistryShouldHaveRows(TableNode $table)
    {
        $em = $this->getManager();
        $registry = $this->lastRegistry;

        $attributes = $registry->getAttributes();
        $attributesNames = $attributes->map(function(Attribute $entry){
            return $entry->getName();
        })->toArray();
        /** @var Attribute[] $attributesByName */
        $attributesByName = array_combine($attributesNames, $attributes->toArray());

        foreach($attributesNames as $name){
            assertArrayHasKey($name, $table->getColumnsHash()[0]);
        }

        foreach($table->getHash() as $data){
            $row = new Row();
            foreach($data as $key => $text) {
                $value = $attributesByName[$key]->createValue();
                $value->setText($text);
                $row->addValue($value);
                $em->persist($value);
            }
            $row->setRegistry($registry);
            $em->persist($row);
        }

        $em->flush();
    }

    private function createRegistry($name, $description = null)
    {
        $register = new Registry();
        $register->setName($name);
        if($description){
            $register->setDescription($description);
        }
        $this->getManager()->persist($register);
        $this->getManager()->flush();
        return $register;
    }


}
