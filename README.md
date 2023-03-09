# What is it?

With this bundle you get an incredible powerfull backend to parse any text and identify bibleverses within it. It works with symphony but also with any other composer system.
The bibleverses are not only recognized, but intelligently seperated into book, chapter and vers-ranges. 

I am using it in a big installation to store and index bibleverses in the database and perform very quick and complex searches.

# Features
The library recognizes all kind of different texts as bibleveres in german and in english. For example:
* 1Tim 3,16

* 1 Tim 3,16f (one following verse)
* 1 Tim 3,15-16 (multiple verses as range joined by "-")
* 1 Tim 3,16ff (following verses with "ff" until to the chapters end)
* 1 Timotheus 3,16 (long nameing convention)
* 1 Tim 3,15.16 (multiple verses joined with ".")
* 1 Tim 3,15+16 (multiple verses joined with "+")
* 1\. Timothy 3,16 (precreding "1." convention)
* I Timothy 3,16 (precreding "I" convention / with or without ".")
* First Timothy 3,16 (precreding "First" convention)
* 1st Timothy 3,16 (precreding "1st" convention)
* 1\.Timotheusbrief 3,16 (german long nameing convention)
* 1 Ti 3,16 (different short nameing conventions)
* 1Tim 3,16-4,2 (ranges over multiple chapters)
* 1Tim 3 (whole Chapter)

# Examples
The texts are recognized and parsed into valid integers.
For 1Tim 3,16-17 this would be:

|  Parsed-Text  | Book-Id | From Chapter  | From Verse | To Chapter | To Verse |
|:-------------:|:-------:|:-------------:|:----------:|:----------:|:--------:|
| 1Tim 3,15-16  |   54    |       3       |     15     |     3      |    16    |
|  1Tim 3,15f   |   54    |       3       |     15     |     3      |    16    |
| 1Tim 3,16-4,2 |   54    |       3       |     16     |     4      |    2     |
|    1Tim 3     |   54    |       3       |     1      |     3      |    16    |
|   1Tim 3-4    |   54    |       3       |     1      |     4      |    16    |


# Storage and search optimization
Internally the bibleverses are stored as two numbers which describe a range (start - end). This makes it possible so index bibleverses and search for intersecting bibleverses super quickly.

START and END are set together as three digit codes:

|           Book-ID           |       Chapter-Number        |        Verse-Number         | 
|:---------------------------:|:---------------------------:|:---------------------------:|
| (three digits, zero padded) | (three digits, zero padded) | (three digits, zero padded) |


Which results in this indexing:

|  Parsed-Text  | Book-Id | From Chapter | From Verse | To Chapter | To Verse |   **START**   |    **END**    |
|:-------------:|:-------:|:------------:|:----------:|:----------:|:--------:|:-------------:|:-------------:|
| 1Tim 3,15-16  |   54    |      3       |     15     |     3      |    16    | **054003015** | **054003016** |
|  1Tim 3,15f   |   54    |      3       |     15     |     3      |    16    | **054003015** | **054003016** |
| 1Tim 3,16-4,2 |   54    |      3       |     16     |     4      |    2     | **054003016** | **054004002** |
|    1Tim 3     |   54    |      3       |     1      |     3      |    16    | **054003001** | **054003016** |
|   1Tim 3-4    |   54    |      3       |     1      |     4      |    16    | **054003001** | **054004016** |


# Usage
## Parsing text to Bibleverses
``` php 
$service = new BibleVerseService();
$found = $service->stringToBibleVerse('1Tim 3,16');
```

## Formatting Bibleverses as Text
Language German and English are supported by default
``` php
foreach($found as $bibleverse){
	// long labels
	echo $service->bibleVerseToString($bibleverse);
	
	// or short labels
	echo $service->bibleVerseToString($bibleverse, 'short');
}
```

## Extracting bibleverses from larger texts
``` php 
$found = $service->stringToBibleVerse('Hello. I learned from 2Tim 3,16 that it differs from Gen 1,1');
// --> $found will be an array with two recognized bibleverses

$rest = $service->getLastRestString();
// --> $rest will be the remaining string, without the found bibleverses = "Hello. I learned from that it differs from "
```

## Join multiple bibleverses together
``` php
// Join biblevereses
$found = $service->stringToBibleVerse('Hello. I learned from 2Tim 3,16 that it differs from 2Tim 3,17');
// --> $found will be an array with two recognized bibleverses
$merged = $service->mergeBibleverses($found);
// --> $merged will be an array with ONE bibleverse. Both verses where merged into 2Tim 3,16-17
```

## Accessing bibleverse start and end number

```php
	/**
	 * @return int
	 */
	public function getStart() {
		return $this->start;
	}

	/**
	 * @return int
	 */
	public function getEnd() {
		return $this->end;
	}
```

## Bibleverse Interface
Bibleverses are valid ``BibleVerseInterface`` instances. Which means they can be extended and come with theese default functions
``` php
	/**
	 * Set From and To bookId
	 * @param integer $bookId
	 */
	public function setBookId($bookId);

	/**
	 * Get bookId
	 * @return int
	 */
	public function getFromBookId();

	/**
	 * Get bookId
	 * @return int
	 */
	public function getToBookId();

	/**
	 * Set $fromBookId
	 * @param integer $fromBookId
	 */
	public function setFromBookId($fromBookId);

	/**
	 * Set $toBookId
	 * @param integer $toBookId
	 */
	public function setToBookId($toBookId);


	/**
	 * Set fromChapter
	 * @param integer $fromChapter
	 */
	public function setFromChapter($fromChapter);

	/**
	 * Get fromChapter
	 * @return int
	 */
	public function getFromChapter();

	/**
	 * Set toChapter
	 * @param integer $toChapter
	 */
	public function setToChapter($toChapter);

	/**
	 * Get toChapter
	 * @return int
	 */
	public function getToChapter();

	/**
	 * Set fromVerse
	 * @param integer $fromVerse
	 */
	public function setFromVerse($fromVerse);

	/**
	 * Get fromVerse
	 * @return int
	 */
	public function getFromVerse();

	/**
	 * Set toVerse
	 *
	 * @param integer $toVerse
	 */
	public function setToVerse($toVerse);

	/**
	 * Get toVerse
	 *
	 * @return int
	 */
	public function getToVerse();

	/**
	 * @param int      $bookId
	 * @param int      $fromChapter
	 * @param int      $fromVerse
	 * @param int|NULL $toChapter
	 * @param int|NULL $toVerse
	 */
	public function setVerse($bookId, $fromChapter, $fromVerse, $toChapter = NULL, $toVerse = NULL);
```

# JavaScript
There is also a JavaScript library for parsing bibleverses and use it i.e. for markdown purposes.

## Generating Javascript-Library-Updates
The JavaScript library is generated with the command:
``` shell 
php ./js/Generator/cmd.php
``` 