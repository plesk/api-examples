# Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

require 'net/http'
require 'net/https'

class PleskApiClient

  def initialize(host, port = 8443, protocol = 'https')
    @host = host
    @port = port
    @protocol = protocol
  end

  def set_credentials(login, password)
    @login = login
    @password = password
  end

  def set_secret_key(secret_key)
    @secret_key = secret_key
  end

  def request(body)
    http_request = Net::HTTP::Post.new('/enterprise/control/agent.php')
    http_request.body = body
    http_request.content_type = 'text/xml'
    http_request.add_field 'HTTP_PRETTY_PRINT', 'TRUE'

    if @secret_key
      http_request.add_field 'KEY', @secret_key
    else
      http_request.add_field 'HTTP_AUTH_LOGIN', @login
      http_request.add_field 'HTTP_AUTH_PASSWD', @password
    end

    http = Net::HTTP.new(@host, @port)
    if 'https' == @protocol
      http.use_ssl = true
      http.verify_mode = OpenSSL::SSL::VERIFY_NONE
    end

    response = http.start{ |http| http.request(http_request) }
  end

end
