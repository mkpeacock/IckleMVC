class dns {

  exec { 'updatedns':
    command => "/bin/sed -i 's/#prepend domain-name-servers 127.0.0.1;/prepend domain-name-servers 8.8.8.8, 8.8.4.4;/g' /etc/dhcp3/dhclient.conf"
  }
  
  exec { 'preparenetworking':
    command => '/etc/init.d/networking restart',
    require => Exec['updatedns']
  }
 
  

}