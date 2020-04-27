#!/bin/bash

max=60
for i in `seq 0 $max`
do
    php upsert_standards_statements.php $i &
done