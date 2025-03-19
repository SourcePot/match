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

> [!CAUTION]
> The actual match values might depend on the version of this package and related packages.

## Identical

| Test value  | Heystack value | Result |
| ------------- | ------------- | ------------- |
| 100095646\chä6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 1 |
| 100095646\ch6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0 |

## Contains / Contains (ci)

| Test value  | Heystack value | Result |
| ------------- | ------------- | ------------- |
| 100095646\chä6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 1 |
| 100095646\ch6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0 |
| 100095646\chä6477/测试,用例 | 100095646\chä6477/测试,用例(Hallo)Test | 1 |

## Does not contain / Does not contain (ci)

| Test value  | Heystack value | Result |
| ------------- | ------------- | ------------- |
| 100095646\chä6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0 |
| 100095646\ch6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 1 |
| 100095646\chä6477/测试,用例 | 100095646\chä6477/测试,用例(Hallo)Test | 0 |

## Correlation contains

| Test value  | Heystack value | Result |
| ------------- | ------------- | ------------- |
| 100095646\chä6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 1 |
| 100095646\ch6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0.6969696969697 |
| 100095646\chä6477/测试,用例 | 100095646\chä6477/测试,用例(Hallo)Test | 1 |

## Correlation match

| Test value  | Heystack value | Result |
| ------------- | ------------- | ------------- |
| 100095646\chä6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 1 |
| 100095646\ch6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0.67647058823529 |
| 100095646\chrt477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0.94117647058824 |
| 100095646\chä6477/测试,用例 | 100095646\chä6477/测试,用例(Hallo)Test | 0.67647058823529 |

## Integer match

| Test value  | Heystack value | Result |
| ------------- | ------------- | ------------- |
| 100095646 | 100095646 | 1 |
| 100095647 | 100095646 | 0.99999999000956 |
| 200095647 | 100095646 | 0.50023899820269 |
| 1 | 100095646 | 0 |

## Float match

| Test value  | Heystack value | Result |
| ------------- | ------------- | ------------- |
| 1.45434 | 1.454,34e-3 | 1 |
| 1.45435 | 1.454,34e-3 | 0.99999312407605 |
| 14.5434 | 1.454,34e-3 | 0.1 |
| 145.434 | 1.454,34e-3 | 0.01 |

## String chunk match (middle chunk as needle)
| Test value  | Heystack value | Needle | Result |
| ------------- | ------------- | ------------- | ------------- |
| 2024-11-23;72661;ABCDEF | 2024-11-23;72661;ABCDEF | %72661% | 1 |
| 2024-11-22;72661;ABCDEF | 2024-11-23;72661;ABCDEF | %72661% | 0.66666666666667 |
| 2024-11-23;72660;ABCDEF | 2024-11-23;72661;ABCDEF | %72660% | 0.66666666666667 |
| 2024-11-22;72661;ABCDEF | 2024-11-23;72661;ABCDEF | %72660% | 0.33333333333333 |
| 2024-11-22;72660;ABCdEF | 2024-11-23;72661;ABCDEF | %72660% | 0 |

## Patent case

For this match, the patent or application number is broken down into two components: Country code and property right number. A check digit is ignored and a possible year is accepted with 2 or 4 digits, e.g. 09 == 2009 == 1909 etc.

Example: EP 2009 716 604.5 → Country code: `EP` and Naumber: `09716604` 


| Test value  | Heystack value | Needle | Result |
| ------------- | ------------- | ------------- | ------------- |
| PCT/EP2009/055033 | PCT/EP2009/055033 | %033% | 1 |
| PCT/EP09/055033 | PCT/EP2009/055033 | %033% | 1 |
| PCT/09/055033 | PCT/EP2009/055033 | %033% | 0 |
| PCT/US09/055033 | PCT/EP2009/055033 | %033% | 0 |
| PCT/EP2009/055043 | PCT/EP2009/055033 | %043% | 0 |
| EP09716604.5 | EP 09 716 604.5 | %604% | 1 |
| EP09716604 | EP 09 716 604.5 | %604% | 1 |
| EP2009716604 | EP 09 716 604.5 | %604% | 1 |
| EP209716604 | EP 09 716 604.5 | %604% | 0 |
| EP2009516604 | EP 09 716 604.5 | %604% | 0 |

## UNYCOM case

## DateTime

> [!NOTE]  
> The needle will be based on the database timezone. 

If the date and the time match, the result will be 1. If the date does not match, the result will be 0. If the date matches but the time does not, the result will be <1 and >0.

| Test value  | Heystack value | Needle | Result |
| ------------- | ------------- | ------------- | ------------- |
| 2023-07-13 | July 13, 2023 | 2023-07-13% | 1 |
| 2023-07-13 | July 13, 2023 2.00pm | 2023-07-13% | 0.91666666666667 |
| 2023-07-11 | July 13, 2023 2.00pm | 2023-07-11% | 0 |
| 2023-07-11 12:00:00 (Europe/London) | 2023-07-11 1.00pm (Europe/Berlin) | 2023-07-11% | 1 |

# Evaluation web page

An evaluation web page is provided with this package. Here is a screenshot of the evaluation web page:
<img src="./assets/evaluation-page.png" alt="Evaluation web page" style="width:100%"/>