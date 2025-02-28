<?php

namespace App\Routing;

use App\Service\Osid\IdMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * This service converts controller values to Osid Id objects if type-hinted.
 */
class OsidValueResolver implements ValueResolverInterface
{
    public function __construct(
        private IdMap $osidIdMap,
    ) {
    }

    /**
     * Returns the possible value(s).
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // get the argument type (e.g. \osid_id_Id, \osid_type_Type)
        $argumentType = $argument->getType();
        if ($argumentType
            && (in_array($argumentType, [\osid_id_Id::class, \osid_type_Type::class])
                || is_subclass_of($argumentType, \osid_id_Id::class, true)
                || is_subclass_of($argumentType, \osid_type_Type::class, true)
            )
        ) {
            // get the value from the request, based on the argument name
            $value = $request->attributes->get($argument->getName());
            if (!is_string($value)) {
                return [];
            }
            // create and return the value object
            if (\osid_id_Id::class == $argumentType || is_subclass_of($argumentType, \osid_id_Id::class, true)) {
                return [$this->osidIdMap->fromString($value)];
            }
            if (\osid_type_Type::class == $argumentType || is_subclass_of($argumentType, \osid_type_Type::class, true)) {
                return [$this->osidIdMap->typeFromString($value)];
            }
        }

        return [];
    }
}
