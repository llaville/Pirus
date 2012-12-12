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
      -v, --verbose  Output more verbose information
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

Extra
-----
Since release 1.2.0, Pirus is also available as a [phar](http://http://bartlett.laurent-laville.org/get/pirus.phar)
package version.

You can also download the [skin](http://php5.laurent-laville.org/pirus/templates/bartlett.zip) (12k zip archive)
used by the [Bartlett PEAR Channel](http://bartlett.laurent-laville.org).
Extract contents (included directory structure) to your Pirus template directory.

About
=====

Requirements
------------

- PHP 5.2.1 or better
- Recommandations: Pirum 1.0.5 (for PHP 5.2), Pirum 1.1.4 (for PHP 5.3 / 5.4)

Submitting bugs and feature requests
------------------------------------

Bugs and feature requests are tracked on [GitHub](https://github.com/llaville/pirus/issues)

Author
------

- Laurent Laville <pear@laurent-laville.org>

License
-------

Pirus is licensed under the BSD License - see the LICENSE file for details
