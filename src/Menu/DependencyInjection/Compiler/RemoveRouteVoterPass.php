<?php

namespace App\Menu\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This compiler pass filters the voters in the Matcher.
 */
final class RemoveRouteVoterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('knp_menu.matcher')) {
            return;
        }

        $definition = $container->getDefinition('knp_menu.matcher');

        // Remove the default 'knp_menu.voter.router' implementation that can't
        // handle osid_id_Id objects in routes.
        $originalVoters = $definition->getArgument(0)->getValues();
        $filteredVoters = [];
        foreach ($originalVoters as $voter) {
            if ('knp_menu.voter.router' != $voter) {
                $filteredVoters[] = $voter;
            }
        }

        $definition->replaceArgument(0, new IteratorArgument($filteredVoters));
    }
}
