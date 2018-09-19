#This python code will run the server on the Raspberry pi and handle the incoming messages from the client. We will be using GPIO pins to control the flow of power in the system. 

import threading, subprocess, pickle
import mysql.connector
from mysql.connector import MySQLConnection, Error

#import RPi.GPIO as GPIO
import time

hostname = '127.0.0.1'
username = 'root'
password = 'root'
dbname = 'python'


#this object will store each appliance? 
class appliance(object):
	id = ""
	name = ""
	pin = 0
	state = 0

	def __init__(self, id, name, pin, state):
		self.id = id
		self.name = name
		self.pin = pin
		self.state = state

def doQuery( conn ):
	cur = conn.cursor()
	conn.commit()
	cur.execute( "SELECT id, name, gpioPin, pinState FROM test" )

	for id, name, gpioPin, pinState  in cur.fetchall() :
		#print "Id: %s; Name: %s; PIN #%s ; State: %s" %(id, name, gpioPin, pinState)
		parseAppliance(id, name, gpioPin, pinState)
		

#this method will pull from the database every half second and update the GPIO pins
def dbThread( conn ):
	endconn = "False"
	while 1:
		time.sleep(.05)
		print "Time Update..."
		doQuery( conn )

def parseAppliance(id, name, pin, state):
	if state == 0:
		print "Appliance %s using PIN %s is 'Off'." %(name, pin)
	else:
		print "Appliance %s using PIN %s is 'On'." %(name, pin)
		

def MySQLConnect():
	print "Using mysql.connector...."
	myConnection = mysql.connector.connect(host=hostname, user=username, passwd=password, db=dbname)	
	doQuery( myConnection )
	t = threading.Thread(target=dbThread, args=(myConnection,))
	t.start()
	#myConnection.close()	

#Sends messages to the GPIO Pins
def GPIO(pinNum, On_Off):
	state = ""
	not_state = ""
	#This statement handles the state which the LED is on
	#Right now the inverse is used to turn the light off.
	if On_Off == "high":
		state = GPIO.HIGH
		#not_state = GPIO.LOW
	else:
		state = GPIO.LOW
		#not_state = GPIO.HIGH
	pin = int(pinNum)
	GPIO.setup(pin,GPIO.OUT)
	print "LED on"
	GPIO.output(pin,state)
	#time.sleep(1)
	#print "LED off"
	#GPIO.output(pin,state)
	return "Pin %s has is now %s!" %(pin,state)


if __name__ =='__main__':
	MySQLConnect()
