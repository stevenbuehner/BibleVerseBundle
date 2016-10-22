<?php

namespace StevenBuehner\BibleVerseBundle\Interfaces;

interface BibleVerseInterface {

	/**
	 * Set bookId
	 *
	 * @param integer $bookId
	 */
	public function setBookId($bookId);

	/**
	 * Get bookId
	 *
	 * @return int
	 */
	public function getBookId();

	/**
	 * Set fromChapter
	 *
	 * @param integer $fromChapter
	 */
	public function setFromChapter($fromChapter);

	/**
	 * Get fromChapter
	 *
	 * @return int
	 */
	public function getFromChapter();

	/**
	 * Set toChapter
	 *
	 * @param integer $toChapter
	 */
	public function setToChapter($toChapter);

	/**
	 * Get toChapter
	 *
	 * @return int
	 */
	public function getToChapter();

	/**
	 * Set fromVerse
	 *
	 * @param integer $fromVerse
	 */
	public function setFromVerse($fromVerse);

	/**
	 * Get fromVerse
	 *
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
	 * @param          $bookId
	 * @param in       $fromChapter
	 * @param int      $fromVerse
	 * @param int|NULL $toChapter
	 * @param int|NULL $toVerse
	 */
	public function setVerse($bookId, $fromChapter, $fromVerse, $toChapter = NULL, $toVerse = NULL);

	/**
	 * String-Representation of this bibleverse, which makes it quickly possible to compare two bibleverses for beeing equal
	 *
	 * @return string
	 */
	public function __toString();

}
