FROM ubuntu:20.04

USER root
WORKDIR /root/
COPY ./ ./hznuoj

# change apt-source to aliyun
RUN sed -i s@/archive.ubuntu.com/@/mirrors.aliyun.com/@g /etc/apt/sources.list \
	&& apt-get clean \
	&& cd /root/hznuoj/scripts \
	&& bash install.sh \
	&& cp /root/hznuoj/docker/docker_entry.sh /root/docker_entry.sh \
	&& rm -rf /root/hznuoj

EXPOSE 80
ENTRYPOINT ["/root/docker_entry.sh"]

CMD ["/bin/bash"]
