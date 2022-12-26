# To be used with Google Cloud Build. You cannot run this Dockerfile alone because /workspace doesn't exist.
FROM nathanaelytj/openlitespeed:latest

RUN rm -rf /var/www/vhosts/localhost && \
    mkdir -p /var/www/vhosts

#This COPY is important. The Run Command cannot access GCP Build dir or volumes
COPY . /var/www/vhosts/localhost

RUN rm -rf /var/www/vhosts/localhost/Dockerfile && \
    ln -sf /var/www/vhosts/localhost/public /var/www/vhosts/localhost/html && \
    chown nobody:nobody -R /var/www/vhosts/localhost

VOLUME ["/var/www/vhosts/localhost/storage"]

ENTRYPOINT ["/sbin/tini", "-g", "--"]
CMD ["/entrypoint.sh"]