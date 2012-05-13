class composer {

	package{ "curl":
		ensure => present,
		require => Exec['apt-get update']
	}
	
	package{ "git-core":
		ensure => present,
		require => Exec['apt-get update']
	}
	
	exec{ "compose"
		command => '/usr/bin/curl -s http://getcomposer.org/installer | /usr/bin/php -- --install-dir=/vagrant && cd /vagrant && /usr/bin/php /vagrant/composer.phar install',
		require => [ Package['curl'], Package['git-core'] ]
	}