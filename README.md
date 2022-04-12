# wpforms-newbranch

## Description

Create git branch name which meets WPForms team naming convention

Click below to see video on youtube:

[![IMAGE ALT TEXT HERE](https://img.youtube.com/vi/r3Mkgu3roTg/0.jpg)](https://www.youtube.com/watch?v=r3Mkgu3roTg)

## Installation

Download:

```shell
git clone git@github.com:kkarpieszuk/wpforms-newbranch.git
cd wpforms-newbranch
```

Move `wpforms-newbranch.phar` file somewhere in your PATH, for example:

```shell
chmod +x wpforms-newbranch.phar
sudo mv wpforms-newbranch.phar /usr/local/bin/newbranch
```

## Usage

This tool expects (but not strictly requires) as the first parameter the string which will be converted into branch name:

```shell
# example command:
newbranch "core/email field: values not sanitized #123"
# the above will create branch with name 
# core/123-email-field-values-not-sanitized
```

The "perfect" parameter fits following pattern:
```shell
{plugin or addon name}/{component name}: {issue keywords} #{issue ID}
```
However, none of those parts are required. If you skip some of them or `newbranch` tool will not be able to recignize them for any other reason, it will interactively ask you to provide missing parts. 

You can even run the tool without any parameters and it will run the wizard:

```shell
bin/createbranch 
Select namespace for the branch. Selected namespace will be prepended to the branch name and followed by slash.
0: (skip the namespace part)    16: mailchimp 
1: core                         17: offline-forms 
2: activecampaign               18: paypal-standard 
[...]
14: getresponse                 30: zapier 
15: hubspot 
Please provide the number: 1
I did not recognize ID at the end of copied title. Please enter it manually: 123
Please provide component, feature or field this branch is relevant (leave empty to skip): 
```

### Tips, tricks and examples.

Don't forget to wrap the title and id in the quotes (without them shell will interpret everything after `#` as comment and ignore ID).

### Development

To build tool from source run in main directory:
```shell
vendor/bin/phar-composer build .
```

To run development version from sources run:
```shell
bin/createbranch "test branch bame #123"
```
