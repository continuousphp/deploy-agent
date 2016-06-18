# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = '2'

@script = <<SCRIPT
DOCUMENT_ROOT_ZEND="/vagrant/public"
apt-get update
apt-get install -y apache2 git curl php5-cli php5 php5-intl php5-sqlite sqlite3 php5-mcrypt
echo "
<VirtualHost *:80>
    ServerName skeleton-zf.local
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
service apache2 restart
cd /vagrant
curl -Ss https://getcomposer.org/installer | php
echo "** [CONTINUOUSPHP] Visit http://localhost:8085 in your browser for to view the application **"
SCRIPT


Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = 'ubuntu/trusty64'
  config.vm.network "forwarded_port", guest: 80, host: 8085
  config.vm.hostname = "deploy-agent.local"
  config.vm.provision 'shell', inline: @script

  config.vm.provider "virtualbox" do |vb, override|
    vb.customize ["modifyvm", :id, "--memory", "1024"]
    vb.customize ["modifyvm", :id, "--cpus", "1"]
    override.vm.synced_folder ".", "/vagrant", :mount_options => ['dmode=775','fmode=775']
  end
  
  config.ssh.forward_agent = true

end
