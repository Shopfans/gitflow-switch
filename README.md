# gitflow-switch

Simple web page to manage your git-based testing environments with a few clicks
in the browser. Forget about command line and ssh console to update and
configure your testing environment.

It's a one page application that might be used as is without any changes. But
you are able to make it more friendly to your project. You need only create a
file index.config.php near the index.php. This file must returns array with
application options.

## Installation

Make a directory at your web server and put here this application (`index.php`
only). Then create a few directories which will be used as testing environments.
Load each directory with a git project you'd like to test. Change owner for all
these directories to the user running web server, usually it's a `www-data`.

Right now, if you open the directory with this application you will see a list
of directories with your projects. You will see two controls for each testing
directory is a drop down list with available branches of the project and Update
button, which makes simple git pull. Btw, you must configure your git with using
keys, otherwise it would not be work because we can't pass login and password
every time. Maybe next time.

## Configuration

Try to update your testing environment with button Update. You will see an
output of all executed commands, like in terminal. This helps you controll the
made actions and see results.

But, let's say you'd like have a new command which cleans your temorary files.
You must create an `index.config.php` and fill it with next code:

    <?php

    return array (
        'environments' => array (
            '*' => array (
                'commands' => array (
                    'Update' => 'update_branch',
                    'Clean' => 'clean_temp_files',
                ),
            ),
        ),
        'commands' => array (
            'clean_temp_files' => '
                cd PATH
                rm -rf tmp/*
            ',
        ),
    );

It's done. Now, if you reload the page you will find a new button 'Clean' with
all your testing environments. And if you press to Clean, you will see your
commands and details output. Now, you can clean temporary files right from
browser.

The programm already have two commands `update_branch` and `change_branch`. You
can override them with your extra commands, like cleaning temp files, apply
migrations, etc. You can create as many commands as you need. Moreover, you can
define a few types of testing environments. Just replace asterisk in the
environment key with a regular express which must match to testing environment
path and you can set alternative commands.
