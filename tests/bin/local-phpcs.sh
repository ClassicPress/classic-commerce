#!/usr/bin/env bash

CHANGED_FILES="$(git diff --name-only --diff-filter=ACMR "$(git merge-base HEAD upstream/develop)" | grep '\.php$')"

set -e

IGNORE="tests/cli/,includes/libraries/,includes/api/legacy/"

if [ "$CHANGED_FILES" == "" ]; then
	echo "You haven't modified any PHP files!"
else
	for f in $CHANGED_FILES; do
		php -l -d display_errors=0 "$f"
	done
	echo "Running phpcs"
	./vendor/bin/phpcs --ignore=$IGNORE --encoding=utf-8 -s -n -p $CHANGED_FILES
fi
