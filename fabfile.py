# coding: utf-8

from fabric.api import cd, env, local, run, task, sudo
from fabric.contrib.project import rsync_project

env.hosts = ('dailytweet.net')
env.user = ('root')
env.use_ssh_config = True

env.remote_project_dir = ('/home/dailytweet/daily-tweet')

@task
def deploy():
    _asset_compile()
    _rsync()
    _remote_refresh()

def _asset_compile():
    local('gulp build')

def _rsync():
    rsync_project(
        local_dir='.',
        remote_dir=env.remote_project_dir,
        exclude=(
            '.bundle',
            '.idea',
            '.sass-cache',
            '.git',
            'ansible',
            'node_modules',
            'roles',
            'daily-tweet-example.jpg',
            'fabfile.py',
            'gulpfile.js',
            'package.json',
            '.DS_Store',
            '.editorconfig',
            'app/logs',
            'app/cache/dev',
            'app/cache/pro_',
            'app/cache/test'
        ),
        delete=True
    )

def _remote_refresh():
    with cd(env.remote_project_dir):
        run('app/console doctrine:database:create --if-not-exists')
        run('app/console doctrine:schema:update --force')
        run('app/console cache:clear --env=prod --no-warmup --no-optional-warmers')
        sudo('service php5-fpm restart')
