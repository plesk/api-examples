// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

import java.net.*;
import java.io.*;
import javax.net.ssl.*;
import java.security.cert.X509Certificate;

class PleskApiClient {

    private String host;
    private String login;
    private String password;
    private String secretKey;

    public PleskApiClient(String host) {
        this.host = host;
    }

    public void setCredentials(String login, String password) {
        this.login = login;
        this.password = password;
    }

    public void setSecretKey(String secretKey) {
        this.secretKey = secretKey;
    }

    public String request(String request) throws Exception {
        this.setTrustAllSslCertificates();

        URL url = new URL("https://" + this.host + ":8443/enterprise/control/agent.php");
        HttpURLConnection connection = (HttpURLConnection) url.openConnection();

        connection.setRequestMethod("POST");
        connection.setRequestProperty("Content-Type", "text/xml");
        connection.setRequestProperty("HTTP_PRETTY_PRINT", "TRUE");

        if (null != this.secretKey) {
            connection.setRequestProperty("KEY", this.secretKey);
        } else {
            connection.setRequestProperty("HTTP_AUTH_LOGIN", this.login);
            connection.setRequestProperty("HTTP_AUTH_PASSWD", this.password);
        }

        connection.setDoOutput(true);

        DataOutputStream writer = new DataOutputStream(connection.getOutputStream());
        writer.writeBytes(request);
        writer.flush();
        writer.close();

        BufferedReader reader = new BufferedReader(new InputStreamReader(connection.getInputStream()));

        String response = "";
        String inputLine;

        while ((inputLine = reader.readLine()) != null) {
            response += inputLine + "\n";
        }

        reader.close();

        return response;
    }

    private void setTrustAllSslCertificates() throws Exception {
        TrustManager[] trustAllCerts = new TrustManager[] {new X509TrustManager() {
                public java.security.cert.X509Certificate[] getAcceptedIssuers() {
                    return null;
                }
                public void checkClientTrusted(X509Certificate[] certs, String authType) {
                }
                public void checkServerTrusted(X509Certificate[] certs, String authType) {
                }
            }
        };

        SSLContext sc = SSLContext.getInstance("SSL");
        sc.init(null, trustAllCerts, new java.security.SecureRandom());
        HttpsURLConnection.setDefaultSSLSocketFactory(sc.getSocketFactory());

        HostnameVerifier allHostsValid = new HostnameVerifier() {
            public boolean verify(String hostname, SSLSession session) {
                return true;
            }
        };

        HttpsURLConnection.setDefaultHostnameVerifier(allHostsValid);
    }

}
