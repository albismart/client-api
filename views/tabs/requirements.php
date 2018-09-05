<div class="column col-6">
	<div class="card">
		<div class="card-body">
			<ul>
				<li> 
					<font :class="textClass(reqs.php.version)">PHP 5.4> </font> <font v-html="badge(reqs.php.version)"></font>
					<ul>
					  <li :class="textClass(reqs.php.json)"> JSON Extension <font v-html="badge(reqs.php.json)"></font></li>
					  <li :class="textClass(reqs.php.snmp)"> SNMP Extension <font v-html="badge(reqs.php.snmp)"></font></li>
					  <li :class="textClass(reqs.php.mysql)"> MySQL Extension <font v-html="badge(reqs.php.mysql)"></font></li>
					  <li :class="textClass(reqs.php.mysqlPdo)"> MySQL PDO Extension  <font v-html="badge(reqs.php.mysqlPdo)"></font></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="column col-6">
	<div class="card">
		<div class="card-body">
			<ul>
				<li><a href="https://freeradius.org" target="_blank">FreeRadius</a></li>
				<li><a href="https://www.isc.org/downloads/dhcp/" target="_blank">DHCP Service</a></li>
				<li><a href="https://linux.die.net/man/1/omshell" target="_blank"> OMSHELL </a></li>
			</ul>
		</div>
	</div>
</div>