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

    bibleVerseToString: function (verse, displayLength) {

        if (!displayLength || (displayLength !== 'short' && displayLength !== 'long')) {
            displayLength = 'short';
        }

        var resultString = 'UNKNOWN';

        if (this.isBibleVerseValid(verse) === true) {
            resultString = this._getBookLabel(verse.getFromBookId(), displayLength) + ' ';
            resultString += verse.getFromChapter();

            if (verse.isSameBook() && verse.isSameChapter() && verse.getFromVerse() === 1 && this.isVerseUntilChapterEnd(verse)) {
                // Do not display the verse!
            } else {
                resultString += this.chapterVerseSeparator + verse.getFromVerse();
            }


            if (!verse.isSingleVerse()) {

                if (verse.isSameBook()) {

                    resultString += this.verseVerseSeparator;

                    if (verse.isSameChapter()) {
                        resultString += verse.getToVerse();
                    } else {
                        resultString += verse.getToChapter() + this.chapterVerseSeparator + verse.getToVerse();
                    }

                } else {
                    // Todo: Different Books => Not supported yet!
                }
                
            }

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