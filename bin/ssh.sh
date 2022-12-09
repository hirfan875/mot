#!/bin/bash
CONATINER=`docker container ls | grep docker-php | awk '{print $1}'`
winpty docker exec -it $CONATINER //bin//bash
