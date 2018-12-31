class BibleVerse {

    constructor(from, to) {
        this.init();

        if (from) {
            this.setFrom(from);
        }

        if (to) {
            this.setTo(to);
        } else if (from) {
            this.setTo(from);
        }
    }


    init() {
        this.fromBookId  = null;
        this.fromChapter = null;
        this.fromVerse   = null;
        this.toBookId    = null;
        this.toChapter   = null;
        this.toVerse     = null;
    }

    getFromBookId() {
        return this.fromBookId;
    }

    getFromChapter() {
        return this.fromChapter;
    }

    getFromVerse() {
        return this.fromVerse;
    }

    getFrom() {
        return this.constructor.concatVerse(this.getFromBookId(), this.getFromChapter(), this.getFromVerse());
    }

    getToBookId() {
        return this.toBookId;
    }

    getToChapter() {
        return this.toChapter;
    }

    getToVerse() {
        return this.toVerse;
    }

    getTo() {
        return this.constructor.concatVerse(this.getToBookId(), this.getToChapter(), this.getToVerse());
    }

    setFromBookId(bookId) {
        this.fromBookId = bookId;
    }

    setFromChapter(fromChapter) {
        this.fromChapter = fromChapter;
    }

    setFromVerse(fromVerse) {
        this.fromVerse = fromVerse;
    }

    setToBookId(bookId) {
        this.toBookId = bookId;
    }

    setToChapter(toChapter) {
        this.toChapter = toChapter;
    }

    setToVerse(toVerse) {
        this.toVerse = toVerse;
    }

    setVerse(fromBookId, fromChapter, fromVerse, toBookId, toChapter, toVerse) {
        this.setFromBookId(fromBookId);
        this.setFromChapter(fromChapter);
        this.setFromVerse(fromVerse);
        this.setToBookId(toBookId);
        this.setToChapter(toChapter);
        this.setToVerse(toVerse);
    }

    setFrom(from) {
        const {bookId, chapter, verse} = this.constructor.explodeNumber(from);

        this.setFromBookId(bookId);
        this.setFromChapter(chapter);
        this.setFromVerse(verse);
    }

    setTo(to) {
        const {bookId, chapter, verse} = this.constructor.explodeNumber(to);

        this.setToBookId(bookId);
        this.setToChapter(chapter);
        this.setToVerse(verse);
    }

    isSameBook() {
        return (this.getFromBookId() === this.getFromBookId());
    }

    isSameChapter() {
        return (this.getFromChapter() === this.getToChapter());
    }

    isSameVerse() {
        return (this.getFromVerse() === this.getToVerse());
    }

    isSingleVerse() {
        return this.isSameBook() && this.isSameChapter() && this.isSameVerse();
    }


    static explodeNumber(number) {

        const paddedNumber = number.toString().padStart(9, '0');

        return {
            bookId: parseInt(paddedNumber.substr(0, 3)),
            chapter: parseInt(paddedNumber.substr(3, 3)),
            verse: parseInt(paddedNumber.substr(6, 3))
        };

    }

    static concatVerse(bookId, chapter, verse) {
        return bookId.toString().padStart(3, '0') + chapter.toString().padStart(3, '0') + verse.toString().padStart(3, '0')
    }


}

export default BibleVerse;