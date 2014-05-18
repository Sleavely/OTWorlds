#!/bin/bash

git checkout master
git checkout -- .
git pull --no-edit origin master
grunt build
