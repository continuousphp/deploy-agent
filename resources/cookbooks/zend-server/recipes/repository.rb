include_recipe "apt"

# add the Zend Server repo
apt_repository "zend-server" do
  uri "http://repos.zend.com/zend-server/#{node[:zend_server][:version]}/deb_ssl1.0"
  distribution "server"
  components ["non-free"]
  key "http://repos.zend.com/zend.key"
  action :add
end