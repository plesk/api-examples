#!/usr/bin/env ruby
# Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

require './plesk_api_client'

host = ENV['REMOTE_HOST']
login = ENV['REMOTE_LOGIN'] || 'admin'
password = ENV['REMOTE_PASSWORD']

client = PleskApiClient.new(host)
client.set_credentials(login, password)

request = <<eof
<packet>
  <server>
    <get_protos/>
  </server>
</packet>
eof

response = client.request(request)
puts response.body

