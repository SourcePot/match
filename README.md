The PHP classes in this repository allow complex data values such as patent numbers, dates and times etc. to be matched. To do gthis, in a first step the data value to be compared with as well as the match type needs to be laoded. After this the match is prepared. Depending on the match type, a needle is generated for database pre-filtering to create a haystack of data values from a database. In the last step the matches are performed between the initially loaded data value and the haystack values. Each match provides a match probability in the range 0...1, with 1 → complete match and 0 → no match.

# Installation

You can use Packagist to install the package on a web server or your local computer, e.g. *Command Prompt*, enter `composer create-project sourcepot/asset {add your target directory here}`. 

## Features
- Simple and complex value matches, from simple string contain to UNYCOM cases
- Extraction of a 'needles' from the input value used to pre-filter database entries and create a haystack
- Returns a match probability 0...1 for the input value compared with entries of the haystack

# Sample code

In the following example source code the UNYCOM case number “2015P45527WEPL122” is extracted from the string “q2015P45527WEPL12” and loaded to the match object as the match value for a UNYCOM match. 

```
<?php

namespace SourcePot\Match;

require_once('../../vendor/autoload.php');

// create an instance of class MatchValues
$matchObj = new MatchValues(); 

// set the value you want to match with other values, e.g. of a haystack and set match type
$matchObj->set('q2015P45527WEPL122','unycom');

// prepare the match, this will also return a needle, e.g. to filter entries from a database to create the haystack
$needle=$matchObj->prepareMatch();

// match with a value of the haystack, typically used in a loop. $match is the match probability in the range of 0...1 
$match=$matchObj->match('2015P45527WE122');

// get all data with regard to the match as an array: input value, match value, needle, match type and match result
$result=$matchObj->get();

/* $result is 
  ['value'=>'2015P45527WE122',
   'matchType'=>'unycom',
   'obj'=>'2015P45527WE 12',
   'toMatchValue'=>'q2015P45527WEPL122',
   'match'=>0.9,
   'needle'=>'%45527%'
   ]
*/
var_dump($result);

?>
```

# Match types

> [!CAUTION]
> The actual match result values might depend on the version of this package and related packages.

## String chunk match

The following example uses strings whose sub-strings or components are separated by semicolons. Possible separators are defined by the regular expression `[\{\}\[\]\(\)\'";,|\/\\.\s]+`, which is defined as a constant. The middle sub-string is used as needle.

| haystack value  | to match value | needle | match |
| ------------- | ------------- | ------------- | ------------- |
| 2024-11-23;72661;ABCDEF | 2024-11-23;72661;ABCDEF | %72661% | 1 |
| 2024-11-22;72661;ABCDEF | 2024-11-23;72661;ABCDEF | %72661% | 0.66666666666667 |
| 2024-11-23;72660;ABCDEF | 2024-11-23;72661;ABCDEF | %72660% | 0.66666666666667 |
| 2024-11-22;72661;ABCDEF | 2024-11-23;72661;ABCDEF | %72660% | 0.33333333333333 |
| 2024-11-22;72660;ABCdEF | 2024-11-23;72661;ABCDEF | %72660% | 0 |

## Patent case

For this match, the patent or application number is broken down into two components: Country code and property right number. A check digit is ignored and a possible year is accepted with 2 or 4 digits, e.g. 09 == 2009 == 1909 etc.

Example: EP 2009 716 604.5 → Country code: `EP` and Naumber: `09716604` 


| haystack value  | to match value | needle | match |
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

A weight is assigned to each of the components of the UNYCOM case number (year, file type, number, region, country and part). The weights are defined as constants in the `SourcePot\Match\UNYCOM` class and can be customised to your own requirements.

