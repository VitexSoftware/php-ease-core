#!/bin/bash
# Compile message catalogs for development testing
set -e

# Ensure directories exist
mkdir -p i18n/cs_CZ/LC_MESSAGES/
mkdir -p i18n/en_US/LC_MESSAGES/
mkdir -p i18n/eo/LC_MESSAGES/

# Compile message catalogs
if command -v msgfmt >/dev/null 2>&1; then
  find i18n -type f -name 'php-vitexsoftware-ease-core.po' -print0 \
    | xargs -0 -r -I{} sh -c 'dest="$(dirname "{}")/php-vitexsoftware-ease-core.mo"; echo "Compiling $dest"; msgfmt -o "$dest" "{}" || true'
else
  echo "msgfmt command not found. Please install gettext package."
  exit 1
fi

echo "Message catalogs compiled successfully."