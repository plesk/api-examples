var http = require('http');
var https = require('https');

function Client(host, port, protocol) {
  this._host = host;
  this._port = port || 8443;
  this._protocol = protocol || 'https';
}

Client.prototype.setCredentials = function(login, password) {
  this._login = login;
  this._password = password;
}

Client.prototype.setSecretKey = function(secretKey) {
  this._secretKey = secretKey;
}

Client.prototype.request = function(body, callback) {
  var headers = {
    'Content-type': 'text/xml',
    'HTTP_PRETTY_PRINT': 'TRUE'
  }

  if (this._secretKey) {
    headers['KEY'] = this._secretKey;
  } else {
    headers['HTTP_AUTH_LOGIN'] = this._login;
    headers['HTTP_AUTH_PASSWD'] = this._password;
  }

  var options = {
    host: this._host,
    port: this._port,
    path: '/enterprise/control/agent.php',
    method: 'POST',
    headers: headers
  }

  var handler = function(response) {
    var result = '';
    response.on('data', function (chunk) {
      result += chunk;
    });

    response.on('end', function () {
      callback(result);
    });
  }

  var client = 'https' == this._protocol ? https : http;
  var request = client.request(options, handler);
  request.write(body);
  request.end();
}

exports.Client = Client;
