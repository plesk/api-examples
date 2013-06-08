#!/bin/sh

. plesk_api_client.sh

request='
<packet version="1.6.3.0">
  <server>
    <get_protos/>
  </server>
</packet>
'

plesk_api "$request"
