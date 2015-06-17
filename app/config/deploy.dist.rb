# config valid only for current version of Capistrano
lock '3.4.0'

# - Generic deployment settings ----------------------------------------------
set :application,              'symfony2-template'
set :keep_releases,            3

# Default value for :format is :pretty, :dot is much shorter
set :format,                    :pretty

# Default value for :log_level is :debug
set :log_level,                 :info

# - Add Node.js/NPM packages binaries to environment -------------------------
set :default_env, {
  path: [
    "#{shared_path}/node_modules/.bin",
    "$PATH"].join(":")
}

# - Git settings -------------------------------------------------------------
set :repo_url,                 'https://github.com/danielsreichenbach/symfony2-template.git'
# ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }.call
set :branch,                   'master'

# - Shared and per release file settings -------------------------------------
set :linked_files,              fetch(:linked_files, []).push('app/config/parameters.yml')
set :linked_dirs,               fetch(:linked_dirs, []).push('app/logs', 'app/Resources/assets/vendor', 'node_modules', 'vendor')

# - Composer plugin settings -------------------------------------------------
set :composer_install_flags, '--no-dev --no-interaction --optimize-autoloader'
SSHKit.config.command_map[:composer] = "php #{shared_path.join("composer.phar")}"

# - File permissions plugin settings -----------------------------------------
set :file_permissions_paths,   fetch(:file_permissions_paths, []).push('app/cache', 'app/logs')
set :file_permissions_users,   fetch(:file_permissions_users, []).push('www-data')

set :permission_method,        :acl
set :use_set_permissions,      true

# - Capistrano flow modifications --------------------------------------------
namespace :deploy do

  before  :starting,           'composer:install_executable'

  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
      # Here we can do anything such as:
      # within release_path do
      #   execute :rake, 'cache:clear'
      # end
    end
  end

  task :node do
    on roles(:web), in: :groups, limit: 3, wait: 5 do
      within release_path do
        execute :bower, "install"
        execute :bower, "prune"
        execute :grunt
      end
    end
  end

  task :migrate do
    invoke 'symfony:console', 'doctrine:migrations:migrate', '--no-interaction', 'db'
  end

  task :opcache_clear do
    invoke 'symfony:console', 'app:cache:opcache:clear'
  end

  after   'npm:install',       'npm:prune'
  after   'npm:prune',         'node'
  before  'deploy:updated',    'deploy:set_permissions:acl'

  after   'deploy:updated',    'symfony:assetic:dump'
  after   'deploy:updated',    'symfony:assets:install'
  after   'deploy:updated',    'migrate'

  after   'deploy:finishing',  'deploy:cleanup'
  after   'deploy:finished',   'opcache_clear'

end
