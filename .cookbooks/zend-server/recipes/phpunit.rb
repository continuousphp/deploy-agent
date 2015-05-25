include_recipe "zend-server::server"
include_recipe "zend-server::xdebug"

bash "Installing PHPUnit pear package" do
  user "root"
  code <<-EOH
  /usr/local/zend/bin/pear update-channels
  /usr/local/zend/bin/pear channel-discover pear.phpunit.de
  /usr/local/zend/bin/pear channel-discover pear.symfony.com
  /usr/local/zend/bin/pear install --alldeps phpunit/PHPUnit
  EOH
  not_if { ::File.exists?("/usr/local/zend/bin/phpunit") }
end