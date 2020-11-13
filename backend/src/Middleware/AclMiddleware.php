<?php

namespace App\Middleware;

use InvalidArgumentException;
use Slim\Http\Request;
use Slim\Http\Response;
use Geggleto\Acl\AclRepository;

class AclMiddleware implements MiddlewareInterface 
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param Container $container
     */
    public function __construct($container) 
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(Request $request, Response $response, callable $next) 
    {
        $authService = $this->container->authService->getPermissions();
        /*
         * Verifica se a rota informada pelo usuário existe
         */
        if (!in_array($request->getUri()->getPath(), $authService['resources'])) {
            return $next($request, $response);
        } 
        else {
            /** @var AclRepository $aclRepo */
            $aclRepo = new AclRepository($authService['roles'], $this->container["authService"]->getPermissions());
            $allowed = false;
            $route = '/' . ltrim($request->getUri()->getPath(), '/');
            
            try {
                $allowed = $aclRepo->isAllowedWithRoles($aclRepo->getRole(), $route);
            } 
            catch (InvalidArgumentException $iae) { //This is executed in cases where there is a route parameters... /user/{id:} 
                $fn = function (ServerRequestInterface $requestInterface, AclRepository $aclRepo) {
                    $route = $requestInterface->getAttribute('route'); // Grab the route to get the pattern
                    if (!empty($route)) {
                        foreach ($aclRepo->getRole() as $role) {
                            if ($aclRepo->isAllowed($role, $route->getPattern())) { // check to see fi the user can access the pattern
                                return true; //Is allowed
                            }
                        }
                    }
                    return false;
                };

                $allowed = $fn($request, $aclRepo);
            }

            if ($allowed) {
                return $next($request, $response);
            } else {
                return $response->withStatus(403)->write($this->container['twig']->fetch('app/error/403.twig'));
            }
        }
    }

}
