<?php

namespace Deployer;
require 'recipe/common.php';

// Configuration
set('ssh_type', 'native');
set('ssh_multiplexing', true);

// Servers
server('staging', 'example.com')
    ->user('centos')
    ->stage('prod')
    ->identityFile()
    ->set('deploy_path', '/var/www/public_html/');

// Tasks
task('upload', function() {
  upload('output/release.tgz', get('deploy_path') . 'release.tgz');
});

task('extract',function(){
  cd(get('deploy_path'));
  run('tar -xzf release.tgz && rm -f release.tgz');
});

task('clear:cache',function(){
  cd(get('deploy_path'));
  run('rm -rf var/cache/*');
});

// If you are using migrations, for example
// task('migrate', function(){
//     cd(get('deploy_path') . '_tools/migration');
//     run('vendor/bin/phinx migrate');
// });

task ('deploy',[
  'upload',
  'extract',
//   'migrate',
  'clear:cache',
]);
