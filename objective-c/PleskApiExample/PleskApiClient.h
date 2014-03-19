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
