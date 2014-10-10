#!/bin/sh

. plesk_api_client.sh

request='
<packet>
  <server>
    <get_protos/>
  </server>
</packet>
'

plesk_api "$request"
