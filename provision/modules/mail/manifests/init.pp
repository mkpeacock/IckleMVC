class mail {

  package { "postfix":
    ensure => present,
    require => Exec['apt-get update']
  }

  package { "mailutils":
    ensure => present,
    require => Exec['apt-get update']
  }
  
  exec { 'autostartmail':
    command => '/usr/sbin/update-rc.d postfix defaults',
    require => Exec['apt-get update']
  }
 
  

}