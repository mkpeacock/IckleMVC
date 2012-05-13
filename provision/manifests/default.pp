stage { 'first': before => Stage[main] }
stage { 'ppa': before => Stage['first'] }
stage {'veryfirst': before => Stage['ppa'] }
stage { 'last': require => Stage[main] }
group { 'puppet': ensure => 'present' }
class {'apache': stage => first}
class {'modrewrite': stage => last}
class {'dns': stage => veryfirst }
class {'misc': stage => ppa }

class sudo{

	group { "wheel":
	    ensure => "present",
	}
	
	exec { "/bin/echo \"%wheel  ALL=(ALL) ALL\" >> /etc/sudoers":
	    require => Group["wheel"]
	}
	
	user { "developer":
		ensure => "present",
		gid => "wheel",
		shell => "/bin/bash",
		home => "/home/developer",
		managehome => true,
		password => "passwordtest",
		require => Group["wheel"]
	}

}
import "dns"
include dns
import "apache"
include apache
import "modrewrite"
include modrewrite
import "php"
include php
import "mysql"
include mysql
import "mail"
include mail
include sudo