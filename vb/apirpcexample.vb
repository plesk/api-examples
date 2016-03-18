' Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

Imports System
Imports System.Net
Imports System.Text
Imports System.IO
Imports System.Xml
Imports System.Xml.Schema
Imports System.Security.Cryptography.X509Certificates
Imports System.Net.Security

Namespace ApiRpcExample
    Public Class Request
        Public Hostname As String = "localhost"
        Public Login As String = ""
        Public Password As String = ""
        
        Public ReadOnly Property AgentEntryPoint As String
            Get
                Return ("https://" & Me.Hostname & ":8443/enterprise/control/agent.php")
            End Get
        End Property

        Public Function Send(ByVal packet As XmlDocument) As XmlDocument
            Dim request As HttpWebRequest = Me.SendRequest(packet.OuterXml)
            Return Me.GetResponse(request)
        End Function

        Public Function Send(ByVal packet As Stream) As XmlDocument
            Using reader As TextReader = New StreamReader(packet)
                Return Me.Send(Me.Parse(reader))
            End Using
        End Function

        Public Function Send(ByVal packetUri As String) As XmlDocument
            Using reader As TextReader = New StreamReader(packetUri)
                Return Me.Send(Me.Parse(reader))
            End Using
        End Function

        Private Function SendRequest(ByVal message As String) As HttpWebRequest
            Dim request As HttpWebRequest = DirectCast(WebRequest.Create(Me.AgentEntryPoint), HttpWebRequest)
            request.Method = "POST"
            request.Headers.Add("HTTP_AUTH_LOGIN", Me.Login)
            request.Headers.Add("HTTP_AUTH_PASSWD", Me.Password)
            request.ContentType = "text/xml"
            request.ContentLength = message.Length
            Dim bytes As Byte() = New ASCIIEncoding().GetBytes(message)
            Using stream As Stream = request.GetRequestStream
                stream.Write(bytes, 0, message.Length)
            End Using
            Return request
        End Function

        Private Function Parse(ByVal xml As TextReader) As XmlDocument
            Dim document As New XmlDocument
            Using reader As XmlReader = XmlReader.Create(xml)
                document.Load(reader)
            End Using
            Return document
        End Function

        Private Function GetResponse(ByVal request As HttpWebRequest) As XmlDocument
            Using response As HttpWebResponse = DirectCast(request.GetResponse, HttpWebResponse)
                Using stream As Stream = response.GetResponseStream
                    Using reader As TextReader = New StreamReader(stream)
                        Return Me.Parse(reader)
                    End Using
                End Using
            End Using
        End Function
    End Class

    Friend Class Program

        Shared Sub Main(ByVal args As String())
            If (args.Length < 4) Then
                Console.WriteLine("Usage: apirpcexample <Hostname> <Login> <Password> <Protocol> <Request>")
                Console.WriteLine(" ")
                Console.WriteLine(" Host name - The Panel's host name")
                Console.WriteLine(" Login - Administrator's login")
                Console.WriteLine(" Password - Administrator's password")
                Console.WriteLine(" Request - Request file path (*.xml)")
            Else
                ' Verifies the remote Secure Sockets Layer (SSL) certificate 
                ' used for authentication.
                ServicePointManager.ServerCertificateValidationCallback = New RemoteCertificateValidationCallback(AddressOf Program.RemoteCertificateValidation)
                Dim request As New Request
                request.Hostname = args(0)
                request.Login = args(1)
                request.Password = args(2)
                Dim packetUri As String = args(3)
                Try
                    Program.PrintResult(request.Send(packetUri))
                Catch exception As Exception
                    Console.WriteLine("Request error: {0}", exception.Message)
                End Try
            End If
        End Sub

        ' The following method is invoked by the RemoteCertificateValidationDelegate.
        Private Shared Function RemoteCertificateValidation(ByVal sender As Object, ByVal certificate As X509Certificate, ByVal chain As X509Chain, ByVal sslPolicyErrors As SslPolicyErrors) As Boolean
            If (sslPolicyErrors <> sslPolicyErrors.RemoteCertificateNotAvailable) Then
                Return True
            End If
            Console.WriteLine("Certificate error: {0}", sslPolicyErrors)
            ' Do not allow this client to communicate with unauthenticated servers.
            Return False
        End Function

        Private Shared Sub PrintResult(ByVal document As XmlDocument)
            Dim w As New XmlTextWriter(Console.Out)
            w.Formatting = Formatting.Indented
            document.WriteTo(w)
            w.Flush()
            Console.WriteLine()
        End Sub

    End Class

End Namespace
