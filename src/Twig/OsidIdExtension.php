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
            new TwigFunction('osidTypeToString', [$this, 'osidTypeToString']),
        ];
    }

    public function osidIdToString(\osid_id_Id $id): string
    {
        return $this->idMap->toString($id);
    }

    public function osidTypeToString(\osid_type_Type $type): string
    {
        return $this->idMap->typeToString($type);
    }
}
