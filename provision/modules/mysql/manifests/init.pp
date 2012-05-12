class mysql {

  package { "mysql-server":
    ensure => present,
    require => [ Exec['apt-get update'], Package[php5-mysql] ]
  }
  
  package { "mysql-client":
    ensure => present,
    require => [ Exec['apt-get update'], Package[php5-mysql] ]
  }
  
  package { "libmysqlclient15-dev":
    ensure => present,
    require => [ Exec['apt-get update'], Package[php5-mysql] ]
  }
    
  exec { 'apache2ctl graceful':
    command => '/usr/sbin/apache2ctl graceful',
    require => [ Exec['apt-get update'], Package[php5-mysql] ] 
  }
  

}