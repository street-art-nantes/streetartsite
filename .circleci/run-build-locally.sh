#!/usr/bin/env bash
curl --user ${CIRCLE_TOKEN}: \
    --request POST \
    --form revision=d40c1b7da8ef86e4a7befd0b95df4d39b85cb7a6\
    --form config=@config.yml \
    --form notify=false \
        https://circleci.com/api/v1.1/project/github/street-art-nantes/streetartsite/tree/feature/ci