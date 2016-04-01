<?php

use JNCampbell\UniqueSequenceFinder;

require __DIR__ . "../../vendor/autoload.php";

$listOfWords = file(__DIR__ . "/input/words.txt");

$sequencer = new UniqueSequenceFinder($listOfWords);

$sequencer->generateOutput(__DIR__ . "/output/");