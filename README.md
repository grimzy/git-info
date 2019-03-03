# GitInfo
[![Build Status](https://travis-ci.org/grimzy/git-info.svg?branch=master)](https://travis-ci.org/grimzy/git-info) [![Maintainability](https://api.codeclimate.com/v1/badges/a470ddc2d9cec40c6b2f/maintainability)](https://codeclimate.com/github/grimzy/git-info/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/a470ddc2d9cec40c6b2f/test_coverage)](https://codeclimate.com/github/grimzy/git-info/test_coverage) [![StyleCI](https://github.styleci.io/repos/145178864/shield?branch=master)](https://github.styleci.io/repos/145178864)

Utility library to retrieve Git information from a working directory.

**Minimum requirement:** PHP 7.1



## Installation

```shell
composer require git-info
```

## Usage

```php
// Instantiate GitInfo
$gitInfo = new \Grimzy\GitInfo\GitInfo();

// Run all registered commands
$info = $gitInfo->getInfo();

// $info = [
//     'commit-hash-long' => 'd93287e02a3b7823623f383ffb443d686b41e5ae',
//     'commit-hash-short' => 'd93287',
//     'author-name' => 'John Doe',
//     'author-email' => 'john.doe@git-info',
//     'author-date' => '2018-08-17T20:58:21-04:00',
//     'subject' => 'Release v1.2.3'
//     'branch' => 'master'
//     'version' => 'v1.2.3'
// ]


// Run a subset of commands
$info = $gitInfo->getInfo(['branch', 'commit-hash-short', 'author-date']);

// $info = [
//     'branch' => 'master'
//     'commit-hash-short' => 'd93287',
//     'author-date' => '2018-08-17T20:58:21-04:00'
// ]


// Run one command
$info = $gitInfo->getInfo('version');

// $info = 'v1.2.3'
```



## Working directory

### Get the working directory

The default working directory is set using [`getcwd()`](http://php.net/manual/en/function.getcwd.php).

```php
$gitInfo = new \Grimzy\GitInfo\GitInfo();
$gwd = $gitInfo->getWorkingDirectory();
// $gwd = '/absolute/path/to/working/directory'
```

### Set the working directory

```php
// When instantiating GitInfo
$gitInfo = new \Grimzy\GitInfo\GitInfo('absolute/or/relative/path');

// OR

// Using setWorkingDirectory(string $path);
$gitInfo = new \Grimzy\GitInfo\GitInfo();
$gitInfo->setWorkingDirectory('absolute/or/relative/path');

$gwd = $gitInfo->getWorkingDirectory();
// $gwd = '/absolute/path/or/absolute/path/of/relative/path'
```

## Commands

### Get registered commands

```php
$commands = GitInfo::getCommands();

// Default commands:
// $commands = [
//     'commit-hash-long'  => 'git log -1 --pretty=%H',
//     'commit-hash-short' => 'git log -1 --pretty=%h',
//     'author-name'       => 'git log -1 --pretty=%aN',
//     'author-email'      => 'git log -1 --pretty=%aE',
//     'author-date'       => 'git log -1 --pretty=%aI',
//     'subject'           => 'git log -1 --pretty=%s',
//     'branch'            => 'git rev-parse --abbrev-ref HEAD',
//     'version'           => 'git describe --always --tags --abbrev=0'
// ]
```

### Add commands to GitInfo

**When instantiating:**

```php
$gitInfo = new \Grimzy\GitInfo\GitInfo(null, [
    'status' => 'git status'
]);

$info = $gitInfo->getInfo('status');
// $info = THE STATUS
```

**Using `static` method:**

```php
// Add the status command
GitInfo::addCommand('status', 'git status');

// All instances of GitInfo (existing and newly created) will now have a status command
$gitInfo = new GitInfo();
$info = $gitInfo->getInfo('status');

// $info = THE STATUS
```

## Tests

```shell
composer test
```

## Contributing

TODO: document

## Licence

GitInfo is licensed under the [MIT License](LICENSE).