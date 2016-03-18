// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

using System;
using System.Net;
using System.Text;
using System.IO;
using System.Xml;
using System.Xml.Schema;
using System.Security.Cryptography.X509Certificates;
using System.Net.Security;

namespace ApiRpcExample
{
    public class Request
    {
        public string Hostname = "localhost";
        public string Login = "admin";
        public string Password = "";

        public Request()
        {
        }

        public string AgentEntryPoint { get { return "https://" + Hostname + ":8443/enterprise/control/agent.php"; } }

        public XmlDocument Send(XmlDocument packet)
        {
            HttpWebRequest request = SendRequest(packet.OuterXml);
            XmlDocument result = GetResponse(request);
            return result;
        }

        public XmlDocument Send(Stream packet)
        {
            using (TextReader reader = new StreamReader(packet))
            {
                return Send(Parse(reader));
            }
        }

        public XmlDocument Send(string packetUri)
        {
            using (TextReader reader = new StreamReader(packetUri))
            {
                return Send(Parse(reader));
            }
        }

        private HttpWebRequest SendRequest(string message)
        {
            HttpWebRequest request = (HttpWebRequest)WebRequest.Create(AgentEntryPoint);

            request.Method = "POST";
            request.Headers.Add("HTTP_AUTH_LOGIN", Login);
            request.Headers.Add("HTTP_AUTH_PASSWD", Password);
            request.ContentType = "text/xml";
            request.ContentLength = message.Length;

            ASCIIEncoding encoding = new ASCIIEncoding();
            byte[] buffer = encoding.GetBytes(message);

            using (Stream stream = request.GetRequestStream())
            {
                stream.Write(buffer, 0, message.Length);
            }

            return request;
        }

        private XmlDocument Parse(TextReader xml)
        {
            XmlDocument document = new XmlDocument();

            using (XmlReader reader = XmlTextReader.Create(xml))
            {
                document.Load(reader);
            }

            return document;
        }

        private XmlDocument GetResponse(HttpWebRequest request)
        {
            using (HttpWebResponse response = (HttpWebResponse)request.GetResponse())
            using (Stream stream = response.GetResponseStream())
            using (TextReader reader = new StreamReader(stream))
            {
                return Parse(reader);
            }
        }
    }

    class Program
    {
        static void Main(string[] args)
        {
            if (args.Length < 4)
            {
                Console.WriteLine("Usage: PanelApiRpcClient <Hostname> <Login> <Password> <Request>");
                Console.WriteLine("  ");
                Console.WriteLine("  Hostname  - Panel host name");
                Console.WriteLine("  Login     - Administrator's login");
                Console.WriteLine("  Password  - Administrator's password");
                Console.WriteLine("  Request   - Request file path (*.xml)");
                return;
            }

            ServicePointManager.ServerCertificateValidationCallback =
                          new RemoteCertificateValidationCallback(RemoteCertificateValidation);
            Request request = new Request();
            request.Hostname = args[0];
            request.Login = args[1]; 
            request.Password = args[2]; 
            string packet = args[3];
            try
            {
                XmlDocument result = request.Send(packet);
                PrintResult(result);
            }
            catch (Exception e)
            {
                Console.WriteLine("Request error: {0}", e.Message);
            }
        }

        private static bool RemoteCertificateValidation(object sender,
              X509Certificate certificate, X509Chain chain, SslPolicyErrors sslPolicyErrors)
        {
            if (sslPolicyErrors != SslPolicyErrors.RemoteCertificateNotAvailable)
                return true;

            Console.WriteLine("Certificate error: {0}", sslPolicyErrors);

            // Do not allow this client to communicate with unauthenticated servers.
            return false;
        }

        private static void XmlSchemaValidation(object sender, ValidationEventArgs e)
        {
            Console.WriteLine("Validation error: {0}", e.Message);
        }

        static void PrintResult(XmlDocument document)
        {
            XmlTextWriter writer = new XmlTextWriter(Console.Out);
            writer.Formatting = Formatting.Indented;

            document.WriteTo(writer);

            writer.Flush();
            Console.WriteLine();
        }
    }
}
