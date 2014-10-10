#!/usr/bin/env python

import os
from plesk_api_client import PleskApiClient

host = os.environ['REMOTE_HOST']
login = os.environ['REMOTE_LOGIN']
password = os.environ['REMOTE_PASSWORD']

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
print response
