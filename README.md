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

1. Go to the issue description on the Github page and copy issue title with "#" and issue number at the end.
2. In the main directory of wpforms-plugin (ususally `wp-content/plugins`) run:
```shell
newbranch "Copied issue title #123"
```
The command above will create branch `core/123-copied-issue-title`.

### Tips, tricks and examples.

Don't forget to wrap the title and id in the quotes (without them shell will interpret everything after `#` as comment and ignore ID).

If you want to create branch in other namespace than `core/` just add it on the beggining of the title:

```shell
newbranch "wpforms-user-registration/This is the title of some ticket for WPForms User Registration addon #222"
> Created branch with name wpforms-user-registration/222-this-is-the-title-of-some-ticket-for-wpforms-user-registration-addon
```

If you forget providing ID at the end of the title, the prompt will ask you to enter it.
