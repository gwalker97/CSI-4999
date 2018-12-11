# HARP
Home Automated Raspberry Pi

CSI 4999 - Senior Capstone Project
Oakland University, 2018

Joe Danz, Alex Manaila, Michael Muller, Fady Putrus, Stephen Thai, Graham Walker

# Hardware Requirements
Raspberry Pi - Any model with GPIO pins (A Raspberry Pi 2 Model B was used for this project)
Wireless Arduino Modules - Arduino compatible with GPIO pins (ESP8266 manufactured by HiLetgo was used for this project)
Temperature Sensor (For HVAC simulation)
Low voltage LEDs/Fans, breadboards (For the sake of concept demonstration)
A WLAN (Home wireless router is sufficent)

# Installation Instructions
Install a LAMP stack on a Raspberry Pi - There are various guides available online
Import .sql file to database
Deploy website by moving files from "Website" to apache directory (default: /var/www/html)
Install Python scripts on Raspberry Pi, configure to run at startup - Available in Hardware Code > Python
Flash code to Arudino Modules - .ino files are availble in Hardware > Arduino

# Wiring
