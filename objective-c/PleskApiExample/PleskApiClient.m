// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

#import "PleskApiClient.h"

@implementation NSURLRequest(DataController)

+ (BOOL)allowsAnyHTTPSCertificateForHost:(NSString *)host
{
    return YES;
}

@end

@implementation PleskApiClient

- (id)initWithHost:(NSString *)host
{
    self = [super init];
    remoteHost = host;
    return self;
}

- (void)setCredentials:(NSString *)login password:(NSString *)password
{
    remoteLogin = login;
    remotePassword = password;
}

- (NSString *)request:(NSString *)requestText
{
    NSString *url = [NSString stringWithFormat:@"%@://%@:%@/enterprise/control/agent.php", @"https", remoteHost, @"8443"];
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] init];
    [request setURL:[NSURL URLWithString:url]];
    [request setHTTPMethod:@"POST"];

    [request addValue:@"text/xml" forHTTPHeaderField: @"Content-Type"];
    [request addValue:@"TRUE" forHTTPHeaderField: @"HTTP_PRETTY_PRINT"];
    [request addValue:remoteLogin forHTTPHeaderField: @"HTTP_AUTH_LOGIN"];
    [request addValue:remotePassword forHTTPHeaderField: @"HTTP_AUTH_PASSWD"];

    NSMutableData *postBody = [NSMutableData data];
    [postBody appendData:[requestText dataUsingEncoding:NSUTF8StringEncoding]];
    [request setHTTPBody:postBody];

    NSHTTPURLResponse* urlResponse = nil;
    NSError *error = [[NSError alloc] init];
    NSData *responseData = [NSURLConnection sendSynchronousRequest:request returningResponse:&urlResponse error:&error];
    NSString *response = [[NSString alloc] initWithData:responseData encoding:NSUTF8StringEncoding];

    if ([urlResponse statusCode] < 200 || [urlResponse statusCode] >= 300) {
        NSLog(@"Response code: %ld", (long)[urlResponse statusCode]);
    }

    return response;
}

@end
