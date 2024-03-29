<?php

namespace Maba\Bundle\TwigTemplateModificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('maba_twig_template_modification');

        $rootNode->children()->arrayNode('paths_to_process')->defaultValue(
            [
                '%kernel.root_dir%/Resources/views',
                '%kernel.root_dir%/Resources/views/**',
                '%kernel.root_dir%/../src/{,*/,*/*/}*Bundle/Resources/views',
                '%kernel.root_dir%/../src/{,*/,*/*/}*Bundle/Resources/views/**',

            ]
        )->prototype('scalar');

        return $treeBuilder;
    }
}
