<?php

namespace App\Menu\Matcher\Voter;

use App\Service\Osid\IdMap;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Voter based on the route with support of osid_id_Id arguments.
 *
 * A copy of Knp\Menu\Matcher\Voter\RouteVoter
 */
#[AutoconfigureTag('knp_menu.voter')]
class OsidRouteVoter implements VoterInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private IdMap $osidIdMap,
    ) {
    }

    public function matchItem(ItemInterface $item): ?bool
    {
        if (\method_exists($this->requestStack, 'getMainRequest')) {
            $request = $this->requestStack->getMainRequest();   // symfony 5.3+
        } else {
            $request = $this->requestStack->getMasterRequest();
        }

        if (null === $request) {
            return null;
        }

        $route = $request->attributes->get('_route');
        if (null === $route) {
            return null;
        }

        $routes = (array) $item->getExtra('routes', []);

        foreach ($routes as $testedRoute) {
            if (\is_string($testedRoute)) {
                $testedRoute = ['route' => $testedRoute];
            }

            if (!\is_array($testedRoute)) {
                throw new \InvalidArgumentException('Routes extra items must be strings or arrays.');
            }

            if ($this->isMatchingRoute($request, $testedRoute)) {
                return true;
            }
        }

        return null;
    }

    /**
     * @phpstan-param array{route?: string|null, pattern?: string|null, parameters?: array<string, mixed>, query_parameters?: array<string, string>} $testedRoute
     */
    private function isMatchingRoute(Request $request, array $testedRoute): bool
    {
        $route = $request->attributes->get('_route');

        if (isset($testedRoute['route'])) {
            if ($route !== $testedRoute['route']) {
                return false;
            }
        } elseif (!empty($testedRoute['pattern'])) {
            if (!\preg_match($testedRoute['pattern'], $route)) {
                return false;
            }
        } else {
            throw new \InvalidArgumentException('Routes extra items must have a "route" or "pattern" key.');
        }

        return $this->isMatchingParameters($request, $testedRoute) && $this->isMatchingQueryParameters($request, $testedRoute);
    }

    /**
     * @phpstan-param array{route?: string|null, pattern?: string|null, parameters?: array<string, mixed>, query_parameters?: array<string, string>} $testedRoute
     */
    private function isMatchingParameters(Request $request, array $testedRoute): bool
    {
        if (!isset($testedRoute['parameters'])) {
            return true;
        }

        // Middlebury customization: Include all query parameters when testing
        // for matches. We aren't exclusively using positional parameters.
        $routeParameters = array_merge($request->attributes->get('_route_params', []), $request->query->all());

        foreach ($testedRoute['parameters'] as $name => $value) {
            if (!isset($routeParameters[$name])) {
                return false;
            }
            $routeParameterValue = $routeParameters[$name];
            // Middlebury customization to not fail on osid_id_Id objects.
            if ($routeParameterValue instanceof \osid_id_Id) {
                $routeParameterValue = $this->osidIdMap->toString($routeParameterValue);
            } elseif ($routeParameterValue instanceof \osid_type_Type) {
                $routeParameterValue = $this->osidIdMap->typeToString($routeParameterValue);
            }

            // Middlebury customization to not fail on osid_id_Id objects.
            if ($value instanceof \osid_id_Id) {
                $value = $this->osidIdMap->toString($value);
            } elseif ($value instanceof \osid_type_Type) {
                $value = $this->osidIdMap->typeToString($value);
            }

            // Middlebury customization to not fail on array parameters for
            // multi-select form parameters.
            if (is_array($routeParameterValue) || is_array($value)) {
                sort($routeParameterValue);
                sort($value);
                foreach ($routeParameterValue as $i => $v) {
                    if ((string) $routeParameterValue[$i] !== (string) $value[$i]) {
                        return false;
                    }
                }
            } else {
                // cast both to string so that we handle integer and other non-string parameters, but don't stumble on 0 == 'abc'.
                if ((string) $routeParameterValue !== (string) $value) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @phpstan-param array{route?: string|null, pattern?: string|null, parameters?: array<string, mixed>, query_parameters?: array<string, string>} $testedRoute
     */
    private function isMatchingQueryParameters(Request $request, array $testedRoute): bool
    {
        if (!isset($testedRoute['query_parameters'])) {
            return true;
        }

        $routeQueryParameters = $request->query->all();

        foreach ($testedRoute['query_parameters'] as $name => $value) {
            // cast both to string so that we handle integer and other non-string parameters, but don't stumble on 0 == 'abc'.
            if (!isset($routeQueryParameters[$name]) || \is_array($routeQueryParameters[$name]) || (string) $routeQueryParameters[$name] !== (string) $value) {
                return false;
            }
        }

        return true;
    }
}
