(function () {
    "use strict";

    function BibleVerse() {
        this.init();
    }

    BibleVerse.prototype.init = function () {
        this.bookId      = null;
        this.fromChapter = null;
        this.fromVerse   = null;
        this.toChapter   = null;
        this.toVerse     = null;
    };

    BibleVerse.prototype.getBookId = function () {
        return this.bookId;
    };

    BibleVerse.prototype.getFromChapter = function () {
        return this.fromChapter;
    };

    BibleVerse.prototype.getFromVerse = function () {
        return this.fromVerse;
    };

    BibleVerse.prototype.getToChapter = function () {
        return this.toChapter;
    };

    BibleVerse.prototype.getToVerse = function () {
        return this.toVerse;
    };

    BibleVerse.prototype.setBookId = function (bookId) {
        this.bookId = bookId;
    };

    BibleVerse.prototype.setFromChapter = function (fromChapter) {
        this.fromChapter = fromChapter;
    };

    BibleVerse.prototype.setFromVerse = function (fromVerse) {
        this.fromVerse = fromVerse;
    };

    BibleVerse.prototype.setToChapter = function (toChapter) {
        this.toChapter = toChapter;
    };

    BibleVerse.prototype.setToVerse = function (toVerse) {
        this.toVerse = toVerse;
    };

    BibleVerse.prototype.setVerse = function (bookId, fromChapter, fromVerse, toChapter, toVerse) {
        this.setBookId(bookId);
        this.setFromChapter(fromChapter);
        this.setFromVerse(fromVerse);
        this.setToChapter(toChapter);
        this.setToVerse(toVerse);
    };

    BibleVerse.prototype.isSingleVerse = function () {
        return (this.getBookId() !== null && this.getFromChapter() === this.getToChapter() && this.getFromVerse() === this.getToVerse());
    };


    function BibleBook(bookId, nameLong, nameShort, namePattern, chapterCount, verseCount) {
        this.setBookId(bookId);
        this.setNameLong(nameLong);
        this.setNameShort(nameShort);
        this.setNamePattern(namePattern);
        this.setChapterCount(chapterCount);
        this.setVerseCount(verseCount);
    }

    BibleBook.prototype.getBookId = function () {
        return this.bookId;
    };

    BibleBook.prototype.setBookId = function (bookId) {
        this.bookId = bookId;
    };

    BibleBook.prototype.getNameLong = function () {
        return this.nameLong;
    };

    BibleBook.prototype.setNameLong = function (nameLong) {
        this.nameLong = nameLong;
    };

    BibleBook.prototype.getNameShort = function () {
        return this.nameShort;
    };

    BibleBook.prototype.setNameShort = function (nameShort) {
        this.nameShort = nameShort;
    };

    BibleBook.prototype.getNamePattern = function () {
        return this.namePattern;
    };

    BibleBook.prototype.setNamePattern = function (namePattern) {
        this.namePattern = namePattern;
    };

    BibleBook.prototype.getChapterCount = function () {
        return this.chapterCount;
    };

    BibleBook.prototype.setChapterCount = function (chapterCount) {
        this.chapterCount = chapterCount;
    };

    BibleBook.prototype.getVerseCount = function () {
        return this.verseCount;
    };

    BibleBook.prototype.setVerseCount = function (verseCount) {
        var i;
        this.verseCount = {};
        for (i in verseCount) {
            if (verseCount.hasOwnProperty(i)) {
                this.verseCount[i] = verseCount[i];
            }
        }
    };

    BibleBook.prototype.chapterExists = function (chapterNo) {
        return this.verseCount.hasOwnProperty(chapterNo);
    };

    BibleBook.prototype.isValidVerse = function (chapterNo, verseNo) {
        return (this.chapterExists(chapterNo) && this.verseCount[chapterNo] <= verseNo && verseNo > 0);
    };

    if (!window.hasOwnProperty('SB')) {
        var SB = {};
    }

    SB.BibleVerseService = { // jshint ignore:line
        _d: { //{{ include('BookListing') }}
        },
        biblePattern: '{{ include("biblePattern") }}',
        chapterVerseSeparator: '{{ chapterVerseSeparator }}',

        bibleVerseToString: function (verse, length) {
            if (!length || (length !== 'short' && length !== 'long')) {
                length = 'short';
            }

            var resultString = 'UNKNOWN';

            if (this.isBibleVerseValid(verse) === true) {
                resultString = this._getBookLabel(verse.getBookId(), length) + ' ';
                resultString += verse.getFromChapter() + this.chapterVerseSeparator + verse.getFromVerse();

                if (!verse.isSingleVerse()) {
                    resultString += '-' + verse.getToChapter() + this.chapterVerseSeparator + verse.getToVerse();
                }
            }

            return resultString;
        },

        isBibleVerseValid: function (verse) {
            if (!(verse instanceof BibleVerse)) {
                return false;
            }

            if (!this._d.hasOwnProperty(verse.getBookId())) {
                return false;
            }

            // Validate chapter-range
            var book = this._d[verse.getBookId()], chapterCount = book.getChapterCount();
            if (verse.getFromChapter() <= 0 || verse.getToChapter() < verse.getFromChapter() || verse.getToChapter() > chapterCount) {
                return false;
            }

            // Validate chapter verse combination
            if (book.isValidVerse(verse.getFromChapter(), verse.getFromVerse()) === false || book.isValidVerse(verse.getToChapter(), verse.getToVerse()) === false) {
                return false;
            }

            // Validate from < to
            if (book.getFromChapter() === book.getToChapter() && book.getFromVerse() > book.getToVerse()) {
                return false;
            }

            return true;
        },

        _getBookLabel: function (bookId, length) {
            if (!length || (length !== 'short' && length !== 'long')) {
                length = 'short';
            }

            var result = '';

            if (this._bibleBookIdExists(bookId) === false) {
                result = 'UNKOWN-BOOK-ID';
            }

            if (length === 'short') {
                result = this._d.getNameShort();
            } else {
                result = this._d.getNameLong();
            }

            return result;
        },

        _bibleBookIdExists: function (bookId) {
            return (this._b.hasOwnProperty(bookId));
        }
    };
});