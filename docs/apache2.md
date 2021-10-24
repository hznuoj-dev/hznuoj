# apache2

If the concurrency is so high, need to modify the default configuration of apache2.

You can use the following command to view the working mode of apache2:

```bash
apachectl -V | grep -i mpm
```

if the mode is `prefork`, need to edit the configuration file `/etc/apache2/mods-available/mpm_prefork.conf`

such as:

```plain
# prefork MPM
# StartServers: number of server processes to start
# MinSpareServers: minimum number of server processes which are kept spare
# MaxSpareServers: maximum number of server processes which are kept spare
# MaxRequestWorkers: maximum number of server processes allowed to start
# MaxConnectionsPerChild: maximum number of requests a server process serves

<IfModule mpm_prefork_module>
    StartServers                 10
    MinSpareServers              10
    MaxSpareServers              20
    MaxRequestWorkers            2048
    MaxConnectionsPerChild       10000
</IfModule>

# vim: syntax=apache ts=4 sw=4 sts=4 sr noet
```

## Reference

- https://blog.mimvp.com/article/22790.html
