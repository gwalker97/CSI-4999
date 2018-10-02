#This python code will run the server on the Raspberry pi and handle the incoming messages from the client. We will be using GPIO pins to control the flow of power in the system. 

import threading, subprocess
import mysql.connector
import RPi.GPIO as gpio
from mysql.connector import MySQLConnection, Error
import time

hostname = '127.0.0.1'
username = 'root'
password = 'root'
dbname = 'SeniorProject'
dbtable = 'Addon'


#Makes a query request to the database and passes returned data to parser
def doQuery( conn ):
	cur = conn.cursor(buffered=True)
	conn.commit()	
	#For now we are just pulling all the time until we can recompile the newest version of mysql to 5.7 on ARM64.
	cur.execute( "Select Addon_ID, Addon_Name, Addon_Pin, Addon_State from Addon")
	#cur.execute( "SELECT Addon_ID, Addon_Name, Addon_Pin, Addon_State FROM %s WHERE EXISTS (Select table_schema,table_name,update_time FROM information_schema.tables WHERE update_time > (NOW() - INTERVAL .15 SECOND) AND table_schema = '%s' AND table_name='%s')" %(dbtable, dbname, dbtable)); #This query might not be needed. The impact would be minimal
	if cur.rowcount > 0 : 
		#print "========================================"
		try:
			for Addon_Pin, Addon_State in cur.fetchall() :
				GPIO(Addon_Pin, Addon_State)
			#for Addon_Pin, Addon_State, Addon_Dim, Addon_dimVal in cur.fetchall() :
				#GPIO(Addon_Pin, Addon_State, Addon_Dim, Addon_dimVal)
		except:
			#There was an error, ignore it.
			print "hello"

#this method will pull from the database every half second and update the GPIO pins
def dbThread( conn ):
	while 1:
		time.sleep(.1)
		doQuery( conn )


def MySQLConnect():
	#print "Using mysql.connector...."
	myConnection = mysql.connector.connect(host=hostname, user=username, passwd=password, db=dbname)	
	doQuery( myConnection )
	# Pull from the database and sync the pin states to that of the database.
	t = threading.Thread(target=dbThread, args=(myConnection,))
	t.start()

#Sends messages to the GPIO Pins
#Use this header if adding Dim.
def GPIO(pinNum, On_Off):
	#Setting up the GPIO Pin that is being used
	gpio.setmode(gpio.BCM)
	gpio.setwarnings(False)
	gpio.setup(pinNum, gpio.OUT)
	#Setting up the current pin for dimming
		#pwm=gpio.PWM(pinNum, 1000)
	#Right now the inverse is used to turn the light off.
	if On_Off == 1 and gpio.input(pinNum) < 1 :
		gpio.output(pinNum, gpio.HIGH)
	elif On_Off == 0 and gpio.input(pinNum) > 0:
		gpio.output(pinNum, gpio.LOW)
	elif On_Off != 1 and On_Off != 0:
		#At this point the value might be used for dimming
		#pwm = gpio.PWM(pinNum, gpio.OUT)
		#pwm.ChangeDutyCycle(dimVal)
		
		print "sit"

if __name__ =='__main__':
	#gpio.setmode(gpio.BCM)
	#gpio.setwarnings(False)
	#gpio.setup(17, gpio.OUT)
	#gpio.setup(27, gpio.OUT)
	#gpio.setup(22, gpio.OUT)
	#gpio.setup(23, gpio.OUT)
	#pwm1 = gpio.PWM(17, 1000)
	#pwm2 = gpio.PWM(27, 1000)
	#pwm3 = gpio.PWM(23, 1000)
	#pwm4 = gpio.PWM(22, 1000)
	#pwm1.start(0)
	#pwm2.start(100)
	#pwm3.start(0)
	#pwm4.start(100)
	#count = 0
	#frac = 20
	#while 1:
	#	if count > 5:
	#		exit(0)
	#	pwm1.ChangeDutyCycle(0 + (count * frac))
	#	pwm2.ChangeDutyCycle(100 - (frac * count))
	#	pwm3.ChangeDutyCycle(0 + (count * frac))
	#	pwm4.ChangeDutyCycle(100 - (frac * count))
	#	time.sleep(3)
	#	count += 1
	MySQLConnect()
