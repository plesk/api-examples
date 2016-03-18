# Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

import httplib

class PleskApiClient:

    def __init__(self, host, port = 8443, protocol = 'https'):
        self.host = host
        self.port = port
        self.protocol = protocol
        self.secret_key = None

    def set_credentials(self, login, password):
        self.login = login
        self.password = password

    def set_secret_key(self, secret_key):
        self.secret_key = secret_key

    def request(self, request):
        headers = {}
        headers["Content-type"] = "text/xml"
        headers["HTTP_PRETTY_PRINT"] = "TRUE"

        if self.secret_key:
            headers["KEY"] = self.secret_key
        else:
            headers["HTTP_AUTH_LOGIN"] = self.login
            headers["HTTP_AUTH_PASSWD"] = self.password

        if 'https' == self.protocol:
            conn = httplib.HTTPSConnection(self.host, self.port)
        else:
            conn = httplib.HTTPConnection(self.host, self.port)

        conn.request("POST", "/enterprise/control/agent.php", request, headers)
        response = conn.getresponse()
        data = response.read()
        return data
