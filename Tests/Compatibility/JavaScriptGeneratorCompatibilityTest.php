<?php

namespace StevenBuehner\BibleVerseBundleTests\Compatibility;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use StevenBuehner\JS\Generator\BibleVerseGenerator;

class JavaScriptGeneratorCompatibilityTest extends TestCase {

	public function testTwigThreeGeneratorReproducesThePublishedJavaScriptExports(): void {
		$outputDirectory = sys_get_temp_dir() . '/bible-verse-bundle-' . bin2hex(random_bytes(8));

		if (!mkdir($outputDirectory, 0700) && !is_dir($outputDirectory)) {
			throw new RuntimeException("Could not create temporary directory: {$outputDirectory}");
		}

		try {
			(new BibleVerseGenerator())->run($outputDirectory);

			foreach (['de', 'en'] as $language) {
				$fileName = "BibleVerseService_{$language}.js";
				$this->assertFileEquals(
					__DIR__ . "/../../js/out/{$fileName}",
					"{$outputDirectory}/{$fileName}",
					"The generated {$language} JavaScript export changed unexpectedly."
				);
			}
		} finally {
			foreach (['de', 'en'] as $language) {
				$file = "{$outputDirectory}/BibleVerseService_{$language}.js";
				if (is_file($file)) {
					unlink($file);
				}
			}

			rmdir($outputDirectory);
		}
	}
}
