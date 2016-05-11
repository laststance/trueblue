daily-tweet [![Build Status](https://travis-ci.org/ryota-murakami/daily-tweet.svg?branch=clean-OAuthLoginBunde-name)](https://travis-ci.org/ryota-murakami/daily-tweet)
========================
#### Work In Progress

![example](https://raw.githubusercontent.com/ryota-murakami/ImageBox/master/daily-tweet/daily-tweet-example.jpg)

#### Document

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
```
