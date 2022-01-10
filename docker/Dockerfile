FROM ubuntu:20.04

USER root
WORKDIR /root/
COPY ./ ./hznuoj

ENV DEBIAN_FRONTEND=noninteractive \
    CONTAINER_TIMEZONE=Asia/Shanghai

# change apt-source to aliyun
RUN sed -i s@/archive.ubuntu.com/@/mirrors.aliyun.com/@g /etc/apt/sources.list \
    && apt-get clean \
    && cd /root/hznuoj/scripts \
    && bash install.sh \
    && cp /root/hznuoj/docker/docker_entry.sh /root/docker_entry.sh \
    && cp -a /root/hznuoj/docker/scripts /scripts \
    && bash /root/hznuoj/docker/configure.sh \
    && rm -rf /root/hznuoj

# Expose HTTP port
EXPOSE 80

# Healthchecking script
HEALTHCHECK --interval=10s --timeout=10s --start-period=30s --retries=3 CMD [ "/scripts/bin/healthcheck" ]

ENTRYPOINT ["/root/docker_entry.sh"]
CMD [""]
