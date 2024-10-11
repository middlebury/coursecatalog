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
            new TwigFunction('osidIdsEqual', [$this, 'osidIdsEqual']),
            new TwigFunction('osidIdInArray', [$this, 'osidIdInArray']),
            new TwigFunction('osidTypeInArray', [$this, 'osidTypeInArray']),
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

    public function osidIdsEqual(\osid_id_Id $id1, \osid_id_Id $id2): bool
    {
        return $id1->isEqual($id2);
    }

    public function osidIdInArray(\osid_id_Id $id, iterable $ids): bool
    {
        foreach ($ids as $comparison) {
            if ($id->isEqual($comparison)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function osidTypeInArray(\osid_type_Type $type, iterable $types): bool
    {
        foreach ($types as $comparison) {
            if ($type->isEqual($comparison)) {
                return TRUE;
            }
        }
        return FALSE;
    }
}
