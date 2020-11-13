#!/usr/bin/env bash

#setup Xvfb - X virtual framebuffer is a display server implementing the X11 display server protocol
#Xvfb :1 -screen 0 1280x768x24 &
#export DISPLAY=:1

#nohup java -Dwebdriver.gecko.driver="geckodriver" -jar selenium-server-standalone-3.4.0.jar -log /tmp/selenium-firefox.log &
#DISPLAY=:1 xvfb-run  java -Dwebdriver.gecko.driver="geckodriver" -jar selenium-server-standalone-3.4.0.jar
java -Dwebdriver.gecko.driver="geckodriver" -jar selenium-server-standalone-3.4.0.jar