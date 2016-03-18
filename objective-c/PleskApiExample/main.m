// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

#import <Foundation/Foundation.h>
#import "PleskApiClient.h"

int main(int argc, const char * argv[])
{
    @autoreleasepool {
        NSString *login = [[[NSProcessInfo processInfo] environment] objectForKey:@"REMOTE_LOGIN"];
        NSString *password = [[[NSProcessInfo processInfo] environment] objectForKey:@"REMOTE_PASSWORD"];
        NSString *host = [[[NSProcessInfo processInfo] environment] objectForKey:@"REMOTE_HOST"];

        PleskApiClient *client = [[PleskApiClient alloc] initWithHost:host];
        [client setCredentials:login password:password];

        NSString *request = @""
            "<packet>"
                "<server>"
                    "<get_protos/>"
                "</server>"
            "</packet>";

        NSString *response = [client request:request];

        NSLog(@"Response: %@", response);
    }

    return 0;
}

