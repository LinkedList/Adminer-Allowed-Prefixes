Adminer Allowed Prefixes Plugin
=================================
Plugin for [Adminer](http://http://www.adminer.org/ "www.adminer.com") that shows only tables with user set prefixes in the left menu

When initializing the plugin use this format for a parameter:

```
array("server_name" => array("database_name" => array("prefix")))
```

e.g.
```
new AdminerAllowedPrefixes(
    		array(
				"sql.server.com" => array(
					"database1" => array("PREFIX1", "PREFIX2")
				),
				"sql2.server.com" => array(
					"database2" => array("PREFIX3", "PREFIX4")
				)
			)
		),
```