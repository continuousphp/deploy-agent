# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = '2'

@script = <<SCRIPT
DOCUMENT_ROOT_ZEND="/var/www/deploy-agent/public"
apt-get update
apt-get install -y apache2 git curl php5-cli php5 php5-intl php5-sqlite sqlite3 php5-mcrypt
echo "
<VirtualHost *>
    DocumentRoot $DOCUMENT_ROOT_ZEND
    <Directory $DOCUMENT_ROOT_ZEND>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
" > /etc/apache2/sites-available/deploy-agent.conf
a2enmod rewrite
a2dissite 000-default
a2ensite deploy-agent
php5enmod mcrypt
adduser www-data vagrant
adduser vagrant www-data
service apache2 restart
cd /var/www/deploy-agent
curl -Ss https://getcomposer.org/installer | php
echo "** [CONTINUOUSPHP] Visit http://192.168.33.99 in your browser for to view the application **"
SCRIPT


Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = 'ubuntu/trusty64'
  config.vm.network :private_network, ip: "192.168.33.99"
  config.vm.hostname = "deploy-agent.local"
  config.vm.provision 'shell', inline: @script

  config.vm.provider "virtualbox" do |vb, override|
    vb.customize ["modifyvm", :id, "--cpus", "1"]
    vb.customize ["modifyvm", :id, "--memory", "1024", "--natdnshostresolver1", "on"]
    override.vm.synced_folder ".", "/var/www/deploy-agent", :mount_options => ['dmode=775','fmode=775']
  end
  
  config.ssh.forward_agent = true

end
