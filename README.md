![alt text](http://albismart.com/client-api.png "Logo")

[![Client API - Intro](http://albismart.com/clientapi-preview.gif)](https://www.youtube.com/watch?v=RVfij_-W1y0)


AlbiSmart client-api is an opensource project, MIT-licensed. It is maintained by the core developers of AlbiSmart company. Open to any contributors willing to give their very own to the Internet Provisioning community.

## Introduction
The client-api as we call it, is a middleware between the local servers of the ISP companies subscribed to AlbiSmart services. However the API can be used for your internal needs, experiments or integration to systems of your choice.
It is designed to provide your network infrastructure a modern Rest API with [JSON](http://www.json.org/) results.

### Prerequisites

In order to get the API running and ready, setup a linux distro with the below servicecs

```
1. PHP 5.4> running server with 
2. PHP Extensions: json, snmp, mysql, mysql-pdo
3. MySQL if you are planing to use https://freeradius.org
4. DHCP server in case you have one you can install our API inside that actual running server.
5. ([OMAPI](https://en.wikipedia.org/wiki/OMAPI)) and OMSHELL in order to manage the DHCP Hosts on the fly.
```

### Installing

You may download our api from [project page](albismart.com) or get the zip from github.

After doing get the files inside your `/var/www/html/` dir or your public apache/nginx based on your server configuration.


### Our initial todo list (we may extend or eleminate items, depending on the project flow)

- [x] Server info
- [ ] DHCPv2/3 Fixes 
- [ ] DHCP Provisioning Management API
- [ ] OMSHELL API
- [x] PPPoE Provisioning Management API
- [x] CMTS - SNMP Read, Write API
- [x] CableModem - SNMP Read, Write API
- [ ] OLT - SNMP Read, Write API
- [ ] ONU/ONT - SNMP Read, Write API
- [ ] Telnet connection API
- [ ] Cronjob tasks for fixes, updates and collection purposes
- [x] GUI installation index.php, create config.php
- [ ] Build an extensive wiki for the repo

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/albismart/client-api/tags). 

## Authors

* **Mentor Gashi** - *Maintainer/Developer* - [mentorgashi](https://github.com/mentorgashi)
* **Arbnor Gashi** - *Bringing in network standards* - [arbnorgashi](https://github.com/arbnorgashi)

See also the list of [contributors](https://github.com/albismart/client-api/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
