# UniqueSequenceFinder

## Usage
1\.  Instantiate the UniqueSequenceFinder class with an array of words
```php
    $sequencer = new UniqueSequenceFinder(array $listOfWords);
```

2\. Call the generateOutput method with the path of the directory you want the files to be generated in.
```php
    $sequencer->generateOutput('/outputDirectoryPath/);
```

## Notes
I created a public directory to help you test the class quickly. It has:
    - An Input directory that contains the words.txt file you included with the directions.
    - An index.php file that instantiates the class and generates the the uniques.txt and fullwords.txt files as per your directions.