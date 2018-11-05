class BibleBook {

    constructor(bookId, nameLong, nameShort, namePattern, chapterCount, verseCount) {
        this.setBookId(bookId);
        this.setNameLong(nameLong);
        this.setNameShort(nameShort);
        this.setNamePattern(namePattern);
        this.setChapterCount(chapterCount);
        this.setVerseCount(verseCount);
    }


    getBookId() {
        return this.bookId;
    };

    setBookId(bookId) {
        this.bookId = bookId;
    };

    getNameLong() {
        return this.nameLong;
    };

    setNameLong(nameLong) {
        this.nameLong = nameLong;
    };

    getNameShort() {
        return this.nameShort;
    };

    setNameShort(nameShort) {
        this.nameShort = nameShort;
    };

    getNamePattern() {
        return this.namePattern;
    };

    setNamePattern(namePattern) {
        this.namePattern = namePattern;
    };

    getChapterCount() {
        return this.chapterCount;
    };

    setChapterCount(chapterCount) {
        this.chapterCount = chapterCount;
    };

    getVerseCount() {
        return this.verseCount;
    };

    getVerseCountForChapter(chapterNo) {
        return this.verseCount[chapterNo];
    }

    setVerseCount(verseCount) {
        var i;
        this.verseCount = {};
        for (i in verseCount) {
            if (verseCount.hasOwnProperty(i)) {
                this.verseCount[i] = verseCount[i];
            }
        }
    };

    chapterExists(chapterNo) {
        return this.verseCount.hasOwnProperty(chapterNo);
    };

    isValidVerse(chapterNo, verseNo) {
        return (this.chapterExists(chapterNo) && this.verseCount[chapterNo] >= verseNo && verseNo > 0);
    };

}

export default BibleBook;