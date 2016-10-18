<?php

namespace Sowp\RegistryBundle;


use Symfony\Component\Filesystem\Filesystem;

class WebPathResolver
{
    /** @var Filesystem */
    private $fs;
    private $webPathDir;

    public function __construct($webPathDir, Filesystem $fs)
    {
        $this->webPathDir = $webPathDir;
        $this->fs = $fs;
    }

    public function resolve($path)
    {
        $filename = basename($path);
        $dirname = dirname($path);

        $url = $this->fs->makePathRelative($dirname, $this->webPathDir);

        if (substr($url, 0, 3) === '../'){
            throw new \InvalidArgumentException("\"$path\" is not in web directory");
        }

        return '/'.$url.$filename;
    }
}