# Installation

You can use Packagist to install the package on a web server or your lacal computer, e.g. *Command Prompt*, enter `composer create-project sourcepot/asset {add your target directory here}`. 

The following code examples require the namespace to be set to `namespace SourcePot\Asset;`.

## Features
- Simple and complex value matches, from simple string contain to UNYCOM cases
- Extraction of a 'needles' from the input value used to pre-filter database entries and create a haystack
- Returns a match probability 0...1 for the input value compared with entries of the haystack

# Sample code
```
<?php

namespace SourcePot\Match;

require_once('../../vendor/autoload.php');

// create an instance of class MatchValues
$matchObj = new MatchValues(); 

// set the value you like to match with other values of a haystack and set match type
$matchObj->set('q2015P45527WEPL122','unycom');

// prepare the match, this will also return a needle, e.g. to filter entries from a database to create the haystack
$needle=$matchObj->prepareMatch();

// match with a value of the haystack, typically used in a loop. $match is a value in the range of 0...1
$match=$matchObj->match('2015P45527WE122');

// get all data with regard to the match as an array: input value, match value, needle, match type and match result
$result=$matchObj->get();

var_dump($result);

?>
```
# Match types

## Identical
## Contains / Contains (ci)
## Does not contain / Does not contain (ci)
## Correlation contains / Correlation match
## Integer match
## Float match
## String chunk match (middle chunk as needle)
## Patent case
## UNYCOM case
## DateTime'

# Evaluation web page

An evaluation web page is provided with this package. Here is a screenshot of the evaluation web page:
<img src="./assets/evaluation-page.png" alt="Evaluation web page" style="width:100%"/>