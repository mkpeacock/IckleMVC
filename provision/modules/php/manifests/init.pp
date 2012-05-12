class php {

  package { "php5":
    ensure => present,
    require => [ Exec['apt-get update'], Package[apache2] ]
  }
  
  package { "php5-mysql":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-dev":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-curl":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-gd":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] }
  
  package { "php5-imagick":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ]
  }
  
  package { "php5-mcrypt":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-memcache":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-mhash":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-pspell":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-snmp":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-sqlite":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-xmlrpc":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-xsl":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php5-cli":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  package { "php-pear":
    ensure => present, 
    require => [ Exec['apt-get update'], Package[apache2] ] 
  }
  
  exec { "phpunit":
  	command => "/usr/bin/pear upgrade pear && /usr/bin/pear config-set auto_discover 1 && /usr/bin/pear install pear.phpunit.de/PHPUnit",
  	require => Package[php-pear]
  }
  
  exec { "reload-apache2":
      command => "/etc/init.d/apache2 reload",
      refreshonly => true,
      require => [ Exec['apt-get update'], Package[apache2], File['/etc/apache2/sites-available/default'] ]
  }
  
  

  

}