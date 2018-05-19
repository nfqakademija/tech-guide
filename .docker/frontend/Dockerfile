FROM node:9.10.0

MAINTAINER Aurelijus Banelis <aurelijus@banelis.lt>

# Install dependencies
RUN apt-get update \
 && apt-get install -y git \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /code
VOLUME /code

CMD '/bin/bash'