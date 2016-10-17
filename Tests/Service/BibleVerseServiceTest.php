<?php

namespace StevenBuehner\BibleVerseBundle\Tests\Service;

use StevenBuehner\BibleVerseBundle\Entity\BibleVerse;
use StevenBuehner\BibleVerseBundle\Service\BibleVerseService;

class BibleVerseServiceTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var BibleVerseService
	 */
	protected $bibleVerseService;

	public function testService() {
		$this->assertNotNull($this->bibleVerseService);
		$this->assertEquals(get_class($this->bibleVerseService),
							'StevenBuehner\BibleVerseBundle\Service\BibleVerseService');
	}

	public function testNormalBibleVerses() {
		$bv     = $this->bibleVerseService->stringToBibleVerse('1Tim 2,3');
		$bv_exp = new BibleVerse();
		$bv_exp->setVerse(54, 2, 3, 2, 3);
		$this->assertEquals($bv[0], $bv_exp);

		$bv = $this->bibleVerseService->stringToBibleVerse('1. Tim 2,3');
		$this->assertEquals($bv[0], $bv_exp);

		$bv = $this->bibleVerseService->stringToBibleVerse('1. Tim 2,3-3');
		$this->assertEquals($bv[0], $bv_exp);

		$bv = $this->bibleVerseService->stringToBibleVerse('1 Tim 2,3');
		$this->assertEquals($bv[0], $bv_exp);

		$bv = $this->bibleVerseService->stringToBibleVerse('1 Tim 2:3');
		$this->assertEquals($bv[0], $bv_exp);

		// Reverse check
		$bv_str = $this->bibleVerseService->bibleVerseToString($bv_exp, "long", "de");
		$this->assertEquals($bv_str, '1. Timotheus 2,3');
		$bv_str = $this->bibleVerseService->bibleVerseToString($bv_exp, "short", "de");
		$this->assertEquals($bv_str, '1Tim 2,3');
		$bv_str = $this->bibleVerseService->bibleVerseToString($bv_exp, "long", "en");
		$this->assertEquals($bv_str, '1 Timothy 2:3');
		$bv_str = $this->bibleVerseService->bibleVerseToString($bv_exp, "short", "en");
		$this->assertEquals($bv_str, '1Tim 2:3');
	}

	public function testBibelVerseCombinationString() {
		$bv = $this->bibleVerseService->stringToBibleVerse('1. Mose 2,1-3; 2. Mose 20,8-11; Daniel 1,8-20; Daniel 2,14-23; Daniel 2,23-26; Psalm 145; Matthäus 28,18-20; Johannes 6,35; 1. Timotheus 2,1-6');
		$this->assertCount(9, $bv);
	}

	public function testBibleVerseWithOnlyChapters() {
		/** @var $verse BibleVerse */
		$bv = $this->bibleVerseService->stringToBibleVerse('1. Mose 1');
		$this->assertTrue(count($bv) == 1);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 1);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 1);
		$this->assertEquals($verse->getToVerse(), 31);

		$bv = $this->bibleVerseService->stringToBibleVerse('1. Mose 1-3');
		$this->assertTrue(count($bv) == 1);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 1);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 3);
		$this->assertEquals($verse->getToVerse(), 24);

		$bv = $this->bibleVerseService->stringToBibleVerse('1. Mose 1-3,5');
		$this->assertTrue(count($bv) == 1);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 1);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 3);
		$this->assertEquals($verse->getToVerse(), 5);
	}

	public function testBibleVerseWithOblyMultipleChapters() {
		// 1.Mose 12+20; Jona 2+3;

		/** @var $verse BibleVerse */
		$bv = $this->bibleVerseService->stringToBibleVerse('1.Mose 12+20');
		$this->assertTrue(count($bv) == 2);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 12);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 12);
		$this->assertEquals($verse->getToVerse(), 20);
		$verse = $bv[1];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 20);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 20);
		$this->assertEquals($verse->getToVerse(), 18);

		$bv = $this->bibleVerseService->stringToBibleVerse('Jona 2+3');
		$this->assertTrue(count($bv) == 2);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 32);
		$this->assertEquals($verse->getFromChapter(), 2);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 2);
		$this->assertEquals($verse->getToVerse(), 11);
		$verse = $bv[1];
		$this->assertEquals($verse->getBookId(), 32);
		$this->assertEquals($verse->getFromChapter(), 3);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 3);
		$this->assertEquals($verse->getToVerse(), 10);
	}

	public function testCombination() {
		$bv = $this->bibleVerseService->stringToBibleVerse('1.Mose 12+20,4-20,6');
		$this->assertTrue(count($bv) == 2);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 12);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 12);
		$this->assertEquals($verse->getToVerse(), 20);
		$verse = $bv[1];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 20);
		$this->assertEquals($verse->getFromVerse(), 4);
		$this->assertEquals($verse->getToChapter(), 20);
		$this->assertEquals($verse->getToVerse(), 6);

		$bv = $this->bibleVerseService->stringToBibleVerse('Jesus Sirach 35,17-18.20-21');
		$this->assertTrue(count($bv) == 2);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 73);
		$this->assertEquals($verse->getFromChapter(), 35);
		$this->assertEquals($verse->getFromVerse(), 17);
		$this->assertEquals($verse->getToChapter(), 35);
		$this->assertEquals($verse->getToVerse(), 18);
		$verse = $bv[1];
		$this->assertEquals($verse->getBookId(), 73);
		$this->assertEquals($verse->getFromChapter(), 35);
		$this->assertEquals($verse->getFromVerse(), 20);
		$this->assertEquals($verse->getToChapter(), 35);
		$this->assertEquals($verse->getToVerse(), 21);

		$bv = $this->bibleVerseService->stringToBibleVerse('Jesus Sirach 16-17+19-20');
		$this->assertTrue(count($bv) == 2);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 73);
		$this->assertEquals($verse->getFromChapter(), 16);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 17);
		$this->assertEquals($verse->getToVerse(), 32);
		$verse = $bv[1];
		$this->assertEquals($verse->getBookId(), 73);
		$this->assertEquals($verse->getFromChapter(), 19);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 20);
		$this->assertEquals($verse->getToVerse(), 31);

		$bv = $this->bibleVerseService->stringToBibleVerse('Jesus Sirach 16-17+19-20+30-32,5');
		$this->assertCount(3, $bv);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 73);
		$this->assertEquals($verse->getFromChapter(), 16);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 17);
		$this->assertEquals($verse->getToVerse(), 32);
		$verse = $bv[1];
		$this->assertEquals($verse->getBookId(), 73);
		$this->assertEquals($verse->getFromChapter(), 19);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 20);
		$this->assertEquals($verse->getToVerse(), 31);
		$verse = $bv[2];
		$this->assertEquals($verse->getBookId(), 73);
		$this->assertEquals($verse->getFromChapter(), 30);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 32);
		$this->assertEquals($verse->getToVerse(), 5);
	}

	public function testBibleVerseFollowUp() {
		/** @var $verse BibleVerse */
		$bv = $this->bibleVerseService->stringToBibleVerse('1. Mose 1,4f');
		$this->assertTrue(count($bv) == 1);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 1);
		$this->assertEquals($verse->getFromVerse(), 4);
		$this->assertEquals($verse->getToChapter(), 1);
		$this->assertEquals($verse->getToVerse(), 5);

		$bv = $this->bibleVerseService->stringToBibleVerse('1. Mose 1,4ff');
		$this->assertTrue(count($bv) == 1);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 1);
		$this->assertEquals($verse->getFromVerse(), 4);
		$this->assertEquals($verse->getToChapter(), 1);
		$this->assertEquals($verse->getToVerse(), 31);

		$bv = $this->bibleVerseService->stringToBibleVerse('1. Mose 1,4-5f');
		$this->assertTrue(count($bv) == 1);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 1);
		$this->assertEquals($verse->getFromVerse(), 4);
		$this->assertEquals($verse->getToChapter(), 1);
		$this->assertEquals($verse->getToVerse(), 6);

		$bv = $this->bibleVerseService->stringToBibleVerse('1. Mose 1,4-5ff');
		$this->assertTrue(count($bv) == 1);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 1);
		$this->assertEquals($verse->getFromVerse(), 4);
		$this->assertEquals($verse->getToChapter(), 1);
		$this->assertEquals($verse->getToVerse(), 31);

		$bv = $this->bibleVerseService->stringToBibleVerse('1. Mose 1,4-5,3f');
		$this->assertTrue(count($bv) == 1);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 1);
		$this->assertEquals($verse->getFromVerse(), 4);
		$this->assertEquals($verse->getToChapter(), 5);
		$this->assertEquals($verse->getToVerse(), 4);

		$bv = $this->bibleVerseService->stringToBibleVerse('1. Mose 1,4-5,3ff');
		$this->assertTrue(count($bv) == 1);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 1);
		$this->assertEquals($verse->getFromChapter(), 1);
		$this->assertEquals($verse->getFromVerse(), 4);
		$this->assertEquals($verse->getToChapter(), 5);
		$this->assertEquals($verse->getToVerse(), 32);
	}

	public function testBibleChapterFollowUp() {
		$bv = $this->bibleVerseService->stringToBibleVerse('2. Mose 16f');
		$this->assertTrue(count($bv) == 1);
		$verse = $bv[0];
		$this->assertEquals($verse->getBookId(), 2);
		$this->assertEquals($verse->getFromChapter(), 16);
		$this->assertEquals($verse->getFromVerse(), 1);
		$this->assertEquals($verse->getToChapter(), 17);
		$this->assertEquals($verse->getToVerse(), 16);
	}

	public function testBibleVerseCreation() {
		$bv  = new BibleVerse();
		$bv2 = new BibleVerse();
		$this->assertTrue($bv !== $bv2);
	}

	public function testBibleVerseInit() {
		$bv = new BibleVerse();
		$bv->setVerse(4, 1, 2);
		$this->assertEquals($bv->getBookId(), 4);
		$this->assertEquals($bv->getFromChapter(), 1);
		$this->assertEquals($bv->getToChapter(), 1);
		$this->assertEquals($bv->getFromVerse(), 2);
		$this->assertEquals($bv->getToVerse(), 2);
	}

	public function testStringToBibleVerse() {
		$bh = $this->bibleVerseService;

		$tests["Mark 8,9"]    = "Markus 8,9";
		$tests["Mark 8,9-10"] = "Markus 8,9-10";

		$tests["1. Mose 3,4"]  = "1. Mose 3,4";
		$tests["1. Joh 5,5"]   = "1. Johannes 5,5";
		$tests["Joh 5,5"]      = "Johannes 5,5";
		$tests["Joh 5,1-47"]   = "Johannes 5";
		$tests["Joh 5,1-8,59"] = "Johannes 5-8";


		foreach ($tests as $input => $erwOutput) {
			$back = $bh->stringToBibleVerse($input);

			$this->assertNotNull($back, "Erwartet war: " . $erwOutput);
			$this->assertTrue(count($back) == 1, "Erwartet war: " . $erwOutput);
			$this->assertEquals($bh->bibleVerseToString($back[0]), $erwOutput);
		}
	}

	public function testStringToBibleVerseNegativ() {
		$bh = $this->bibleVerseService;

		$tests["Mark 8,9-7"]    = "";
		$tests["Mark 8,9-7,10"] = "";

		foreach ($tests as $input => $erwOutput) {
			$back = $bh->stringToBibleVerse($input);

			$this->assertNotNull($back);
			$this->assertTrue(count($back) == 0);
		}
	}

	public function testStringToBibleVerseDual() {
		$bh = $this->bibleVerseService;

		$tests["Mark 8,9+4"]    = array("Markus 8,9", "Markus 8,4");
		$tests["Mark 8,9+4+10"] = array("Markus 8,9", "Markus 8,4", "Markus 8,10");
		$tests["Mark 8,9-10.3"] = array("Markus 8,9-10", "Markus 8,3");
		$tests["1. Mose 3,4.3"] = array("1. Mose 3,4", "1. Mose 3,3");
		$tests["1. Joh 5,5.10"] = array("1. Johannes 5,5", "1. Johannes 5,10");
		$tests["Joh 5,5-5+3"]   = array("Johannes 5,5", "Johannes 5,3");

		foreach ($tests as $input => $erwOutput) {
			$back = $bh->stringToBibleVerse($input);

			$this->assertNotNull($back);
			$this->assertTrue(count($back) >= 2);

			foreach ($back as $key => $value) {
				$this->assertTrue(in_array($bh->bibleVerseToString($value), $erwOutput),
								  "Expected {$value} to be in array.");
			}
		}

	}

	public function testDefaultStringToBible() {
		$bh = $this->bibleVerseService;
		$bd = $bh->getBibleData();

		$languages[] = "de";
		$languages[] = "en";
		$sizes[]     = "short";
		$sizes[]     = "long";

		foreach ($languages as $lang) {
			foreach ($sizes as $size) {
				foreach ($bd as $bookID => $bookArray) {
					$vers1 = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,4");
					$this->assertNotNull($vers1,
										 "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,4 (ID:" . $bookID . ")");

					if ($vers1 != NULL) {
						$this->assertEquals($vers1[0]->getBookId(), $bookID);
						$this->assertEquals($vers1[0]->getFromChapter(), 1);
						$this->assertEquals($vers1[0]->getToChapter(), 1);
						$this->assertEquals($vers1[0]->getFromVerse(), 4);
						$this->assertEquals($vers1[0]->getToVerse(), 4);
					}
				}

				foreach ($bd as $bookID => $bookArray) {
					$vers1       = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,4f");
					$errorString = "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,4f (ID:" . $bookID . ")";
					$this->assertNotNull($vers1, $errorString);

					if ($vers1 != NULL) {
						$this->assertEquals($vers1[0]->getBookId(), $bookID);
						$this->assertEquals($vers1[0]->getFromChapter(), 1, $errorString);
						$this->assertEquals($vers1[0]->getToChapter(), 1, $errorString);
						$this->assertEquals($vers1[0]->getFromVerse(), 4, $errorString);
						$this->assertEquals($vers1[0]->getToVerse(), 5, $errorString);
					}
				}

				foreach ($bd as $bookID => $bookArray) {
					$vers1 = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,4-5");
					$this->assertNotNull($vers1,
										 "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,4-5 (ID:" . $bookID . ")");

					if ($vers1 != NULL) {
						$this->assertEquals($vers1[0]->getBookId(), $bookID);
						$this->assertEquals($vers1[0]->getFromChapter(), 1);
						$this->assertEquals($vers1[0]->getToChapter(), 1);
						$this->assertEquals($vers1[0]->getFromVerse(), 4);
						$this->assertEquals($vers1[0]->getToVerse(), 5);
					}
				}

				foreach ($bd as $bookID => $bookArray) {
					$vers1       = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,4-5f");
					$errorString = "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,4-5f (ID:" . $bookID . ")";

					$this->assertNotNull($vers1, $errorString);

					if ($vers1 != NULL) {
						$this->assertEquals($vers1[0]->getBookId(), $bookID);
						$this->assertEquals($vers1[0]->getFromChapter(), 1, $errorString);
						$this->assertEquals($vers1[0]->getToChapter(), 1, $errorString);
						$this->assertEquals($vers1[0]->getFromVerse(), 4, $errorString);
						$this->assertEquals($vers1[0]->getToVerse(), 6, $errorString);
					}
				}

				foreach ($bd as $bookID => $bookArray) {
					if ($bd[$bookID]["kapAnz"] > 1) {
						$vers1 = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,4-2,2");

						$this->assertNotNull($vers1,
											 "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,4-2,2 (ID:" . $bookID . ")");

						if ($vers1 != NULL) {
							$this->assertEquals($vers1[0]->getBookId(), $bookID);
							$this->assertEquals($vers1[0]->getFromChapter(), 1);
							$this->assertEquals($vers1[0]->getToChapter(), 2);
							$this->assertEquals($vers1[0]->getFromVerse(), 4);
							$this->assertEquals($vers1[0]->getToVerse(), 2);
						}

					} else {
						$vers1 = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,4-1,6");

						$this->assertNotNull($vers1,
											 "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,4-1,6 (ID:" . $bookID . ")");

						if ($vers1 != NULL) {
							$this->assertEquals($vers1[0]->getBookId(), $bookID);
							$this->assertEquals($vers1[0]->getFromChapter(), 1);
							$this->assertEquals($vers1[0]->getToChapter(), 1);
							$this->assertEquals($vers1[0]->getFromVerse(), 4);
							$this->assertEquals($vers1[0]->getToVerse(), 6);
						}
					}
				}

				foreach ($bd as $bookID => $bookArray) {
					if ($bd[$bookID]["kapAnz"] > 1) {
						$vers1        = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,4-2,2f");
						$errorMessage = "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,4-2,2f (ID:" . $bookID . ")";

						$this->assertNotNull($vers1, $errorMessage);

						if ($vers1 != NULL && isset($bookArray[2])) {
							$this->assertEquals($vers1[0]->getBookId(), $bookID);
							$this->assertEquals($vers1[0]->getFromChapter(), 1, $errorMessage);
							$this->assertEquals($vers1[0]->getToChapter(), 2, $errorMessage);
							$this->assertEquals($vers1[0]->getFromVerse(), 4, $errorMessage);
							$this->assertEquals($vers1[0]->getToVerse(), 3, $errorMessage);
						}

					} else {
						$vers1 = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,4-1,6f");

						$this->assertNotNull($vers1,
											 "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,4-1,6f (ID:" . $bookID . ")");

						if ($vers1 != NULL) {
							$this->assertEquals($vers1[0]->getBookId(), $bookID);
							$this->assertEquals($vers1[0]->getFromChapter(), 1);
							$this->assertEquals($vers1[0]->getToChapter(), 1);
							$this->assertEquals($vers1[0]->getFromVerse(), 4);
							$this->assertEquals($vers1[0]->getToVerse(), 7);
						}
					}
				}

				foreach ($bd as $bookID => $bookArray) {
					$vers1 = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1");

					$this->assertNotNull($vers1,
										 "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1 (ID:" . $bookID . ")");

					if ($vers1 != NULL) {
						$this->assertEquals($vers1[0]->getBookId(), $bookID);
						$this->assertEquals($vers1[0]->getFromChapter(), 1);
						$this->assertEquals($vers1[0]->getToChapter(), 1);
						$this->assertEquals($vers1[0]->getFromVerse(), 1);
						$this->assertEquals($vers1[0]->getToVerse(), $bd[$bookID][1]);
					}
				}

				foreach ($bd as $bookID => $bookArray) {
					$vers1 = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,1.3");

					$this->assertNotNull($vers1,
										 "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,1.3 (ID:" . $bookID . ")");
					$this->assertTrue(count($vers1) == 2);


					if ($vers1 != NULL) {
						$this->assertEquals($vers1[0]->getBookId(), $bookID);
						$this->assertEquals($vers1[0]->getFromChapter(), 1);
						$this->assertEquals($vers1[0]->getToChapter(), 1);
						$this->assertEquals($vers1[0]->getFromVerse(), 1);
						$this->assertEquals($vers1[0]->getToVerse(), 1);


						$this->assertEquals($vers1[1]->getBookId(), $bookID);
						$this->assertEquals($vers1[1]->getFromChapter(), 1);
						$this->assertEquals($vers1[1]->getToChapter(), 1);
						$this->assertEquals($vers1[1]->getFromVerse(), 3);
						$this->assertEquals($vers1[1]->getToVerse(), 3);
					}
				}

				foreach ($bd as $bookID => $bookArray) {
					$vers1 = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,1+3");

					$this->assertNotNull($vers1,
										 "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,1+3 (ID:" . $bookID . ")");
					$this->assertTrue(count($vers1) == 2);

					if ($vers1 != NULL) {
						$this->assertEquals($vers1[0]->getBookId(), $bookID);
						$this->assertEquals($vers1[0]->getFromChapter(), 1);
						$this->assertEquals($vers1[0]->getToChapter(), 1);
						$this->assertEquals($vers1[0]->getFromVerse(), 1);
						$this->assertEquals($vers1[0]->getToVerse(), 1);

						$this->assertEquals($vers1[1]->getBookId(), $bookID);
						$this->assertEquals($vers1[1]->getFromChapter(), 1);
						$this->assertEquals($vers1[1]->getToChapter(), 1);
						$this->assertEquals($vers1[1]->getFromVerse(), 3);
						$this->assertEquals($vers1[1]->getToVerse(), 3);
					}
				}

				foreach ($bd as $bookID => $bookArray) {
					$vers1 = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,1+3f");

					$this->assertNotNull($vers1,
										 "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,1+3f (ID:" . $bookID . ")");
					$this->assertTrue(count($vers1) == 2);

					if ($vers1 != NULL) {
						$this->assertEquals($vers1[1]->getBookId(), $bookID);
						$this->assertEquals($vers1[1]->getFromChapter(), 1);
						$this->assertEquals($vers1[1]->getToChapter(), 1);
						$this->assertEquals($vers1[1]->getFromVerse(), 3);
						$this->assertEquals($vers1[1]->getToVerse(), 4);
					}
				}

				// Test "ff"
				foreach ($bd as $bookID => $bookArray) {
					$vers1        = $bh->stringToBibleVerse($bd[$bookID]["desc"][$lang][$size] . " 1,2ff");
					$errorMessage = "%s bei " . $bd[$bookID]["desc"][$lang][$size] . " 1,2ff (ID:" . $bookID . ")";
					$this->assertNotNull($vers1, $errorMessage);

					if ($vers1 != NULL) {
						$this->assertEquals($vers1[0]->getBookId(), $bookID, $errorMessage);
						$this->assertEquals($vers1[0]->getFromChapter(), 1, $errorMessage);
						$this->assertEquals($vers1[0]->getToChapter(), 1, $errorMessage);
						$this->assertEquals($vers1[0]->getFromVerse(), 2, $errorMessage);
						$this->assertEquals($vers1[0]->getToVerse(), $bookArray[1], $errorMessage);
					}
				}
			}
		}
	}

	function testCompareFunction() {
		$bh   = $this->bibleVerseService;
		$vers = $bh->stringToBibleVerse("Mark 8,9");
		$a    = $vers[0];

		$vers   = $bh->stringToBibleVerse("Mark 8,9");
		$a_same = $vers[0];

		$vers = $bh->stringToBibleVerse("Mark 8,10");
		$b1   = $vers[0];

		$vers = $bh->stringToBibleVerse("Mark 9,9");
		$b2   = $vers[0];

		$this->assertNotNull($a);
		$this->assertNotNull($b1);
		$this->assertNotNull($b2);

		$this->assertNotEquals($a, $b1);
		$this->assertNotEquals($a, $b2);
		$this->assertEquals($a, $a_same);

	}

	function testValidityOfVersesinBibletagHelper() {
		$bh = $this->bibleVerseService;

		$vers = $bh->stringToBibleVerse("Ex 1,22");
		$this->assertNotNull($vers[0]);

		$vers = $bh->stringToBibleVerse("Ex 1,22-2,25");
		$this->assertNotNull($vers[0]);

		$vers = $bh->stringToBibleVerse("Ex 1,1");
		$this->assertNotNull($vers[0]);

		$vers = $bh->stringToBibleVerse("Ex 1,23");
		$this->assertTrue(count($vers) == 0);

		$vers = $bh->stringToBibleVerse("Ex 1,22-2,26");
		$this->assertTrue(count($vers) == 0);

		$vers = $bh->stringToBibleVerse("Ex 1,23-2,26");
		$this->assertTrue(count($vers) == 0);

		// Solange noch nicht alle eingetragen sind
		$vers = $bh->stringToBibleVerse("Psalmen Salomos 1,8");
		$this->assertNotNull($vers[0]);

		$vers = $bh->stringToBibleVerse("Psalmen Salomos 1,8-2,41");
		$this->assertNotNull($vers[0]);

		$vers = $bh->stringToBibleVerse("Psalmen Salomos 8,23");
		$this->assertNotNull($vers[0]);

		$vers = $bh->stringToBibleVerse("Psalmen Salomos 0,23");
		$this->assertTrue(count($vers) == 0);
	}

	public function testUltimaString() {
		$bh      = $this->bibleVerseService;
		$testStr = "/abcdei";
		$this->assertEquals("abcde", $bh->cleanUpForUltima($testStr));

		$testStr = "/^abcde\$i";
		$this->assertEquals("abcde", $bh->cleanUpForUltima($testStr));

		$testStr = "/^abcde\$//i";
		$this->assertEquals("abcde", $bh->cleanUpForUltima($testStr));

		$testStr = "/^abcde\$/i";
		$this->assertEquals("abcde", $bh->cleanUpForUltima($testStr));

		$testStr = "///^abcde\$i";
		$this->assertEquals("abcde", $bh->cleanUpForUltima($testStr));

		$testStr = "/^/abcde\$i";
		$this->assertEquals("abcde", $bh->cleanUpForUltima($testStr));
	}

	public function testNotPossibleTest() {
		$bh   = $this->bibleVerseService;
		$vers = $bh->stringToBibleVerse("Test 2");
		// Darf nicht "Ester 2" sein
		$this->assertEquals(count($vers), 0);
	}

	public function testStupidWriting() {
		// This is actually not correct syntax. But commonly used
		$bv = $this->bibleVerseService->stringToBibleVerse('1.Mose 12ff');
		// Ment to be "1. Mose 12,1ff)
		$this->assertTrue(count($bv) == 1);

		$bv_temp = $this->bibleVerseService->stringToBibleVerse('1.Mose 12ff.');
		$bv[]    = $bv_temp[0];
		$this->assertTrue(count($bv) == 2);

		foreach ($bv as $verse) {
			$this->assertEquals($verse->getBookId(), 1);
			$this->assertEquals($verse->getFromChapter(), 12);
			$this->assertEquals($verse->getFromVerse(), 1);
			$this->assertEquals($verse->getToChapter(), 12);
			$this->assertEquals($verse->getToVerse(), 20);
		}
	}

	public function testConjunctionOfVerses() {
		$bv = $this->bibleVerseService->stringToBibleVerse('1Kor 10,14-22, 1Kor 10,25-28, Ethische Grenzen');

		$this->assertCount(2, $bv);

		$this->assertEquals(46, $bv[0]->getBookId());
		$this->assertEquals(10, $bv[0]->getFromChapter());
		$this->assertEquals(14, $bv[0]->getFromVerse());
		$this->assertEquals(10, $bv[0]->getToChapter());
		$this->assertEquals(22, $bv[0]->getToVerse());

		$this->assertEquals(46, $bv[1]->getBookId());
		$this->assertEquals(10, $bv[1]->getFromChapter());
		$this->assertEquals(25, $bv[1]->getFromVerse());
		$this->assertEquals(10, $bv[1]->getToChapter());
		$this->assertEquals(28, $bv[1]->getToVerse());
	}

	public function testEnglishConjunctionOfVerses() {
		$bv = $this->bibleVerseService->stringToBibleVerse('Genesis 26:35, 27:46');

		$this->assertCount(2, $bv);

		$this->assertEquals(1, $bv[0]->getBookId());
		$this->assertEquals(26, $bv[0]->getFromChapter());
		$this->assertEquals(35, $bv[0]->getFromVerse());
		$this->assertEquals(26, $bv[0]->getToChapter());
		$this->assertEquals(35, $bv[0]->getToVerse());

		$this->assertEquals(1, $bv[1]->getBookId());
		$this->assertEquals(27, $bv[1]->getFromChapter());
		$this->assertEquals(46, $bv[1]->getFromVerse());
		$this->assertEquals(27, $bv[1]->getToChapter());
		$this->assertEquals(46, $bv[1]->getToVerse());


		$bv = $this->bibleVerseService->stringToBibleVerse('Genesis 6:12, 11');

		$this->assertCount(2, $bv);
		$this->assertEquals(1, $bv[1]->getBookId());
		$this->assertEquals(6, $bv[1]->getFromChapter());
		$this->assertEquals(11, $bv[1]->getFromVerse());
		$this->assertEquals(6, $bv[1]->getToChapter());
		$this->assertEquals(11, $bv[1]->getToVerse());


		$bv = $this->bibleVerseService->stringToBibleVerse('Genesis 39:16-17,19');
		$this->assertCount(2, $bv);

		$this->assertEquals(1, $bv[0]->getBookId());
		$this->assertEquals(39, $bv[0]->getFromChapter());
		$this->assertEquals(16, $bv[0]->getFromVerse());
		$this->assertEquals(39, $bv[0]->getToChapter());
		$this->assertEquals(17, $bv[0]->getToVerse());

		$this->assertEquals(1, $bv[1]->getBookId());
		$this->assertEquals(39, $bv[1]->getFromChapter());
		$this->assertEquals(19, $bv[1]->getFromVerse());
		$this->assertEquals(39, $bv[1]->getToChapter());
		$this->assertEquals(19, $bv[1]->getToVerse());


		$bv = $this->bibleVerseService->stringToBibleVerse('Exodus 7:3-4,3:20,4:21');
		$this->assertCount(3, $bv);

		$this->assertEquals(2, $bv[0]->getBookId());
		$this->assertEquals(7, $bv[0]->getFromChapter());
		$this->assertEquals(3, $bv[0]->getFromVerse());
		$this->assertEquals(7, $bv[0]->getToChapter());
		$this->assertEquals(4, $bv[0]->getToVerse());

		$this->assertEquals(2, $bv[1]->getBookId());
		$this->assertEquals(3, $bv[1]->getFromChapter());
		$this->assertEquals(20, $bv[1]->getFromVerse());
		$this->assertEquals(3, $bv[1]->getToChapter());
		$this->assertEquals(20, $bv[1]->getToVerse());

		$this->assertEquals(2, $bv[2]->getBookId());
		$this->assertEquals(4, $bv[2]->getFromChapter());
		$this->assertEquals(21, $bv[2]->getFromVerse());
		$this->assertEquals(4, $bv[2]->getToChapter());
		$this->assertEquals(21, $bv[2]->getToVerse());

	}

	public function featureMissingYet() {
		$bv = $this->bibleVerseService->stringToBibleVerse('1.Mose 12; 14,4');
		$this->assertCount(2, $bv);
	}

	public function debug($obj) {
		fwrite(STDERR, print_r($obj, TRUE) . "\n");
	}

	protected function setUp() {
		//  self::bootKernel();

//        $this->bibleVerseService = static::$kernel->getContainer()->get('bible_verse.helper');
		$this->bibleVerseService = new BibleVerseService();
	}
}