include_recipe "zend-server::repository"

package "zend-server-php-#{node[:zend_server][:php_version]}"

conf_plain_file '/etc/profile' do
  pattern  /\/usr\/local\/zend\/bin/
  new_line 'PATH=$PATH:/usr/local/zend/bin'
  action   :insert_if_no_match
end

conf_plain_file '/etc/profile' do
  pattern  /\/usr\/local\/zend\/lib/
  new_line 'LD_LIBRARY_PATH=$LD_LIBRARY_PATH:/usr/local/zend/lib'
  action   :insert_if_no_match
end

conf_plain_file '/usr/local/zend/etc/php.ini' do
  current_line ';date.timezone ='
  new_line 'date.timezone = "Europe/Luxembourg"'
  action   :replace
end

conf_plain_file '/usr/local/zend/etc/conf.d/optimizerplus.ini' do
  current_line 'zend_extension_manager.dir.optimizerplus="/usr/local/zend/lib/optimizerplus"'
  new_line 'zend_extension_manager.dir.optimizerplus='
  action   :replace
end

link "/usr/bin/php" do
  to "/usr/local/zend/bin/php"
  action :create
end

service "apache2" do
  action :restart
end