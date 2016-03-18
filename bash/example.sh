#!/bin/sh
# Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

. ./plesk_api_client.sh

request='
<packet>
  <server>
    <get_protos/>
  </server>
</packet>
'

plesk_api "$request"
