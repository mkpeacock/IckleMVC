class composer {

	package{ "curl":
		ensure => present,
		require => Exec['apt-get update']
	}
	
	package{ "git-core":
		ensure => present,
		require => Exec['apt-get update']
	}
	
	exec{ "compose":
		command => '/bin/rm -rfv /vagrant/vendor/* && /bin/rm -f /vagrant/composer.lock && /usr/bin/curl -s http://getcomposer.org/installer | /usr/bin/php -- --install-dir=/vagrant && cd /vagrant && /usr/bin/php /vagrant/composer.phar install',
		require => [ Package['curl'], Package['git-core'] ]
	}
	
}