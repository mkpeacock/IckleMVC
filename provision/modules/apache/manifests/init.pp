class apache {
  exec { 'apt-get update':
    command => '/usr/bin/apt-get update',
    require => Exec['preparenetworking']
  }

  package { "apache2":
    ensure => present,
    require => Exec['apt-get update']
  }
  
  file { '/var/www/src':
	   ensure => 'link',
	   target => '/vagrant/src',
	   require => Package['apache2']
	}

  service { "apache2":
    ensure => running,
    require => Package["apache2"]
  }
}