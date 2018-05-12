#!/usr/bin/env bash
curl --user ${CIRCLE_TOKEN}: \
    --request POST \
    --form revision=610eef16da5a91eba80cc237c8872b6564dae5fa\
    --form config=@config.yml \
    --form notify=false \
        https://circleci.com/api/v1.1/project/github/street-art-nantes/streetartsite/tree/master