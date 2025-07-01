FROM node:22.16.0-slim

RUN apt-get update && apt-get install -y git openssh-client --no-install-recommends

RUN mkdir -p /root/.ssh && \
    chmod 0700 /root/.ssh && \
    ssh-keyscan github.com >> /root/.ssh/known_hosts

RUN git config --global --add safe.directory /var/www

WORKDIR /var/www

COPY listener.js .

CMD ["node", "listener.js"]