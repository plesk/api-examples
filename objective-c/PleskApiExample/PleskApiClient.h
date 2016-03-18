// Copyright 1999-2016. Parallels IP Holdings GmbH. All Rights Reserved.

#import <Foundation/Foundation.h>

@interface PleskApiClient : NSObject
{
    NSString *remoteLogin;
    NSString *remotePassword;
    NSString *remoteHost;
}

- (id)initWithHost:(NSString *)host;
- (void)setCredentials:(NSString *)login password:(NSString *)password;
- (NSString *)request:(NSString *)requestText;

@end
