```bash
# install
$ git clone git@github.com:ryota-murakami/daily-tweet.git
$ composer install
$ npm install

# run dev server
$ app/console server:run # you may kick web/app_dev.php

#webpack devserver
$ webpack-dev-server --progress --colors --config webpack.config.js

#database
$ mysql.server start

# provisioning
$ ansible-playbook -i ansible/production ansible/site.yml

# deploy
$ fab deploy
```