| haystack value  | to match value | needle | match |
| ------------- | ------------- | ------------- | ------------- |
| 2009P53143WECZ03 | 2009P53143WECZ03 | %53143% | 1 |
| 2019P53143WECZ03 | 2009P53143WECZ03 | %53143% | 0.8 |
| 2009XP53143WECZ03 | 2009P53143WECZ03 | %53143% | 0.9 |
| 2009P52143WECZ03 | 2009P53143WECZ03 | %53143% | 0.6 |
| 2009P53143EPCZ03 | 2009P53143WECZ03 | %53143% | 0.9 |
| 2009P53143WEDE03 | 2009P53143WECZ03 | %53143% | 0.9 |
| 2009P53143WECZ02 | 2009P53143WECZ03 | %53143% | 0.9 |
| 2009P53143WECZ | 2009P53143WECZ03 | %53143% | 0.9 |
| 2009P53143EP | 2009P53143WECZ03 | %53143% | 0.7 |
| 2008P52143EP | 2009P53143WECZ03 | %53143% | 0.1 |
| 2008F52143 | 2009P53143WECZ03 | %53143% | 0 |

## DateTime

> [!NOTE]  
> The needle will be based on the database timezone. 

If the date and the time match, the result will be 1. If the date does not match, the result will be 0. If the date matches but the time does not, the result will be <1 and >0.

| haystack value  | to match value | needle | match |
| ------------- | ------------- | ------------- | ------------- |
| 2023-07-13 | July 13, 2023 | 2023-07-13% | 1 |
| 2023-07-13 | July 13, 2023 2.00pm | 2023-07-13% | 0.91666666666667 |
| 2023-07-11 | July 13, 2023 2.00pm | 2023-07-11% | 0 |
| 2023-07-11 12:00:00 (Europe/London) | 2023-07-11 1.00pm (Europe/Berlin) | 2023-07-11% | 1 |

## Identical

| haystack value  | to match value | match |
| ------------- | ------------- | ------------- |
| 100095646\chä6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 1 |
| 100095646\ch6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0 |

## Contains / Contains (ci)

`(ci)` is the case insensitive match type.

| haystack value  | to match value | match |
| ------------- | ------------- | ------------- |
| 100095646\chä6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 1 |
| 100095646\ch6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0 |
| 100095646\chä6477/测试,用例 | 100095646\chä6477/测试,用例(Hallo)Test | 1 |

## Does not contain / Does not contain (ci)

`(ci)` is the case insensitive match type.

| haystack value  | to match value | match |
| ------------- | ------------- | ------------- |
| 100095646\chä6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0 |
| 100095646\ch6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 1 |
| 100095646\chä6477/测试,用例 | 100095646\chä6477/测试,用例(Hallo)Test | 0 |

## Correlation contains

| haystack value  | to match value | match |
| ------------- | ------------- | ------------- |
| 100095646\chä6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 1 |
| 100095646\ch6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0.6969696969697 |
| 100095646\chä6477/测试,用例 | 100095646\chä6477/测试,用例(Hallo)Test | 1 |

## Correlation match

| haystack value  | to match value | match |
| ------------- | ------------- | ------------- |
| 100095646\chä6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 1 |
| 100095646\ch6477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0.67647058823529 |
| 100095646\chrt477/测试,用例(Hallo)Test | 100095646\chä6477/测试,用例(Hallo)Test | 0.94117647058824 |
| 100095646\chä6477/测试,用例 | 100095646\chä6477/测试,用例(Hallo)Test | 0.67647058823529 |

## Integer match

| haystack value  | to match value | match |
| ------------- | ------------- | ------------- |
| 100095646 | 100095646 | 1 |
| 100095647 | 100095646 | 0.99999999000956 |
| 200095647 | 100095646 | 0.50023899820269 |
| 1 | 100095646 | 0 |

## Float match

| haystack value  | to match value | match |
| ------------- | ------------- | ------------- |
| 1.45434 | 1.454,34e-3 | 1 |
| 1.45435 | 1.454,34e-3 | 0.99999312407605 |
| 14.5434 | 1.454,34e-3 | 0.1 |
| 145.434 | 1.454,34e-3 | 0.01 |

# Evaluation web page

An evaluation web page is provided with this package. Here is a screenshot of the evaluation web page:
<img src="./assets/evaluation-page.png" alt="Evaluation web page" style="width:100%"/>