#!/usr/bin/env python3
# Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

import os
from plesk_api_client import PleskApiClient

host = os.getenv('REMOTE_HOST')
login = os.getenv('REMOTE_LOGIN', 'admin')
password = os.getenv('REMOTE_PASSWORD')

client = PleskApiClient(host)
client.set_credentials(login, password)

request = """
<packet>
  <server>
    <get_protos/>
  </server>
</packet>
"""

response = client.request(request)
print(response)
