<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use App\Service\Osid\IdMap;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OsidIdExtension extends AbstractExtension
{

    /**
     * @var \App\Service\Osid\IdMap
     *   The IdMap service.
     */
    private $idMap;

    /**
     * Create a new instance of this service.
     *
     * @param \App\Service\Osid\IdMap
     *   The IdMap service.
     */
    public function __construct(IdMap $idMap) {
        $this->idMap = $idMap;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('osidIdToString', [$this, 'osidIdToString']),
        ];
    }

    public function osidIdToString(\osid_id_Id $id): string
    {
        return $this->idMap->toString($id);
    }
}
