<?php

namespace Sowp\DashboardBundle\Dashboard;

class DashboardElement
{
    const TYPE_HTML = 'HTML';
    const TYPE_IMAGE = 'IMAGE';
    const TYPE_FONT_AWESOME = 'FONT_AWESOME';

    /** @var string */
    private $name;
    /** @var string(TYPE_HTML, TYPE_IMAGE, TYPE_FONT_AWESOME) */
    private $type;
    /** @var string */
    private $icon;
    /** @var string */
    private $href;

    /**
     * DashboardElement constructor.
     *
     * @param string $name
     * @param string $type
     * @param string $icon
     * @param $href
     */
    public function __construct($name, $type, $icon, $href)
    {
        $this->name = $name;
        $this->type = $type;
        $this->icon = $icon;
        $this->href = $href;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return mixed
     */
    public function getHref()
    {
        return $this->href;
    }
}
