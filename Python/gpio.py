#This python code will run the server on the Raspberry pi and handle the incoming messages from the client. We will be using GPIO pins to control the flow of power in the system. 

import threading, subprocess, pickle
import mysql.connector
#from gpiozero import LED
from mysql.connector import MySQLConnection, Error

#import RPi.GPIO as GPIO
import time

hostname = '127.0.0.1'
username = 'root'
password = 'root'
dbname = 'SeniorProject'
dbtable = 'Addon'
count = 0
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

#Makes a query request to the database and passes returned data to parser
def doQuery( conn ):
	global count
	cur = conn.cursor(buffered=True)
	conn.commit()
	cur.execute( "SELECT id, appname, pin, flag FROM test WHERE EXISTS (Select table_schema,table_name,update_time FROM information_schema.tables WHERE update_time > (NOW() - INTERVAL .15 SECOND) AND table_schema = '%s' AND table_name='%s')" %(dbname, dbtable));
	if cur.rowcount > 0:
		count = count + 1
		print "%s ========================================" %(count)
		for Addon_ID, Addon_Name, Addon_Pin, Addon_State in cur.fetchall() :
			parseAppliance(Addon_ID, Addon_Name, Addon_Pin, Addon_State)


#this method will pull from the database every half second and update the GPIO pins
def dbThread( conn ):
	endconn = "False"
	
	while 1:
		time.sleep(.1)
		doQuery( conn )

#Used to test pin state against current state and then send pin to get changed in GPIO function
def parseAppliance(id, name, pin, state):
	#Used for testing the current state of pin against the state from the database to look for changes. (No use without Pi)
	#oldState = getpinState()
	#if state != oldState: # Assuming it returns a 0 or 1
	#	print "Changing appliance %s at PIN %s from %s to %s!" %(name, ping, oldState, state)
	#	GPIO(pin, state)
	#else:
		#Nothing because the state didn't change
	
	#Stream of table in database as its refreshed
	print "Flag of appliance %s using PIN %s is %s." %(name, pin, state)
	#GPIO(pin, state)



def MySQLConnect():
	print "Using mysql.connector...."
	myConnection = mysql.connector.connect(host=hostname, user=username, passwd=password, db=dbname)	
	doQuery( myConnection )
	# Pull from the database and sync the pin states to that of the database.
	t = threading.Thread(target=dbThread, args=(myConnection,))
	t.start()
	#myConnection.close()	

#Sends messages to the GPIO Pins
def GPIO(pinNum, On_Off):
	print "blahj"
	#try: 
		#led = LED(pinNum)
		#This statement handles the state which the LED is on
		#Right now the inverse is used to turn the light off.
		#if On_Off == 1:
			#led.on()
		#else:
			#led.off()
		#return "Pin %s has is now %s!" %(pin, On_Off)
	#except:	
		#print "Blah"


if __name__ =='__main__':
	MySQLConnect()
