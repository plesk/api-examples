TARGET := example
SRCS := example.cpp plesk_api_client.cpp
OBJS := $(SRCS:.cpp=.o)

CCFLAGS = -Wall -Werror
LDFLAGS =
LIBS = -lcurl

.PHONY: all clean
all:: $(TARGET)

$(TARGET): $(OBJS)
	$(CXX) $(LDFLAGS) -o $@ $^ $(LIBS)

$(OBJS): %.o: %.cpp
	$(CXX) $(CCFLAGS) -o $@ -c $<

clean::
	$(RM) $(OBJS) $(TARGET)

