<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'my_project');

// Project repository
set('repository', 'http://git.rep.elt/edikskrim/billboard.git');

set('branch', 'master');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', ['storage']);

// Writable dirs by web server 
add('writable_dirs', ['storage']);
set('allow_anonymous_stats', false);

// Hosts

host('team3.php.elt')
    ->user('default')
    ->port(22)
    ->identityFile('~/.ssh/id_rsa')
    ->set('deploy_path', '/www/htdocs/team3');

task('deploy:configclear', function () {
    run('php {{release_path}}/artisan config:clear');
})->desc('Clearing config cache');
task('deploy:cacheclear', function () {
    run('php {{release_path}}/artisan cache:clear');
})->desc('Clearing app cache');

task('deploy:migration', function () {
    run('php {{release_path}}/artisan migrate --force');
})->desc('Artisan migrations');

task('deploy:seed', function () {
    run('php {{release_path}}/artisan db:seed');
})->desc('Artisan seed');

task('deploy:storage', function () {
    run('php {{release_path}}/artisan storage:link');
})->desc('Artisan storage');

task('deploy:link', function () {
    run('link {{release_path}}/../../shared/.env {{release_path}}/.env');
})->desc('create env link');

task('deploy:linkstorage', function () {
    run('ln -s {{release_path}}/../../shared/storage/app/public/images {{release_path}}/storage/app/public/images');
})->desc('create storage link');

task('deploy:composerinstall', function () {
    run('cd {{release_path}} && composer install');
})->desc('create env link');

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:composerinstall',
    'deploy:configclear',
    'deploy:cacheclear',
    'deploy:link',
    'deploy:storage',
    'deploy:linkstorage',
    'deploy:migration',
    //'deploy:seed',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success',
]);

task('status', function () {
    writeln("latest release tag: {{tag}}");
})->desc('Show status of custom deploy features');

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');


// Tasks

//task('build', function () {
//    run('cd {{release_path}} && build');
//});


// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

