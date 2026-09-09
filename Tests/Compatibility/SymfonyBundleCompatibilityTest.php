<?php

namespace StevenBuehner\BibleVerseBundleTests\Compatibility;

use PHPUnit\Framework\TestCase;
use StevenBuehner\BibleVerseBundle\BibleVerseBundle;
use StevenBuehner\BibleVerseBundle\DependencyInjection\BibleVerseExtension;
use StevenBuehner\BibleVerseBundle\Service\BibleVerseService;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SymfonyBundleCompatibilityTest extends TestCase {

	public function testBundleLoadsItsServiceDefinitionOnSupportedSymfonyVersions(): void {
		$bundle = new BibleVerseBundle();
		$this->assertSame('BibleVerseBundle', $bundle->getName());

		$container = new ContainerBuilder();
		$extension = new BibleVerseExtension();
		$extension->load([], $container);

		$this->assertTrue($container->hasDefinition('bible_verse.helper'));

		$definition = $container->getDefinition('bible_verse.helper');
		$this->assertSame(BibleVerseService::class, $definition->getClass());
		$this->assertTrue($definition->isPublic());
	}
}
