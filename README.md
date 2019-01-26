# commission-calculator

Solution for a test task described in [TASK.md](TASK.md)

### Setup with VirtualBox / Vagrant

Clone this repository and run `vagrant up` - it should take care of all the environment setup for you (including config files and composer dependencies).

From there on, anything you want to run should be done inside the VM, i.e. after running `vagrant ssh` and `cd /var/www`

### Setup without VM

- Clone this repository
- Make sure you have PHP 7.2 with these extensions:
  - ctype
  - iconv
  - mbstring
  - xml
- Make sure you have composer installed
- Run `composer install` in the project directory
- Copy `.env.dist` to `.env` (modify if/as necessary - defaults should suffice)
- Copy `phpspec.yml.dist` to `phpspec.yml` (modify if/as necessary - defaults should suffice)
- Copy `behat.yml.dist` to `behat.yml` (modify if/as necessary - defaults should suffice)

### Running the calculator

There is a Symfony Console command implemented which reads the input CSV file, performs the calculations and presents results to stdout.

```bash
bin/console commissions:calculate <file>
```

File path is relative to the directory from which the command is run.

There is a [var/data/sample.csv](var/data/sample.csv) file which contains data from the given example.

```bash
bin/console commissions:calculate var/data/sample.csv
```

### Running the tests

The specs (a.k.a. unit tests) are written with [PhpSpec](https://phpspec.net) and can be run with:

```bash
vendor/bin/phpspec run
```

The behavioural tests (functional in this case) are done with [Behat](http://behat.org). Run them with:
```bash
vendor/bin/behat
```
