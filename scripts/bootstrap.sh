/opt/mongodb/bin/mongod --dbpath /opt/nodework/data/db&
sleep 5
/usr/local/bin/node /opt/nodework/node/nodework.server.js 2>&1 >> /var/log/node.log&
java -jar /opt/nodework/nodeworkerjar.jar --config /opt/nodework/config.yaml 2>&1 >> /var/log/node.log

