# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    # /*=====================================
    # =            FREE VERSION!            =
    # =====================================*/
    # This is the free (still awesome) version of Scotch Box.
    # Please go Pro to support the project and get more features.
    # Check out https://box.scotch.io to learn more. Thanks

    $APP_DIR='/var/www'

    config.vm.box = "bento/ubuntu-16.04"
    config.vm.network "private_network", ip: "192.168.33.99"
    config.vm.hostname = "commission-calculator"

    config.vm.synced_folder ".", $APP_DIR, :nfs => { :mount_options => ["dmode=777", "fmode=666"] }

    config.vm.provision "shell", path: "scripts/provision.sh", run: "once", args: [$APP_DIR]

end