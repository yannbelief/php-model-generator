#!/bin/bash


mkdir /bin/php-model-gen
cp *.php /bin/php-model-gen/
cp *.sh /bin/php-model-gen/
ln -s /bin/php-model-gen/mdlgen.sh /bin/mdlgen
ln -s /bin/php-model-gen/mdlgen-uninstall.sh /bin/mdlgen-uninstall
echo "done if no error displayed"
