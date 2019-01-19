<?php
/**
 * This file was created by  steven
 * Created: 30.08.16 16:23
 * All Rights reserved. No usage without written permission allowed.
 */


namespace StevenBuehner\JS\Generator;

use StevenBuehner\BibleVerseBundle\Service\BibleVerseService;

class BibleVerseGenerator {

	protected $bibleVerseService;
	protected $twigEngine;

	public function __construct() {
		$this->bibleVerseService = new BibleVerseService();

		$loader1          = new \Twig_Loader_Filesystem(__DIR__ . '/../in');
		$loader2          = new \Twig_Loader_Array([
													   'BibleVerse'   => "new Bibleverse({{verse.verseId}}, {{verse.fromChapter}}, {{verse.fromVerse}}, {{verse.toChapter}}, {{verse.toVerse}})",
													   'BibleBook'    => "new BibleBook({{book.bookId}}, '{{book.nameLong | escape('js')}}', '{{book.nameShort | escape('js')}}', '{{book.namePattern | escape('js')}}', {{book.chapterSum}}, { {% for count in book.chapterCount %} '{{count.chapter}}' : {{count.verseCount}}, {% endfor %} })",
													   'BookListing'  => "\n{% for book in data %}{{book.bookId}} : {{ include('BibleBook') }},\n{% endfor %}",
													   'biblePattern' => '{{ biblePattern | raw}}'
												   ]);
		$loaderChain      = new \Twig_Loader_Chain([$loader1, $loader2]);
		$this->twigEngine = new \Twig_Environment($loaderChain);
	}

	public function run() {
		$template = $this->twigEngine->loadTemplate('BibleVerseService.js');
		$langs    = ['de', 'en'];
		$_data    = $this->bibleVerseService->getBibleData();
		$_pattern = $this->bibleVerseService->getFirstSearchString();
		$cutStart = 14;
		$cutEnd   = 2;
		$_pattern = substr($_pattern,
						   $cutStart,
						   strlen($_pattern) - $cutStart - $cutEnd); // Negative Lookbehind not supported in Safari

		foreach ($langs as $lang) {

			$bibleBooks            = [];
			$chapterVerseSeparator = ($lang == 'de' ? ',' : ':');

			foreach ($_data as $bookId => $bookData) {
				$book = ['bookId'      => $bookId,
						 'nameLong'    => $bookData['desc'][$lang]['long'],
						 'nameShort'   => $bookData['desc'][$lang]['short'],
						 'namePattern' => $bookData['pat'],
						 'chapterSum'  => $bookData['kapAnz']
				];

				$chapterCount = [];
				for ($i = 1; $i <= $bookData['kapAnz']; $i++) {
					if (isset($bookData[$i])) {
						$chapterCount[] = ['chapter'    => $i,
										   'verseCount' => $bookData[$i]];
					}

				}

				$book['chapterCount'] = $chapterCount;

				$bibleBooks[] = $book;
			}

			file_put_contents(__DIR__ . "/../out/BibleVerseService_{$lang}.js",
							  $template->render(['data'                  => $bibleBooks,
												 'biblePattern'          => $_pattern,
												 'chapterVerseSeparator' => $chapterVerseSeparator]
							  )
			);
		}

	}

}

