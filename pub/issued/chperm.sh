#!/bin/sh


if [[ -z $1 ]]; then
  echo "usage: chperm.sh hex"
  exit
fi


if [[ ! -z $1 ]]; then
  hex=$1
  chmod 644 $hex.pem
  openssl x509 -in $hex.pem -text > $hex.txt
  cp $hex.pem $hex.crt
fi


