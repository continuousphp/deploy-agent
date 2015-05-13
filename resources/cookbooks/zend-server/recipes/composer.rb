include_recipe "zend-server::server"

package "curl"

execute "Installing composer" do
  command "curl -s https://getcomposer.org/installer | /usr/local/zend/bin/php && mv composer.phar /usr/local/bin/composer"
  action :run
  not_if { ::File.exists?("/usr/local/bin/composer") }
end
