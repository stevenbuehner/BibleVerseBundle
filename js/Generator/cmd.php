<?php
/**
 * This file was created by  steven
 * Created: 30.08.16 17:16
 * All Rights reserved. No usage without written permission allowed.
 */

include_once __DIR__ . '/../../vendor/autoload.php';
include_once __DIR__ . '/BibleVerseGenerator.php';

$runner = new \StevenBuehner\JS\Generator\BibleVerseGenerator();

$runner->run();

