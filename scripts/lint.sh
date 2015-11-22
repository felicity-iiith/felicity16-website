#!/usr/bin/env bash

echo "Running jshint..."
jshint src/static/scripts gulpfile.js --reporter=node_modules/jshint-stylish
JSHINT_STATUS=$?

echo "Running scss-lint..."
scss-lint -c .scss-lint.yml # run on .scss files
SCSS_LINT_STATUS=$?
scss-lint -c .css-lint.yml # run on .css files
CSS_LINT_STATUS=$?

exit $((JSHINT_STATUS || SCSS_LINT_STATUS || CSS_LINT_STATUS))
