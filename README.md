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

| Test value  | Heystack value | Needle | Result |
| ------------- | ------------- | ------------- | ------------- |
| PCT/EP2009/055033 | PCT/EP2009/055033 | %033% | 1 |
| PCT/EP09/055033 | PCT/EP2009/055033 | %033% | 0.9 |
| 09733996 | 09733996.4 | %996% | 1 |
| EP09733996 | 09733996.4 | %996% | 0.9 |
| EP2291970 | EP2291970B1 | %970% | 1 |
| EP3291910B1 | EP2291970B1 | %910% | 0.85714285714286 |

## UNYCOM case

## DateTime

[!NOTE]  
The needle will be based on the database timezone. 

If date and time match the result will be 1, if only date or time matches, the result will be 0.5 and there is no date and no time match.

| Test value  | Heystack value | Needle | Result |
| ------------- | ------------- | ------------- | ------------- |
| 2023-07-13 | July 13, 2023 | 2023-07-12% | 1 |
| 2023-07-11 | July 13, 2023 | 2023-07-10% | 0 |

# Evaluation web page

An evaluation web page is provided with this package. Here is a screenshot of the evaluation web page:
<img src="./assets/evaluation-page.png" alt="Evaluation web page" style="width:100%"/>