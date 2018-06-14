#!/bin/bash

kill -9 $(lsof -t -i:$1)
cd ../../../
php yii yii2multiresponse/server/start $1
