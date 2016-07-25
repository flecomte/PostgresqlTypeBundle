<?php
namespace FLE\Bundle\PostgresqlTypeBundle\DependencyInjection;

use FLE\Bundle\PostgresqlTypeBundle\Doctrine\ORM\Query\AST\Functions\ContainsFunction;
use FLE\Bundle\PostgresqlTypeBundle\Doctrine\ORM\Query\AST\Functions\DateTruncFunction;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FLEPostgresqlTypeExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (isset($bundles['DoctrineBundle'])) {
            $config = [
                'orm' => [
                    'dql' => [
                        'datetime_functions' => [
                            'date_trunc' => DateTruncFunction::class
                        ],
                        'string_functions' => [
                            'contains' => ContainsFunction::class
                        ]
                    ]
                ]
            ];
            if ($container->hasExtension('doctrine')) {
                $container->prependExtensionConfig('doctrine', $config);
            }
        }
    }
}