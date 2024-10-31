<?php

namespace App\Routing;

use App\Service\Osid\IdMap;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

/**
 * Decorate the Router service to convert OSID objects to string IDs in URLs.
 */
class OsidRouter implements RouterInterface, WarmableInterface
{
    public function __construct(
        private RouterInterface $router,
        private IdMap $osidIdMap,
    ) {
    }

    /**
     * Gets the RouteCollection instance associated with this Router.
     *
     * WARNING: This method should never be used at runtime as it is SLOW.
     *          You might use it in a cache warmer though.
     *
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->router->getRouteCollection();
    }

    /**
     * Tries to match a URL path with a set of routes.
     *
     * If the matcher cannot find information, it must throw one of the exceptions documented
     * below.
     *
     * @param string $pathinfo The path info to be parsed (raw format, i.e. not urldecoded)
     *
     * @throws NoConfigurationException  If no routing configuration could be found
     * @throws ResourceNotFoundException If the resource could not be found
     * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
     */
    public function match(string $pathinfo): array
    {
        return $this->router->match($pathinfo);
    }

    /**
     * Sets the request context.
     *
     * @return void
     */
    public function setContext(RequestContext $context)
    {
        return $this->router->setContext($context);
    }

    /**
     * Gets the request context.
     */
    public function getContext(): RequestContext
    {
        return $this->router->getContext();
    }

    /**
     * Warms up the cache.
     *
     * @param string      $cacheDir Where warm-up artifacts should be stored
     * @param string|null $buildDir Where read-only artifacts should go; null when called after compile-time
     *
     * @return string[] A list of classes or files to preload on PHP 7.4+
     */
    public function warmUp(string $cacheDir /* , string $buildDir = null */)
    {
        return call_user_func_array([$this->router, 'warmUp'], func_get_args());
    }

    /**
     * Generates a URL or path for a specific route based on the given parameters.
     *
     * Parameters that reference placeholders in the route pattern will substitute them in the
     * path or host. Extra params are added as query string to the URL.
     *
     * When the passed reference type cannot be generated for the route because it requires a different
     * host or scheme than the current one, the method will return a more comprehensive reference
     * that includes the required params. For example, when you call this method with $referenceType = ABSOLUTE_PATH
     * but the route requires the https scheme whereas the current scheme is http, it will instead return an
     * ABSOLUTE_URL with the https scheme and the current host. This makes sure the generated URL matches
     * the route in any case.
     *
     * If there is no route with the given name, the generator must throw the RouteNotFoundException.
     *
     * The special parameter _fragment will be used as the document fragment suffixed to the final URL.
     *
     * @throws RouteNotFoundException              If the named route doesn't exist
     * @throws MissingMandatoryParametersException When some parameters are missing that are mandatory for the route
     * @throws InvalidParameterException           When a parameter value for a placeholder is not correct because
     *                                             it does not match the requirement
     */
    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        return $this->router->generate($name, $this->stringifyOsidObjects($parameters), $referenceType);
    }

    /**
     * Convert osid_id_Id or osid_type_Type objects to string representations.
     *
     * @return array
     *               The parameters with objects mapped to strings
     */
    private function stringifyOsidObjects(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            if (is_object($value)) {
                if ($value instanceof \osid_id_Id) {
                    $parameters[$key] = $this->osidIdMap->toString($value);
                } elseif ($value instanceof \osid_type_Type) {
                    $parameters[$key] = $this->osidIdMap->typeToString($value);
                } elseif ($value instanceof \osid_OsidObject) {
                    $parameters[$key] = $this->osidIdMap->toString($value->getId());
                }
            }
        }

        return $parameters;
    }
}
