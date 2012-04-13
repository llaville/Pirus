Pirus, the companion of Pirum for delegate the themes management
================================================================

Installation
------------

    $ pear channel-discover bartlett.laurent-laville.org
    $ pear install bartlett/Pirus

Interface
---------

pirum --help

    Pirus (cli) by Laurent Laville.

    Usage:
      pirus [options]
      pirus [options] <command> [options] [args]

    Options:
      -h, --help     show this help message and exit
      -v, --version  show the program version and exit

    Commands:
      build   Build the full content of the target directory.
      add     Add a new release.
      remove  Remove an old release.


pirum build --help

    Build the full content of the target directory.

    Usage:
      pirus [options] build [options] <targetDir>

    Options:
      -t theme, --theme=theme                       The name of the theme
                                                    (HTML/CSS) to apply.
      -d templatesDir, --templatesDir=templatesDir  Base directory of all the
                                                    Pirum themes.
      -h, --help                                    show this help message and
                                                    exit

    Arguments:
      targetDir  The root directory of the PEAR channel server.


Features
--------

With the same simplicity as Pirum build your PEAR Channel server, Pirus handle the 
index.html and pirum.css files of your project.

* define default templates directory and theme with a simple INI file
* select the directory where to find your themes with a simple 
* select the theme to skin your web PEAR channel site
* and of course the same Pirum features
* included the basic default template of Pirum

Bonus :

With only 12k, Pirus included also the skin used by 
the [Bartlett PEAR Channel](http://bartlett.laurent-laville.org).

About
=====

Requirements
------------

- PHP 5.1.0 or better
- Pirum 1.0.5 or better

Submitting bugs and feature requests
------------------------------------

Bugs and feature requests are tracked on [GitHub](https://github.com/llaville/pirus/issues)

Author
------

- Laurent Laville <pear@laurent-laville.org>

License
-------

Pirus is licensed under the BSD License - see the LICENSE file for details
