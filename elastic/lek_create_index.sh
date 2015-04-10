#!/bin/bash

# 2015.04.10

Exec_date=$(date)
Mail_addr='hwshang@yeah.net'
C_Index_name="nginx-access-$(date +%Y.%m.%d --date '-1 days ago')"
D_Index_name="nginx-access-$(date +%Y.%m.%d --date '90 days ago')"
ES_Url="http://localhost:9200"
Index_json_file="/etc/elasticsearch/nginx-access.json"

Check_out() {
  if [ $1 = 'true}' ]
  then
    echo " ${Exec_date} - Finish: $2 $3  ^.^ "
    return 0
  else
    echo " ${Exec_date} - False: $2 $3 -.-! "
    cat <<EOF | mutt -s "Elasticsearch" "${Mail_addr}" 
    ${Exec_date} - False: $2 $3 ! 
EOF
    return 1
  fi
}

Verify_index() {
  Staus_code=$(curl -I ${ES_Url}/$2 2>/dev/null |awk '/1/ {print $2}') 
  if [ ${Staus_code} = '404' ] && [ "$1" = "Create" ]
  then
    return 0
  elif [ ${Staus_code} = '200' ] && [ "$1" = "Delete" ]
  then
    return 0
  else
    echo " $1 Warning: ${ES_Url}/$2 is ${Staus_code} "
    return 1
  fi 
}

Create_index() {
  Verify_index Create $1 || return 1
  Out=$(curl -XPUT ${ES_Url}/$1 -d @${Index_json_file} 2>/dev/null | awk -F':' '{print $2}')
  Check_out ${Out} Create $1 
}

Delete_index() {
  Verify_index Delete $1 || return 1
  Out=$(curl -XDELETE ${ES_Url}/$1 2>/dev/null | awk -F':' '{print $2}')
  Check_out ${Out} Delete $1
}

Create_index ${C_Index_name}  
Delete_index ${D_Index_name}

