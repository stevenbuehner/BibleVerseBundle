import BibleBook from './../in/BibleBook.js'
import BibleVerse from './../in/BibleVerse.js'

// Just, so the Import will not be erased automatically
BibleBook;

export const BibleVerseService = {
    _d: { //{{ include('BookListing') }}
    },
    biblePattern: '{{ include("biblePattern") }}',
    chapterVerseSeparator: '{{ chapterVerseSeparator }}',
    verseVerseSeparator: '-',
    untilChapterEndSuffix: 'ff',
    nextVerseAlsoSuffix: 'f',

    bibleVerseToString: function (verse, displayLength) {

        if (!displayLength || (displayLength !== 'short' && displayLength !== 'long')) {
            displayLength = 'short';
        }

        let resultString = '';

        if (this.isBibleVerseValid(verse) === true) {
            resultString = '(Invalid) ';
        }

        resultString += this._getBookLabel(verse.getFromBookId(), displayLength) + ' ';
        resultString += verse.getFromChapter();

        if (verse.isSameBook()) {

            if (verse.isSameChapter()) {
                // All verses are in the same chapter

                if (verse.getFromVerse() === 1 && this.isVerseUntilChapterEnd(verse)) {
                    // Do not display the verse! It is a single Chapter
                } else if (verse.isSingleVerse()) {
                    // Is single Verse
                    resultString += this.chapterVerseSeparator + verse.getFromVerse();
                } else if (verse.getToVerse() - verse.getFromVerse() === 1) {
                    // This verserange conatins only two verses
                    resultString += this.chapterVerseSeparator + verse.getFromVerse() + this.nextVerseAlsoSuffix;
                } else if (this.isVerseUntilChapterEnd(verse)) {
                    // This verserange ends at the chapter end => ff
                    resultString += this.chapterVerseSeparator + verse.getFromVerse() + this.untilChapterEndSuffix;
                } else {
                    // Is multiple Verses in same chapter
                    resultString += this.chapterVerseSeparator + verse.getFromVerse() + this.verseVerseSeparator + verse.getToVerse();
                }

            }
            else {
                // This verse contains multiple chapters of the same book

                if (verse.getFromVerse() === 1 && this.isVerseUntilChapterEnd(verse)) {
                    // Contains complete Chapters => display only chapters without verses
                    resultString += this.verseVerseSeparator + verse.getToChapter()
                } else {
                    // This verse has multiple chapters with separat verses
                    resultString += this.chapterVerseSeparator + verse.getFromVerse() + this.verseVerseSeparator + verse.getToChapter() + this.chapterVerseSeparator + verse.getToVerse();
                }
            }


        } else {
            console.error('Ranges over multiples books are not supported yet')
        }


        return resultString;
    },


    isBibleVerseValid: function (verse) {
        if (!(verse instanceof BibleVerse)) {
            console.error('This verse is not an instance of Bibleverse', verse);
            return false;
        }

        if (!this._d.hasOwnProperty(verse.getFromBookId()) || !this._d.hasOwnProperty(verse.getToBookId())) {
            console.error('The given book-ids do not match any description', verse);
            return false;
        }

        if (verse.getFrom() > verse.getTo) {
            console.error('The verse starts ends before it starts', verse);
            return false;
        }

        // Validate chapter-range
        const fromBook = this._d[verse.getFromBookId()];
        const toBook   = this._d[verse.getToBookId()];

        if (verse.getFromChapter() <= 0 || verse.getFromChapter() > fromBook.getChapterCount()) {
            console.error('Invalid chapter in bibleverse-from', verse);
            return false;
        }
        if (verse.getToChapter() <= 0 || verse.getToChapter() > toBook.getChapterCount()) {
            console.error('Invalid chapter in bibleverse-to', verse);
            return false;
        }

        // Validate chapter verse combination
        if (fromBook.isValidVerse(verse.getFromChapter(), verse.getFromVerse()) === false) {
            console.error('Invalid verse in fromchapter', verse);
            return false;
        }

        if (toBook.isValidVerse(verse.getToChapter(), verse.getToVerse()) === false) {
            console.error('Invalid verse in chapter', verse);
            return false;
        }

        return true;
    },

    _getBookLabel: function (bookId, length) {
        if (!length || (length !== 'short' && length !== 'long')) {
            length = 'short';
        }


        if (this._bibleBookIdExists(bookId) === false) {
            return 'UNKOWN-BOOK-ID';
        }

        const book = this._getBibleBook(bookId);

        if (length === 'short') {
            return book.getNameShort();
        } else {
            return book.getNameLong();
        }

    },

    _getBibleBook(bookId) {
        return this._d[bookId];
    },

    _bibleBookIdExists: function (bookId) {
        return (this._d.hasOwnProperty(bookId));
    },

    isVerseUntilChapterEnd(bibleverse) {
        const book = this._getBibleBook(bibleverse.getToBookId());

        if (!book) {
            console.error('Missing book-data for bibleverse', bibleverse);
            return false;
        }

        const maxVerse = book.getVerseCountForChapter(bibleverse.getToChapter());

        return maxVerse === bibleverse.getToVerse();
    }
};