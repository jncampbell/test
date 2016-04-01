<?php

namespace JNCampbell;

class UniqueSequenceFinder {

    /**
     * The list of words
     *
     * @var array $listOfWords
     */
    protected $listOfWords = [];

    /**
     * The length of each sequence
     *
     */
    const LENGTH_OF_SEQUENCE = 4;

    /**
     * The file path used to store unique sequences
     *
     */
    const UNIQUE_FILE_NAME = "uniques.txt";

    /**
     * The file path used to store full words
     *
     */
    const FULL_WORD_FILE_NAME = "fullwords.txt";

    /**
     * UniqueFourLetterSequencer constructor.
     *
     * @param array $listOfWords
     */
    public function __construct(array $listOfWords)
    {
        $this->listOfWords = $listOfWords;
    }

    /**
     * Returns a ksorted multi-d array with sequences as keys and an array of words they're part of as values
     *
     *
     * @return array
     */
    public function getSequenceWordPairs() : array
    {
        $sequences = [];

        foreach($this->listOfWords as $word) {

            // First we do a little cleaning up to make sure data is uniform.
            $word = strtolower(trim($word));

            $lengthOfWord = iconv_strlen($word);

            if ($lengthOfWord >= self::LENGTH_OF_SEQUENCE) {
                $i = 0;
                // We want to read substrings until the last four chars of the string.
                while($i <= $lengthOfWord - self::LENGTH_OF_SEQUENCE) {
                    if ($this->isAlphaSequence($nextFourChars = substr($word, $i, self::LENGTH_OF_SEQUENCE))) {
                        // Then we want to store each word and all of the four-char sequences that make up the word.
                        $sequences[$word][] = $nextFourChars;
                    }
                    $i++;
                }
            }
        }


        $newArray = [];
        foreach ($sequences as $key => $value) {
            foreach($value as $seq) {
                // Lastly, we store each sequence as an array key and all the words that include that sequence as
                // the array value. This is to make it easier to discard sequences that appear more than once.
                $newArray[$seq][] = $key;
            }
        }

        ksort($newArray);
        return $newArray;
    }

    /*
     * Checks if a given sequence contains all alpha characters
     *
     * @return bool
     */
    private function isAlphaSequence(string $sequence) : bool
    {
        return ctype_alpha($sequence);
    }

    /**
     * Removes sequences that appear more than once and returns the remaining sequence-word pairs
     *
     * @return array
     */
    public function getUniqueSequenceWordPairs() : array
    {
        $sequenceWordPairs = $this->getSequenceWordPairs();
        return array_filter($sequenceWordPairs, function($item) {
            if (count($item) === 1) {
                return $item;
            }
        });
    }

    /**
     * Generates the output for unique sequence-word pairs
     *
     */
    public function generateOutput(string $outputDirectory)
    {
        if (!is_dir($outputDirectory)) {
            mkdir($outputDirectory);
        }

        $uniqueFile = fopen($outputDirectory . self::UNIQUE_FILE_NAME, 'w+');
        $fullWordFile = fopen($outputDirectory . self::FULL_WORD_FILE_NAME, 'w+');

        $uniqueSequenceWordPairs = $this->getUniqueSequenceWordPairs();
        $sequences = array_keys($uniqueSequenceWordPairs);
        $fullWords = array_map('implode', array_values($uniqueSequenceWordPairs));

        fwrite($uniqueFile, implode(PHP_EOL, $sequences));
        fwrite($fullWordFile, implode(PHP_EOL, $fullWords));

        fclose($uniqueFile);
        fclose($fullWordFile);
    }
}