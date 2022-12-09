#!/bin/bash
mkdir siege  && cd siege
wget http://download.joedog.org/siege/siege-3.1.4.tar.gz
tar -zxvf siege-3.1.4.tar.gz
cd siege-3.1.4
./configure --prefix=/home/www/bin
make && make install
cd ../../ && rm -rf siege