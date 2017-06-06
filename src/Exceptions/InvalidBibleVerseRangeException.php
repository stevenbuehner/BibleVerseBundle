<?php

namespace StevenBuehner\BibleVerseBundle\Exceptions;

use Throwable;

class InvalidBibleVerseRangeException extends \Exception {

	public function __construct($message = "", $code = 0, Throwable $previous = NULL) {
		parent::__construct($message, $code, $previous);
	}
}