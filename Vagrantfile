Vagrant.configure("2") do |config|

  config.omnibus.chef_version = :latest
  config.berkshelf.enabled = true
  config.vm.hostname = "deploy-agent.local"
  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true
  config.hostmanager.ignore_private_ip = false
  config.ssh.forward_agent = true
  config.berkshelf.berksfile_path = "Berksfile"

  config.vm.box = "trusty64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"

  config.vm.network :private_network, ip: "192.168.33.27"
  config.vm.provider :virtualbox do |vb, override|
    vb.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    vb.customize ["modifyvm", :id, "--memory", "1024"]
    vb.customize ["modifyvm", :id, "--cpus", "1"]
    
    override.vm.synced_folder ".", "/vagrant", :mount_options => ['dmode=775','fmode=775']
  end

  config.vm.provider "vmware_fusion" do |v|
    v.vmx["memsize"] = "1024"
  end
  
  config.vm.provider :vmware_workstation do |v, override|
    v.vmx["memsize"] = "1024"
  end

  config.vm.provision :chef_solo do |chef|

    chef.add_recipe "apt"
    chef.add_recipe "build-essential"
    chef.add_recipe "openssl"
    chef.add_recipe "conf"
    chef.add_recipe "zend-server"
    chef.add_recipe "zend-server::xdebug"
    chef.add_recipe "git"
    chef.add_recipe "postfix"
    chef.add_recipe "apache2"
    chef.add_recipe "sandbox"
    chef.add_recipe "zend-server::composer"
    chef.add_recipe "locale"

    chef.json = {
      :zend_server => {
        :php_version => "5.6",
        :version => "8.0"
      }
    }
  end

end
