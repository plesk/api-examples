#!/usr/bin/env node

var pleskApi = require('./plesk_api_client.js');

var host = process.env.REMOTE_HOST;
var login = process.env.REMOTE_LOGIN;
var password = process.env.REMOTE_PASSWORD;

var client = new pleskApi.Client(host);
client.setCredentials(login, password);

var request = 
  '<packet version="1.6.3.0">' +
    '<server>' +
      '<get_protos/>' +
    '</server>' +
  '</packet>';

client.request(request, function(response) {
  console.log(response);
});
