FROM ubuntu:16.04

USER root
WORKDIR /root/
COPY ./ ./hznuoj

# change apt-source to aliyun
RUN sed -i s@/archive.ubuntu.com/@/mirrors.aliyun.com/@g /etc/apt/sources.list \
    && apt-get clean \
    && cd /root/hznuoj/scripts \
    && bash install.sh

EXPOSE 80
ENTRYPOINT ["/root/hznuoj/docker/docker_entry.sh"]

CMD ["/bin/bash"]
