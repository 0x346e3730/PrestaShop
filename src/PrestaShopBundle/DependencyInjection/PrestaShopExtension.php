<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace PrestaShopBundle\DependencyInjection;

use PrestaShop\PrestaShop\Adapter\Module\Repository\ModuleRepository;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Adds main PrestaShop core services to the Symfony container.
 */
class PrestaShopExtension extends Extension implements PrependExtensionInterface
{
    public function __construct()
    {
        $this->activeModulesPaths = (new ModuleRepository(_PS_ROOT_DIR_, _PS_MODULE_DIR_))->getActiveModulesPaths();
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new AddOnsConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $env = $container->getParameter('kernel.environment');
        $loader->load('services_' . $env . '.yml');

        $container->setParameter('prestashop.addons.categories', $config['addons']['categories']);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new AddOnsConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'prestashop';
    }

    public function prepend(ContainerBuilder $container)
    {
        foreach ($this->activeModulesPaths as $modulePath) {
            if (str_ends_with($modulePath, 'api_module')) {
                $moduleResourcesPath = sprintf('%s/config/api_platform', $modulePath);
                if (file_exists($moduleResourcesPath)) {
                    $container->prependExtensionConfig('api_platform', ['mapping' => ['paths' => [$moduleResourcesPath]]]);
                }
            }
        }
    }
}
