include_recipe "zend-server::server"

package "graphviz"

execute "Installing phpDocumentor pear package" do
  command "/usr/local/zend/bin/pear channel-discover pear.phpdoc.org && /usr/local/zend/bin/pear install phpdoc/phpDocumentor-alpha"
  action :run
  not_if { ::File.exists?("/usr/local/zend/bin/phpdoc") }
end