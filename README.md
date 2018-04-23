Street Art
================

Development
---
Start the server

```shell
docker-compose up
```

Add `-d`options to demonize command

Now you can go to `http://localhost/api`

Utils
--- 

Re-sync database
```
docker-compose exec -T php bin/console doctrine:schema:update 
```

Extract translation messages
``` bash
docker-compose exec -T php bin/console translation:extract
```

Delete obsolete translation messages
``` bash
translation:delete-obsolete
```

Show emails

Go to `http://localhost:1080`
