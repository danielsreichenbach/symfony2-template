# Configure required variables for Symfony2 deployment
set :deploy_config_path, 'app/config/deploy.rb'
set :stage_config_path,  'app/config/deployment'

# Load DSL and set up stages
require 'capistrano/setup'

# Include default deployment tasks
require 'capistrano/deploy'

# Include tasks from other gems included in your Gemfile
require 'capistrano/file-permissions'

require 'capistrano/bundler'
require 'capistrano/npm'

require 'capistrano/composer'
require 'capistrano/symfony'
