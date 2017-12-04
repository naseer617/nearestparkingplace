# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

# check and install required Vagrant plugins
required_plugins = ["vagrant-hostmanager", "vagrant-hostsupdater","vagrant-vbguest", "vagrant-cachier"]
required_plugins.each do |plugin|
  if Vagrant.has_plugin?(plugin) then
      system "echo OK: #{plugin} already installed"
  else
      system "echo Not installed required plugin: #{plugin} ..."
    system "vagrant plugin install #{plugin}"
  end
end

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "ubuntu/trusty64"

    # Forward ports to Apache and MySQL
    config.vm.network "forwarded_port", guest: 80, host: 8988
    config.vm.network "forwarded_port", guest: 3306, host: 8899

    config.vm.network "private_network", ip: "192.168.2.8"

    config.vm.host_name = 'parkman.local'
    config.vm.hostname = "parkman.local"
    config.hostsupdater.aliases = [
      "parkman.local"
    ]

    config.vm.provider "virtualbox" do |v|
      # Customize the amount of memory on the VM:
      v.memory = 1024*2
      v.cpus = 1
    end

    config.vm.synced_folder "./www", "/var/www", id: "vagrant-root",
    owner: "vagrant",
    group: "www-data",
    mount_options: ["dmode=775,fmode=664"]

    config.vm.provision "shell", path: "provision.sh"
end
