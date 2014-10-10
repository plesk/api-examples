#!/usr/bin/env ruby

require './plesk_api_client'

host = ENV['REMOTE_HOST']
login = ENV['REMOTE_LOGIN']
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

