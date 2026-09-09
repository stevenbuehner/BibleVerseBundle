<?php
/**
 * This file was created by  steven
 * Created: 30.08.16 17:16
 * All Rights reserved. No usage without written permission allowed.
 */

require __DIR__ . '/../../vendor/autoload.php';

$runner = new \StevenBuehner\JS\Generator\BibleVerseGenerator();

$runner->run();
