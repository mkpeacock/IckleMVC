class modrewrite{

  exec { 'enabledmodrewrite':
    command => '/usr/sbin/a2enmod rewrite',
    require => [ Exec['apt-get update'], Package['apache2'] ]
  }
  
  exec { "reload-apache2-again":
      command => "/etc/init.d/apache2 reload",
      require => [ Exec['apt-get update'], Exec['enabledmodrewrite'], Package[apache2] ] 
  }


}