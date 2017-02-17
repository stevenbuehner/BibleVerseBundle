<?php

namespace StevenBuehner\BibleVerseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use StevenBuehner\BibleVerseBundle\Exceptions\InvalidBookIdException;
use StevenBuehner\BibleVerseBundle\Interfaces\BibleVerseInterface;

/**
 * BibleVerse
 *
 * @ORM\Table(name="bible_verse")
 * @ORM\Entity(repositoryClass="StevenBuehner\BibleVerseBundle\Repository\BibleVerseRepository")
 */
class BibleVerse implements BibleVerseInterface {


	/**
	 * @var int
	 *
	 * @ORM\Column(name="start", type="int")
	 */
	protected $start;
	/**
	 * @var int
	 *
	 * @ORM\Column(name="book_id", type="int")
	 */
	/**
	 * @var int
	 *
	 * @ORM\Column(name="end", type="int")
	 */
	protected $end;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="int")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	public function __construct() {
		$this->start = 0;
		$this->end   = 0;
	}

	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set $fromChapter
	 *
	 * @param int $fromChapter
	 * @throws InvalidBookIdException
	 */
	public function setFromChapter($fromChapter) {
		$this->setFromCombined($this->getFromBookId(), $fromChapter, $this->getFromVerse());
	}

	public function setFromCombined($bookId, $chapter, $verse) {
		$this->start = self::getCombi($bookId, $chapter, $verse);
	}

	public static function getCombi($bookId, $chapter, $verse) {
		return (int) sprintf('%03d%03d%03d', $bookId, $chapter, $verse);
	}

	/**
	 * Get bookId
	 *
	 * @return int
	 */
	public function getFromBookId() {
		return self::getBookFromCombi($this->start);
	}

	/**
	 * @param int $chapterVerseNum
	 * @return int
	 */
	public static function getBookFromCombi($chapterVerseNum) {
		return floor($chapterVerseNum / 1000000);
	}

	/**
	 * Get fromVerse
	 *
	 * @return int
	 */
	public function getFromVerse() {
		return self::getVerseFromCombi($this->start);
	}

	/**
	 * @param int $chapterVerseNum
	 * @return int
	 */
	public static function getVerseFromCombi($chapterVerseNum) {
		return (int) ($chapterVerseNum % 1000);
	}

	/**
	 * Get bookId
	 *
	 * @return int
	 * @throws InvalidBookIdException
	 */
	public function getBookId() {
		if ($this->getFromBookId() == $this->getToBookId()) {
			return $this->getFromBookId();
		} else {
			throw new InvalidBookIdException();
		}
	}

	/**
	 * Get bookId
	 *
	 * @return int
	 */
	public function getToBookId() {
		return self::getBookFromCombi($this->end);
	}

	/**
	 * Get bookId
	 *
	 * @return int
	 */
	public function getBookToId() {
		return self::getBookFromCombi($this->end);
	}

	/**
	 * Set fromVerse
	 *
	 * @param int $fromVerse
	 */
	public function setFromVerse($fromVerse) {
		$this->setFromCombined($this->getFromBookId(), $this->getFromChapter(), $fromVerse);
	}

	/**
	 * Get fromChapter
	 *
	 * @return int
	 */
	public function getFromChapter() {
		return self::getChapterFromCombi($this->start);
	}

	/**
	 * @param int $chapterVerseNum
	 * @return int
	 */
	public static function getChapterFromCombi($chapterVerseNum) {
		// cut off bookId and then cut of verses
		return (int) floor(($chapterVerseNum % 1000000) / 1000);
	}

	/**
	 * Set toChapter
	 *
	 * @param int $toChapter
	 */
	public function setToChapter($toChapter) {
		$this->setToCombined($this->getToBookId(), $toChapter, $this->getToVerse());
	}

	/**
	 * @param int $bookId
	 * @param int $chapter
	 * @param int $verse
	 */
	public function setToCombined($bookId, $chapter, $verse) {
		$this->end = self::getCombi($bookId, $chapter, $verse);
	}

	/**
	 * Get toVerse
	 *
	 * @return int
	 */
	public function getToVerse() {
		return self::getVerseFromCombi($this->end);
	}

	/**
	 * Set toVerse
	 *
	 * @param int $toVerse
	 */
	public function setToVerse($toVerse) {
		$this->setToCombined($this->getToBookId(), $this->getToChapter(), $toVerse);
	}

	/**
	 * Get toChapter
	 *
	 * @return int
	 */
	public function getToChapter() {
		return self::getChapterFromCombi($this->end);
	}

	/**
	 * Returns true if kapFrom / versFrom is equals kapTo / versTo
	 *
	 * @return boolean
	 */
	public function isSingleVers() {
		if ($this->end == $this->start) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function __toString() {
		return "BibleVerse[book={$this->getFromBookId()} {$this->getFromChapter()},{$this->getFromVerse()}-{$this->getToBookId()} {$this->getToChapter()},{$this->getToVerse()}]";
	}

	/**
	 * @param BibleVerseInterface $bv
	 */
	public function insertFromBibleVerseInterface(BibleVerseInterface $bv) {
		$this->setFromCombined($bv->getFromBookId(), $bv->getFromChapter(), $bv->getFromVerse());
		$this->setToCombined($bv->getToBookId(), $bv->getToChapter(), $bv->getToVerse());
	}

	public function setVerse($bookId, $fromChapter, $fromVerse, $toChapter = NULL, $toVerse = NULL) {
		$toChapter = (NULL === $toChapter) ? (int) $fromChapter : $toChapter;
		$toVerse   = (NULL === $toVerse) ? (int) $fromVerse : $toVerse;

		$this->setFromCombined($bookId, $fromChapter, $fromVerse);
		$this->setToCombined($bookId, $toChapter, $toVerse);
	}

	/**
	 * Set bookId
	 *
	 * @param int $bookId
	 */
	public function setBookId($bookId) {
		$this->setFromCombined((int) $bookId, $this->getFromChapter(), $this->getFromVerse());
		$this->setToCombined((int) $bookId, $this->getToChapter(), $this->getToVerse());
	}

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

	/**
	 * Set $fromBookId
	 *
	 * @param int $fromBookId
	 */
	public function setFromBookId($fromBookId) {
		$this->start = self::getCombi($fromBookId, $this->getFromChapter(), $this->getFromVerse());
	}

	/**
	 * Set $toBookId
	 *
	 * @param int $toBookId
	 */
	public function setToBookId($toBookId) {
		$this->end = self::getCombi($toBookId, $this->getToChapter(), $this->getToVerse());
	}
}

