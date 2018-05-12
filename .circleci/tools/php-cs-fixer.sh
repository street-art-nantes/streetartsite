#!/usr/bin/env bash
vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix --config=.php_cs.dist --dry-run
# $? catches the last exit code, which is the one we want to exit in the end of the day
return=$?
#we run it twice (first to get exit code, second to get the patch.diff file to share in artifact)
vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix --config=.php_cs.dist
git diff > var/patch.diff

if [ -s var/patch.diff ]
then
    cat var/patch.diff
else
    echo "Return code : $return but patch file is empty... Doing nothing"
    rm -f var/patch.diff
    return=0
fi

exit $return
