<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sowp\RegistryBundle\Entity\Attribute;
use Sowp\RegistryBundle\Entity\Registry;
use Sowp\RegistryBundle\Entity\Row;
use Sowp\RegistryBundle\Entity\Value;
use Sowp\RegistryBundle\Entity\ValueFile;
use Sowp\RegistryBundle\Entity\ValueText;

class LoadRegistryData implements FixtureInterface
{
    private $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create('pl_PL');
    }

    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 4; $i++){
            $registry = $this->generateRegistry();

            // Generate attributes
            $attrCount = $this->faker->numberBetween(3, 8);
            for($j = 0; $j < $attrCount; $j++){
                $attr = $this->generateAttribute();
                $registry->addAttribute($attr);
                $manager->persist($attr);
            }

            // Generate rows
            $rowCount = $this->faker->numberBetween(3, 40);
            for($j = 0; $j < $rowCount; $j ++){
                $row = $this->generateRow($registry);
                $registry->addRow($row);
                $manager->persist($row);
            }

            $manager->persist($registry);
        }
        $manager->flush();
    }

    public function generateRegistry(){
        $registry = new Registry();
        $registry->setName($this->faker->text(100));
        $type = $this->faker->randomElement(array(Registry::TYPE_TABLE, Registry::TYPE_LIST));
        $registry->setType($type);

        if($this->faker->boolean(40)){
            $registry->setDescription($this->faker->text());
        }
        return $registry;
    }

    public function generateAttribute(){
        $attribute = new Attribute();
        $attribute->setName($this->faker->text(30));
        if($this->faker->boolean(30)){
            $attribute->setDescription($this->faker->text());
        }
        if($this->faker->boolean(10)){
            $attribute->setType(Attribute::TYPE_FILE);
        }else{
            $attribute->setType(Attribute::TYPE_TEXT);
        }

        return $attribute;
    }

    public function generateRow(Registry $registry){
        $row = new Row();
        /** @var Attribute $attr */
        foreach($registry->getAttributes() as $attr){
            if($attr->getType() == Attribute::TYPE_FILE)
            {
                $value = new ValueFile();
                $value->setFile('http://lorempixel.com/400/400/cats/' . $this->faker->numberBetween(1, 10));
            } else {
                $value = new ValueText();
                $value->setText($this->faker->text());
            }

            $value->setAttribute($attr);
            $row->addValue($value);
        }

        return $row;
    }

}
