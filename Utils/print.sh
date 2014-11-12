#!/bin/bash

for groupline in "$(cat $1)"
do
	echo "$groupline";
done
