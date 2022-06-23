#!/usr/bin/env bash

# Script that moves media icons from core to public files so that
# "File at public://something.png does not exist" error to be removed on setup.
# Be sure files directories are setup.
cd `dirname $0`/..
for SITE in `ls -d web/sites/*/`; do
  SITE=`basename $SITE`

  if [[ $SITE == 'all' ]] || [[ $SITE == 'default' ]]; then
    continue;
  fi

  if [ ! -w web/sites/$SITE/files/public ]; then
    echo "Public files cannot be added (no write permission)." 1>&2
    continue;
  fi
  echo "Copying public files..."
  mkdir -p web/sites/$SITE/files/public/media-icons/generic \
  # Public files: Prepare media icon files (originally from web/core/modules/media/images/icons/*).
  if [[ -d web/core/modules/media/images/icons ]]; then
    cp -r web/core/modules/media/images/icons/. web/sites/$SITE/files/public/media-icons/generic
  fi

  if [[ -d web/modules/contrib/media_entity_instagram/images/icons ]]; then
    cp -r web/modules/contrib/media_entity_instagram/images/icons/. web/sites/$SITE/files/public/media-icons/generic
  fi

  if [[ -d web/modules/contrib/media_entity_pinterest/images/icons ]]; then
    cp -r web/modules/contrib/media_entity_pinterest/images/icons/. web/sites/$SITE/files/public/media-icons/generic
  fi
  if [[ -d web/modules/contrib/media_entity_twitter/images/icons ]]; then
    cp -r web/modules/contrib/media_entity_twitter/images/icons/. web/sites/$SITE/files/public/media-icons/generic
  fi

done
