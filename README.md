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

TBD (not implemented yet)

### Running the tests

The specs (a.k.a. unit tests) are written with [PhpSpec](https://phpspec.net) and can be run with:

```bash
vendor/bin/phpspec run
```

The behavioural tests (functional in this case) are done with [Behat](http://behat.org). Run them with:
```bash
vendor/bin/behat
```

**Note:** Until the solution is complete, some of behat tests are expected to fail - those are the remaining features to implement.
