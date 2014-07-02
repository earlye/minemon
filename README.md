minemon
=======

A simple setup for monitoring a BTC mine. minemon.php is designed to
run in a screen session; it runs an infinite loop, and polls ASICMiner
blades on each iteration. If a blade's GH/s falls below its configured
threshold, minemon.php will issue a reset to the blade in an attempt
to revive it. No email alerts or anything of the sort are sent.

Sample Cluster Configuration:
=============================

```json
{ "blades" : [
  { "id" : "1", "ip" : "192.168.1.11" , "port" : "8000" , "minSpeed" : 9500 },
  { "id" : "2", "ip" : "192.168.1.12" , "port" : "8000" , "minSpeed" : 9500 },
  { "id" : "3", "ip" : "192.168.1.13" , "port" : "8000" , "minSpeed" : 9500 },
  { "id" : "4", "ip" : "192.168.1.14" , "port" : "8000" , "minSpeed" : 9500 },
  { "id" : "5", "ip" : "192.168.1.15" , "port" : "8000" , "minSpeed" : 9500 },
  { "id" : "6", "ip" : "192.168.1.16" , "port" : "8000" , "minSpeed" : 9500 },
  { "id" : "7", "ip" : "192.168.1.17" , "port" : "8000" , "minSpeed" : 9500 },
  { "id" : "8", "ip" : "192.168.1.18" , "port" : "8000" , "minSpeed" : 9500 },
  { "id" : "9", "ip" : "192.168.1.19" , "port" : "8000" , "minSpeed" : 9500 },
  { "id" : "10", "ip" : "192.168.1.20" , "port" : "8000" , "minSpeed" : 9500 }
  ],

  "Mask" : "255.255.255.0",
  "Gateway" : "192.168.1.1",
  "PrimaryDNS" : "192.168.1.1",
  "SecondaryDNS" : "192.168.1.2",
  "Ports" : "8000,8000",
  "ServerAddresses" : "192.168.1.5,192.168.1.5",
  "UserPass" : "user:password,user:password"
}
```