<?php

namespace StevenBuehner\BibleVerseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
	 * @ORM\Column(name="from", type="integer")
	 */
	protected $from;
	/**
	 * @var int
	 *
	 * @ORM\Column(name="tp", type="integer")
	 */
	protected $to;
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @var int
	 *
	 * @ORM\Column(name="book_id", type="integer")
	 */
	private $bookId;

	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set fromChapter
	 *
	 * @param integer $fromChapter
	 */
	public function setFromChapter($fromChapter) {
		$this->setFromCombined($fromChapter, $this->getFromVerse());
	}

	public function setFromCombined($chapter, $verse) {
		$this->from = $this->getCombi($chapter, $verse);
	}

	protected function getCombi($chapter, $verse) {
		return (int) sprintf('%03d%03d', $chapter, $verse);
	}

	/**
	 * Get fromVerse
	 *
	 * @return int
	 */
	public function getFromVerse() {
		return $this->getVerseFromCombi($this->from);
	}

	/**
	 * @param int $chapterVerseNum
	 * @return int
	 */
	protected function getVerseFromCombi($chapterVerseNum) {
		return (int) ($chapterVerseNum % 1000);
	}

	/**
	 * Set fromVerse
	 *
	 * @param integer $fromVerse
	 */
	public function setFromVerse($fromVerse) {
		$this->setFromCombined($this->getFromChapter(), $fromVerse);
	}

	/**
	 * Get fromChapter
	 *
	 * @return int
	 */
	public function getFromChapter() {
		return $this->getChapterFromCombi($this->from);
	}

	/**
	 * @param int $chapterVerseNum
	 * @return int
	 */
	protected function getChapterFromCombi($chapterVerseNum) {
		return (int) floor($chapterVerseNum / 1000);
	}

	/**
	 * Set toChapter
	 *
	 * @param integer $toChapter
	 */
	public function setToChapter($toChapter) {
		$this->setToCombined($toChapter, $this->getToVerse());
	}

	public function setToCombined($chapter, $verse) {
		$this->to = $this->getCombi($chapter, $verse);
	}

	/**
	 * Get toVerse
	 *
	 * @return int
	 */
	public function getToVerse() {
		return $this->getVerseFromCombi($this->to);
	}

	/**
	 * Set toVerse
	 *
	 * @param integer $toVerse
	 */
	public function setToVerse($toVerse) {
		$this->setToCombined($this->getToChapter(), $toVerse);
	}

	/**
	 * Get toChapter
	 *
	 * @return int
	 */
	public function getToChapter() {
		return $this->getChapterFromCombi($this->to);
	}

	/**
	 * Returns true if kapFrom / versFrom is equals kapTo / versTo
	 *
	 * @return boolean
	 */
	public function isSingleVers() {
		if ($this->to == $this->from) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function __toString() {
		return "BibleVerse[book={$this->getBookId()} {$this->getFromChapter()},{$this->getFromVerse()}-{$this->getToChapter()},{$this->getToVerse()}]";
	}

	/**
	 * Get bookId
	 *
	 * @return int
	 */
	public function getBookId() {
		return $this->bookId;
	}

	/**
	 * Set bookId
	 *
	 * @param integer $bookId
	 */
	public function setBookId($bookId) {
		$this->bookId = (int) $bookId;
	}

	/**
	 * @param BibleVerseInterface $bv
	 */
	public function insertFromBibleVerseInterface(BibleVerseInterface $bv) {
		$this->setBookId($bv->getBookId());
		$this->setFromCombined($bv->getFromChapter(), $bv->getFromVerse());
		$this->setToCombined($bv->getToChapter(), $bv->getToVerse());
	}

	public function setVerse($bookId, $fromChapter, $fromVerse, $toChapter = NULL, $toVerse = NULL) {
		$toChapter = (NULL === $toChapter) ? (int) $fromChapter : $toChapter;
		$toVerse   = (NULL === $toVerse) ? (int) $fromVerse : $toVerse;

		$this->setBookId($bookId);
		$this->setFromCombined($fromChapter, $fromVerse);
		$this->setToCombined($toChapter, $toVerse);
	}

	public function getFrom() {
		return $this->from;
	}

	public function getTo() {
		return $this->to;
	}
}

