# HARP
Home Automated Raspberry Pi

CSI 4999 - Senior Capstone Project
Oakland University, 2018

Joe Danz, Alex Manaila, Michael Muller, Fady Putrus, Stephen Thai, Graham Walker

# Hardware Requirements
* Raspberry Pi - Any model with GPIO pins (A Raspberry Pi 2 Model B was used for this project)
* Wireless Arduino Modules - Arduino compatible with GPIO pins (ESP8266 manufactured by HiLetgo was used for this project)
* Temperature Sensor (For HVAC simulation)
* Low voltage LEDs/Fans, breadboards (For the sake of concept demonstration)
* A WLAN (Home wireless router is sufficent)

# Installation Instructions
* Install a LAMP stack on a Raspberry Pi - There are various guides available online
* Import .sql file to database
* Deploy website by moving files from "Website" to apache directory (default: /var/www/html)
* Install Python scripts on Raspberry Pi, configure to run at startup - Available in Hardware Code > Python
* Flash code to Arudino Modules - .ino files are available in Hardware > Arduino

# Getting Started
* Wireless controllers will host their own SSID; connect to them and navigate to the default gateway in a web browser
  - Enter home router SSID/password
* Create an account on the HARP website (hosted on Raspberry Pi)
* Add the controller to the website by giving it a name and specifying a MAC address
* Add appliances by selecting the controller and a GPIO pin
* Enjoy the additional features of the project
