#!/bin/bash

git checkout master
git checkout -- app/views/
git pull --no-edit origin master
grunt build
