<?php

namespace Sowp\RegistryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Sowp\RegistryBundle\Repository\ValueRepository")
 */
class ValueText extends Value
{
    /**
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * Set value
     *
     * @param string $value
     *
     * @return ValueText
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    public function __toString()
    {
        return $this->getText();
    }

    public function getType()
    {
        return Attribute::TYPE_TEXT;
    }
}
