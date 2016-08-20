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
     * @var int
     *
     * @ORM\Column(name="from_chapter", type="integer")
     */
    private $fromChapter;

    /**
     * @var int
     *
     * @ORM\Column(name="to_chapter", type="integer")
     */
    private $toChapter;

    /**
     * @var int
     *
     * @ORM\Column(name="from_verse", type="integer")
     */
    private $fromVerse;

    /**
     * @var int
     *
     * @ORM\Column(name="to_verse", type="integer")
     */
    private $toVerse;


    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    public function setVerse($bookId, $fromChapter, $fromVerse, $toChapter = NULL, $toVerse = NULL) {
        $this->setBookId($bookId);
        $this->setFromChapter($fromChapter);
        $this->setFromVerse($fromVerse);

        if (NULL == $toChapter)
            $toChapter = $fromChapter;

        if (NULL == $toVerse)
            $toVerse = $fromVerse;

        $this->setToChapter($toChapter);
        $this->setToVerse($toVerse);
    }

    /**
     * Returns true if fromChapter / fromVerse is equals toChapter / toVerse
     *
     * @return boolean
     */
    public function isSingleVers() {
        if ($this->getFromChapter() != 0 && $this->getFromChapter() == $this->getToChapter() && $this->getFromVerse() != 0 && $this->getFromVerse() == $this->getToVerse())
            return true;
        else
            return false;
    }

    /**
     * Get fromChapter
     *
     * @return int
     */
    public function getFromChapter() {
        return $this->fromChapter;
    }

    /**
     * Set fromChapter
     *
     * @param integer $fromChapter
     *
     * @return BibleVerse
     */
    public function setFromChapter($fromChapter) {
        $this->fromChapter = $fromChapter;

        return $this;
    }

    /**
     * Get toChapter
     *
     * @return int
     */
    public function getToChapter() {
        return $this->toChapter;
    }

    /**
     * Set toChapter
     *
     * @param integer $toChapter
     *
     * @return BibleVerse
     */
    public function setToChapter($toChapter) {
        $this->toChapter = $toChapter;

        return $this;
    }

    /**
     * Get fromVerse
     *
     * @return int
     */
    public function getFromVerse() {
        return $this->fromVerse;
    }

    /**
     * Set fromVerse
     *
     * @param integer $fromVerse
     *
     * @return BibleVerse
     */
    public function setFromVerse($fromVerse) {
        $this->fromVerse = $fromVerse;

        return $this;
    }

    /**
     * Get toVerse
     *
     * @return int
     */
    public function getToVerse() {
        return $this->toVerse;
    }

    /**
     * Set toVerse
     *
     * @param integer $toVerse
     *
     * @return BibleVerse
     */
    public function setToVerse($toVerse) {
        $this->toVerse = $toVerse;

        return $this;
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
     *
     * @return BibleVerse
     */
    public function setBookId($bookId) {
        $this->bookId = $bookId;

        return $this;
    }

}

