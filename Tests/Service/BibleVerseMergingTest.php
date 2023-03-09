<?php

namespace StevenBuehner\BibleVerseBundleTests\Service;

use PHPUnit\Framework\TestCase;
use StevenBuehner\BibleVerseBundle\Entity\BibleVerse;
use StevenBuehner\BibleVerseBundle\Service\BibleVerseService;

class BibleVerseMergingTest extends TestCase {

	/**
	 * @var BibleVerseService
	 */
	protected $bibleVerseService;

	public function testNotMergeableDifferentBooks() {
		$v1 = new BibleVerse();
		$v1->setVerse(1, 1, 1, 2, 1);

		$v2 = new BibleVerse();
		$v2->setVerse(2, 1, 1, 2, 1);

		$v3 = new BibleVerse();
		$v3->setVerse(3, 1, 1, 2, 1);

		$result = $this->bibleVerseService->mergeBibleverses(array($v3, $v1, $v2));

		$this->assertCount(3, $result);
		$this->assertEquals($v1, $result[0]);
		$this->assertEquals($v2, $result[1]);
		$this->assertEquals($v3, $result[2]);
	}

	public function testNotMergeableDifferentChapters() {
		$v1 = new BibleVerse();
		$v1->setVerse(1, 1, 1, 1, 1);

		$v2 = new BibleVerse();
		$v2->setVerse(1, 4, 1, 4, 1);

		$v3 = new BibleVerse();
		$v3->setVerse(1, 8, 1, 8, 1);

		$result = $this->bibleVerseService->mergeBibleverses(array($v3, $v1, $v2));

		$this->assertCount(3, $result);
		$this->assertEquals($v1, $result[0]);
		$this->assertEquals($v2, $result[1]);
		$this->assertEquals($v3, $result[2]);
	}

	public function testNotMergeableDifferentVerses() {
		$v1 = new BibleVerse();
		$v1->setVerse(1, 1, 1, 1, 2);

		$v2 = new BibleVerse();
		$v2->setVerse(1, 1, 4, 1, 6);

		$v3 = new BibleVerse();
		$v3->setVerse(1, 1, 8, 1, 8);

		$result = $this->bibleVerseService->mergeBibleverses(array($v3, $v1, $v2));

		$this->assertCount(3, $result);
		$this->assertEquals($v1, $result[0]);
		$this->assertEquals($v2, $result[1]);
		$this->assertEquals($v3, $result[2]);
	}

	public function testMergingTwoOverlappingVerses() {
		$v1 = new BibleVerse();
		$v1->setVerse(1, 1, 1, 1, 5);

		$v2 = new BibleVerse();
		$v2->setVerse(1, 1, 4, 1, 6);

		$v3 = new BibleVerse();
		$v3->setVerse(1, 1, 8, 1, 8);

		$result = $this->bibleVerseService->mergeBibleverses(array($v3, $v1, $v2));

		$mergedResult = new BibleVerse();
		$mergedResult->setVerse(1, 1, 1, 1, 6);

		$this->assertCount(2, $result);
		$this->assertEquals($mergedResult, $result[0]);
		$this->assertEquals($mergedResult, $v1);
		$this->assertEquals($v3, $result[1]);
	}

	public function testMergingTwoAllignedVerses() {
		$v1 = new BibleVerse();
		$v1->setVerse(1, 1, 1, 1, 5);

		$v2 = new BibleVerse();
		$v2->setVerse(1, 1, 6, 1, 7);

		$v3 = new BibleVerse();
		$v3->setVerse(1, 1, 9, 1, 9);

		$result = $this->bibleVerseService->mergeBibleverses(array($v1, $v3, $v2));

		$mergedResult = new BibleVerse();
		$mergedResult->setVerse(1, 1, 1, 1, 7);

		$this->assertCount(2, $result);
		$this->assertEquals($mergedResult, $result[0]);
		$this->assertEquals($mergedResult, $v1);
		$this->assertEquals($v3, $result[1]);
	}

	public function testMergingTwoAllignedChapters() {
		$v1 = new BibleVerse();
		$v1->setVerse(1, 1, 1, 1, $this->bibleVerseService->getMaxVersOfBookKap(1, 1));

		$v2 = new BibleVerse();
		$v2->setVerse(1, 2, 1, 2, 7);

		$v3 = new BibleVerse();
		$v3->setVerse(1, 3, 9, 1, 9);

		$result = $this->bibleVerseService->mergeBibleverses(array($v1, $v3, $v2));

		$mergedResult = new BibleVerse();
		$mergedResult->setVerse(1, 1, 1, 2, 7);

		$this->assertCount(2, $result);
		$this->assertEquals($mergedResult, $result[0]);
		$this->assertEquals($mergedResult, $v1);
		$this->assertEquals($v3, $result[1]);
	}

	public function testMergeThreeWithSubset() {
		$v1 = new BibleVerse();
		$v1->setVerse(1, 1, 1, 1, $this->bibleVerseService->getMaxVersOfBookKap(1, 1));

		$v2 = new BibleVerse();
		$v2->setVerse(1, 2, 1, 3, 10);

		$v3 = new BibleVerse();
		$v3->setVerse(1, 2, 9, 3, 9);

		$result = $this->bibleVerseService->mergeBibleverses(array($v1, $v3, $v2));

		$mergedResult = new BibleVerse();
		$mergedResult->setVerse(1, 1, 1, 3, 10);

		$this->assertCount(1, $result);
		$this->assertEquals($mergedResult, $result[0]);
		$this->assertEquals($mergedResult, $v1);
	}

	protected function setUp() {
		$this->bibleVerseService = new BibleVerseService();
	}
}