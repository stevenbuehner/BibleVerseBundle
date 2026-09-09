<?php

namespace StevenBuehner\BibleVerseBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BibleVerseExtension extends Extension {
	/**
	 * {@inheritdoc}
	 * @throws \Exception
	 */
	public function load(array $configs, ContainerBuilder $container): void {

		$configuration = new Configuration();
		$config        = $this->processConfiguration($configuration, $configs);

		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

		// Überprüfe, ob die 'services.yml' Datei existiert
		$servicesFile = __DIR__ . '/../Resources/config/services.yml';
		if (file_exists($servicesFile)) {
			$loader->load('services.yml');
		} else {
			throw new \RuntimeException("Die Datei 'services.yml' wurde nicht gefunden.");
		}

	}
}
