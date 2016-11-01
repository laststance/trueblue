```bash
# install
$ git clone git@github.com:ryota-murakami/daily-tweet.git
$ composer install
$ npm install

# provisioning
$ ansible-playbook -i ansible/production ansible/site.yml

# deploy
$ fab deploy

# show gulp task list
$ node_modules/.bin/gulp -T

# run dev server
$ app/console server:run # you may kick web/app_dev.php

webpack devserver
$ webpack-dev-server --progress --colors --config webpack.config.js
```
