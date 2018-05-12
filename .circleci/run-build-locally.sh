#!/usr/bin/env bash
curl --user ${CIRCLE_TOKEN}: \
    --request POST \
    --form revision=b65aa6c138aa30d436548d8f0159bd73d3cba6ff\
    --form config=@config.yml \
    --form notify=false \
      q  https://circleci.com/api/v1.1/project/github/street-art-nantes/streetartsite/tree/feature/ci