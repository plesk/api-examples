// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

class Example {

    public static void main(String[] args) throws Exception {
        String login = System.getenv("REMOTE_LOGIN");
        String password = System.getenv("REMOTE_PASSWORD");
        String host = System.getenv("REMOTE_HOST");

        if (null == login) {
            login = "admin";
        }

        PleskApiClient client = new PleskApiClient(host);
        client.setCredentials(login, password);

        String request =
            "<packet>" +
                "<server>" +
                    "<get_protos/>" +
                "</server>" +
            "</packet>";

        String response = client.request(request);
        System.out.println(response);
    }

}
