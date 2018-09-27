#This python code will run the server on the Raspberry pi and handle the incoming messages from the client. We will be using GPIO pins to control the flow of power in the system. 

import threading, subprocess
import mysql.connector
import RPi.GPIO as gpio
from mysql.connector import MySQLConnection, Error

#import RPi.GPIO as GPIO
import time

hostname = '127.0.0.1'
username = 'root'
password = 'root'
dbname = 'SeniorProject'
dbtable = 'Addon'
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
	cur = conn.cursor(buffered=True)
	conn.commit()	
	#For now we are just pulling all the time until we can recompile the newest version of mysql to 5.7 on ARM64.
	cur.execute( "Select Addon_ID, Addon_Name, Addon_Pin, Addon_State from Addon")
	#cur.execute( "SELECT Addon_ID, Addon_Name, Addon_Pin, Addon_State FROM %s WHERE EXISTS (Select table_schema,table_name,update_time FROM information_schema.tables WHERE update_time > (NOW() - INTERVAL .15 SECOND) AND table_schema = '%s' AND table_name='%s')" %(dbtable, dbname, dbtable));
	if cur.rowcount > 0 : 
		print "========================================"
		for Addon_ID, Addon_Name, Addon_Pin, Addon_State in cur.fetchall() :
			GPIO(Addon_Pin, Addon_State)
			#parseAppliance(Addon_ID, Addon_Name, Addon_Pin, Addon_State)

#this method will pull from the database every half second and update the GPIO pins
def dbThread( conn ):
	while 1:
		time.sleep(.1)
		doQuery( conn )

#Used to test pin state against current state and then send pin to get changed in GPIO function
def parseAppliance(id, name, pin, state):
	#Stream of table in database as its refreshed
	print "Flag of appliance %s using PIN %s is %s." %(name, pin, state)
	GPIO(pin, state)



def MySQLConnect():
	print "Using mysql.connector...."
	myConnection = mysql.connector.connect(host=hostname, user=username, passwd=password, db=dbname)	
	doQuery( myConnection )
	# Pull from the database and sync the pin states to that of the database.
	t = threading.Thread(target=dbThread, args=(myConnection,))
	t.start()

#Sends messages to the GPIO Pins
def GPIO(pinNum, On_Off):
	#Setting up the GPIO Pin that is being used
	gpio.setmode(gpio.BCM)
	gpio.setwarnings(False)
	gpio.setup(pinNum, gpio.OUT)
	#print "State of Pin %s" %(gpio.input(pinNum)) #Debugging
	#Right now the inverse is used to turn the light off.
	if On_Off == 1 and gpio.input(pinNum) == 0 :
		gpio.output(pinNum, gpio.HIGH)
	elif On_Off == 0 and gpio.input(pinNum) == 1:
		gpio.output(pinNum, gpio.LOW)
	#print "Pin %s is now %s!" %(pinNum, On_Off) #Debugging


if __name__ =='__main__':
	MySQLConnect()
