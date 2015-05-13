include_recipe "zend-server::server"

execute "Installing phing pear package" do
  command "/usr/local/zend/bin/pear channel-discover pear.phing.info && /usr/local/zend/bin/pear install phing/phing"
  action :run
  not_if { ::File.exists?("/usr/local/zend/bin/phing") }
end