template "/etc/apache2/sites-available/vagrant" do
  source "vhost.erb"
end

file "/etc/apache2/sites-enabled/000-default" do
  action :delete
end

link "/etc/apache2/sites-enabled/000-vagrant" do
  to "/etc/apache2/sites-available/vagrant"
  action :create
end

execute "Adding apache headers module" do
  command "a2enmod headers"
end

execute "Adding www-data user to vagrant group" do
  command "usermod -a -G vagrant www-data"
end

execute "Adding vagrant user to www-data group" do
  command "usermod -a -G www-data vagrant"
end

service "apache2" do
  action :restart
end
