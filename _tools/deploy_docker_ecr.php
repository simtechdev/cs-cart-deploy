<?php

namespace Deployer;
require 'recipe/common.php';

// Configuration
set('ssh_type', 'native');
set('ssh_multiplexing', true);

// User settings
set('default_stage', 'staging');

// Servers
host('staging.example.com')
    ->user('centos')
    ->multiplexing(true)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no')
    ->set('ecr','123456789.dkr.ecr.eu-west-1.amazonaws.com/app-example-staging')
    ->stage('staging');

host('example.com')
    ->user('centos')
    ->multiplexing(true)
    ->addSshOption('UserKnownHostsFile', '/dev/null')
    ->addSshOption('StrictHostKeyChecking', 'no')
    ->set('ecr','123456789.dkr.ecr.eu-west-1.amazonaws.com/app-example-prod')
    ->stage('production');

// Deploy tasks
task('deploy:ecr_login', function(){
  $docker_login = run ('aws ecr get-login --no-include-email');
  run($docker_login);
});

task('deploy:pull_new_image', function(){
 run('/usr/bin/docker pull ' . get('ecr'));
});

task('deploy:tag_current_image', function(){
  run('/usr/bin/docker tag ' . get('ecr') . ':latest ' . get('ecr') . ':prev');
});

task('deploy:stop_container', function(){
  $container_id = run('/usr/bin/docker ps -q');
  run('/usr/bin/docker stop ' . $container_id);
});

task('deploy:start_container', function(){
  run('/usr/bin/docker run -d --rm -v /srv/project/images/:/app/images/ -v /srv/project/var/files/:/app/var/files/ -v /srv/project/var/backups/:/app/var/backups/ -v /srv/project/var/order_confirmations/:/app/var/order_confirmations/ -p 127.0.0.1:81:80 ' . get('ecr') . ':latest');
});

task('deploy:cleanup', function(){
  run('/usr/bin/docker images --filter "dangling=true" -q --no-trunc | xargs -r /usr/bin/docker rmi');
});

task('deploy:done', function () {
    write('Deploy done!');
});

// Main
task ('deploy',[
  'deploy:ecr_login',
  'deploy:tag_current_image',
  'deploy:pull_new_image',
  'deploy:stop_container',
  'deploy:start_container',
  'deploy:cleanup',
  'deploy:done'
]);
