# Cadence #
The _easy_ way to record time in Jira / Tempo.

I used to spend up to 30 minutes logging work to Jira. It was tedious, boring and I would constantly forget. Cadence allows me to log all my work in mere minutes and causes me no stress.

## Installation ##
1. ```git clone https://github.com/hradtke/cadence.git```
1. ```cp cadence/.jira.dist ~/```
1. ```vim ~/.jira``` and fill in the configuration values

## Log Format ##
The log format is very simple so work can quickly be recorded. Each entry in the work log must be on a separate line. Blank lines are permitted.

Syntax for work log entries:

    Entry       = issue time-format comment
    issue       = [A-Z]([A-Z0-9_]+)-[1-9][0-9]*
    time-format = [1-9][0-9]*(d|h|m)
    comment     = *

Example:

    TASK-130 15m scrum
    PROJECT-52 3h worked on the widget
    HR-11 30m performed me an interview

## Sending the work log to Jira ##
Cadence will display each issue it logs work to. Example:

    # php ~/cadence/bin/cadence.php
    DISC-130
    DELTA-52
    WAP-411
    API-43
    DISC-89
    DISC-25
    API-363
    IOS-1698
    API-43
    API-43
    ATASKS-3
    ATASKS-4
    DISC-172
    DISC-89
    ATASKS-3

If there is a problem logging the work, Cadence will show the error detail. It will continue logging subsequent entries in the work log. Example:

    # php ~/cadence/bin/cadence.php
    Failed to record time for BOGUS-100
    HTTP Response code of 404
    stdClass Object
    (
        [errorMessages] => Array
            (
                [0] => Issue Does Not Exist
            )

        [errors] => stdClass Object
            (
            )
    
    )


