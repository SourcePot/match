# Installation

You can use Packagist to install the package on a web server or your lacal computer, e.g. *Command Prompt*, enter `composer create-project sourcepot/asset {add your target directory here}`. 

The following code examples require the namespace to be set to `namespace SourcePot\Asset;`.

## Features


# Sample code
```
namespace SourcePot\Match;

require_once('../../vendor/autoload.php');

$matchObj = new MatchValues();  // create instance of match object

$matchObj->set($valueA,$matchtype); // set the value you like to match with other values of a haystack and set match type

$needle=$matchObj->prepareMatch();  // prepare the match, this will also return a needle, e.g. to filter entries from a database to create the haystack

$match=$matchObj->match($valueB); // match with a value of the haystack, typically used in a loop. $match is a value in the range of 0...1

$result=$matchObj->get();   // get all data with regard to the match, input values, needle, match type and match value
```

# Evaluation web page

An evaluation web page is provided with this package. Here is a screenshot of the evaluation web page:
<img src="./assets/evaluation-page.png" alt="Evaluation web page" style="width:100%"/>