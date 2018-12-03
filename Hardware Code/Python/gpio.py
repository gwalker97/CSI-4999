#This python code will run the server on the Raspberry pi and handle the incoming messages from the client. We will be using GPIO pins to control the flow of power in the system. 

import threading, subprocess
import mysql.connector
import RPi.GPIO as gpio
from mysql.connector import MySQLConnection, Error
import time
import temp
import scenes

hostname = '127.0.0.1'
username = 'root'
password = 'root'
dbname = 'SeniorProject'
dbtable = 'Addon'
conn

#Makes a query request to the database and passes returned data to parser
def doQuery(  ):
	global conn
	cur = conn.cursor(buffered=True)
	conn.commit()
#Starting work on new join statment for cron
	#cur.execute( "select Scenes.Start_Time, Scenes.End_Time from Scenes INNER JOIN Scene_Assignment on Scenes.Scene_ID = Scene_Assignment.Scene_ID INNER JOIN Addon on Scene_Assignment.Addon_ID = Addon.Addon_ID;")
 #This query might not be needed. The impact would be minimal
	if cur.rowcount > 0 : #Check if any rows were returned
		for Addon_ID, Addon_Pin, Addon_State, Addon_Host_ID in cur.fetchall() :
			if (Addon_Host_ID == 1):
				GPIO(Addon_ID, Addon_Pin, Addon_State)
		#for Addon_Pin, Addon_State, Addon_Dim, Addon_dimVal in cur.fetchall() :
		#GPIO(Addon_Pin, Addon_State, Addon_Dim, Addon_dimVal)

#this method will pull from the database every half second and update the GPIO pins
def dbThread(  ):
	while 1:
		time.sleep(.1)
		doQuery(  )


def MySQLConnect():
	#print "Using mysql.connector...."
	conn = mysql.connector.connect(host=hostname, user=username, passwd=password, db=dbname)	
	doQuery( myConnection )
	# Pull from the database and sync the pin states to that of the database.
	t = threading.Thread(target=dbThread,)
	t.start()

#Sends messages to the GPIO Pins
#Use this header if adding Dim.
def GPIO(id, pinNum, On_Off):
	#Setting up the GPIO Pin that is being used
	global dLights
	gpio.setmode(gpio.BCM)
	gpio.setwarnings(False)
	gpio.setup(pinNum, gpio.OUT)
	
	#Checks the value of the pin against current state
	if On_Off == 1 and gpio.input(pinNum) < 1 :
		gpio.output(pinNum, gpio.HIGH)
		if dLights[id] != 0:
			dLights[id] = 0
	elif On_Off == 0 and gpio.input(pinNum) > 0:
		gpio.output(pinNum, gpio.LOW)
		if dLights[id] != 0:
			dLights[id] = 0
	elif On_Off != 1 and On_Off != 0:
		#At this point the value might be used for dimming (Assume 0 < x < 1)
		if dLights[id] != 0:
			pwm = dLights[id]
			pwm.ChangeDutyCycle(On_Off * 100)
		else:
			pwm = gpio.PWM(pinNum, 100)
			pwm.start(0)
			pwm.ChangeDutyCycle(On_Off * 100)
			dLights[id] = pwm

if __name__ =='__main__':
	#MySQLConnect()
