#This python code will run the server on the Raspberry pi and handle the incoming messages from the client. We will be using GPIO pins to control the flow of power in the system. 

import threading, subprocess
import mysql.connector
import RPi.GPIO as gpio
from mysql.connector import MySQLConnection, Error
import time
import temp
#import scenes

hostname = '127.0.0.1'
username = 'root'
password = 'root'
dbname = 'SeniorProject'
dbtable = 'Addon'


#Makes a query request to the database and passes returned data to parser
def doQuery( start, conn ):
	cur = conn.cursor(buffered=True)
	conn.commit()
#Starting work on new join statment for cron
	if start:
		cur.execute("Select Addon.Addon_State, Addon.Addon_ID from Addon where Addon_ID IN (Select Addon_ID from Scene_Assignment where Scene_ID IN (Select Scene_ID from Scenes Where ((Is_Automated = 1) AND (Start_Time <= DATE_FORMAT(NOW(), '%k:%i') AND DATE_FORMAT(NOW() - INTERVAL 50 SECOND, '%k:%i') <= DATE_FORMAT(Start_Time, '%k:%i')))));")
	else:
		cur.execute("Select Addon.Addon_State, Addon.Addon_ID from Addon where Addon_ID IN (Select Addon_ID from Scene_Assignment where Scene_ID IN (Select Scene_ID from Scenes Where ((Is_Automated = 1) AND (End_Time <= DATE_FORMAT(NOW(), '%k:%i') AND DATE_FORMAT(NOW() - INTERVAL 50 SECOND, '%k:%i') >= DATE_FORMAT(End_Time, '%k:%i')))));")
 #This query might not be needed. The impact would be minimal
	if cur.rowcount > 0 : #Check if any rows were returned
		for Addon_State, Addon_ID in cur.fetchall() :
				GPIOQ(Addon_ID, conn, start)
		#for Addon_Pin, Addon_State, Addon_Dim, Addon_dimVal in cur.fetchall() :
		#GPIO(Addon_Pin, Addon_State, Addon_Dim, Addon_dimVal)

#this method will pull from the database every half second and update the GPIO pins
def dbThread( conn ):
	while 1:
		time.sleep(10)
		doQuery( True, conn  )
		doQuery( False, conn )

def MySQLConnect():
	#print "Using mysql.connector...."
	conn = mysql.connector.connect(host=hostname, user=username, passwd=password, db=dbname)	
	# Pull from the database and sync the pin states to that of the database.
	t = threading.Thread(target=dbThread, args=(conn,))
	t.start()

#Sends messages to the GPIO Pins
#Use this header if adding Dim.
def GPIOQ(id, conn, start):
	On_Off = 0
	if start:
		On_Off = 1
	curnew = conn.cursor(buffered=True)
	conn.commit()
	curnew.execute("update Addon set Addon_State = %s where Addon_ID = %s;", (On_Off, id))
	print "update Addon set Addon_State = %s where Addon_ID = %s", (On_Off, id)

if __name__ =='__main__':
	MySQLConnect()
