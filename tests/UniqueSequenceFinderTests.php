<?php

use JNCampbell\UniqueSequenceFinder;

class UniqueSequenceFinderTests extends PHPUnit_Framework_TestCase
{
    protected $testList = [
        'bicycle',
        'book',
        'for',
        'recycle'
    ];

    const OUTPUT_DIRECTORY = __DIR__ . '/output/';

    const UNIQUE_SEQUENCES_OUTPUT_PATH = self::OUTPUT_DIRECTORY . "uniques.txt";

    const FULL_WORDS_OUTPUT_PATH = self::OUTPUT_DIRECTORY . "fullwords.txt";

    public function setUp()
    {
        $this->removeOutputFiles();
    }

    public function tearDown()
    {
        $this->removeOutputFiles();
    }

    public function test_it_can_be_constructed()
    {
        $sequencer = new UniqueSequenceFinder($this->testList);

        $this->assertInstanceOf(UniqueSequenceFinder::class, $sequencer);
    }

    public function test_it_can_get_sequence_word_pairs()
    {
        $expected = [
            'bicy' => ['bicycle'],
            'book' => ['book'],
            'cycl' => ['bicycle', 'recycle'],
            'ecyc' => ['recycle'],
            'icyc' => ['bicycle'],
            'recy' => ['recycle'],
            'ycle' => ['bicycle', 'recycle']
        ];

        $sequencer = new UniqueSequenceFinder($this->testList);

        $actual = $sequencer->getSequenceWordPairs();

        $this->assertEquals($expected, $actual);
    }

    public function test_it_can_get_unique_sequence_word_pairs()
    {
        $expected = [
            'bicy' => ['bicycle'],
            'book' => ['book'],
            'ecyc' => ['recycle'],
            'icyc' => ['bicycle'],
            'recy' => ['recycle'],
        ];

        $sequencer = new UniqueSequenceFinder($this->testList);

        $actual = $sequencer->getUniqueSequenceWordPairs();

        $this->assertEquals($expected, $actual);
    }

    public function test_it_can_generate_output_to_files()
    {
        $expectedSequences = ["bicy\n", "book\n", "ecyc\n", "icyc\n", "recy"];
        $expectedFullWords = ["bicycle\n", "book\n", "recycle\n", "bicycle\n", "recycle"];

        $sequencer = new UniqueSequenceFinder($this->testList);

        $sequencer->generateOutput(self::OUTPUT_DIRECTORY);

        $actualSequences = file(self::UNIQUE_SEQUENCES_OUTPUT_PATH);
        $actualFullwords = file(self::FULL_WORDS_OUTPUT_PATH);

        $this->assertEquals($expectedSequences, $actualSequences);
        $this->assertEquals($expectedFullWords, $actualFullwords);

    }

    private function removeOutputFiles()
    {
        if (file_exists(self::UNIQUE_SEQUENCES_OUTPUT_PATH)) {
            unlink(self::UNIQUE_SEQUENCES_OUTPUT_PATH);
        }

        if (file_exists(self::FULL_WORDS_OUTPUT_PATH)) {
            unlink(self::FULL_WORDS_OUTPUT_PATH);
        }

        if (is_dir(self::OUTPUT_DIRECTORY)) {
            rmdir(self::OUTPUT_DIRECTORY);
        }
    }

}