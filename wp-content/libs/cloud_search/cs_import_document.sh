export JAVA_HOME=/opt/jre1.8.0_25
export CS_HOME=/srv/www/cg.curriki.org/public_html/curriki/wp-content/libs/cloud_search/csconsole
. $CS_HOME/bin/cs-import-documents -c $CS_HOME/account-credentials --source $1 --output $2
