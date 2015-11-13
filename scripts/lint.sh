#!/usr/bin/env bash

echo "Running jshint..."
jshint src/static/scripts gulpfile.js --reporter=node_modules/jshint-stylish
JSHINT_STATUS=$?

echo "Running scss-lint..."
scss-lint src/static/styles/
SCSS_LINT_STATUS=$?

exit $((JSHINT_STATUS || SCSS_LINT_STATUS))
