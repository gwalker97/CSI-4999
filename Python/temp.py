import os
import glob
import time
import mysql.connector
from mysql.connector import MySQLConnection, Error

hostname = '127.0.0.1'
username = 'root'
password = 'root'
dbname = 'SeniorProject'
dbtable = 'Addon'

c = 0
f = 0

os.system('modprobe w1-gpio')
os.system('modprobe w1-therm')
 
base_dir = '/sys/bus/w1/devices/'
device_folder = ""
device_file = ""
def read_sensor():
	global device_folder, device_file
	done = False
	while not done:
		try:
			device_folder = glob.glob(base_dir + '28*')[0]
			device_file = device_folder + '/w1_slave'
			done = True
		except:	
			print "not done"
			done = False
	return done

def read_temp_raw():
	f = open(device_file, 'r')
	lines = f.readlines()
        f.close()
        return lines
 
def read_temp():
	global c, f
	#try:
    	lines = read_temp_raw()
	print "%s" %(lines[1])
     	while lines[0].strip()[-3:] != 'YES':
     		time.sleep(0.2)
		lines = read_temp_raw()
	equals_pos = lines[1].find('t=')
	print "While"
	if equals_pos != -1:
		print "if"
       		temp_string = lines[1][equals_pos+2:]
       		c = float(temp_string) / 1000.0
       		f = c * 9.0 / 5.0 + 32.0
	return c, f
	#except:
		#print read_sensor()

def mysqlConn():
	myconn = mysql.connector.connect(host=hostname, user=username, passwd=password, db=dbname)
	return myconn

def reading():
	conn = mysqlConn()
	cur = conn.cursor(buffered=True)
	while True:
		try:
			temps = read_temp()
			print "%s C / %s F" %(int(temps[0]),int(temps[1]))
			conn.commit()
			print "Sending Update"
			cur.execute( "Update Temp set C = %s, F = %s;" %(int(temps[0]), int(temps[1])))
			time.sleep(.5)
		except:
			print "Nope"
def main():
	initBool = False;
	while not initBool:
		print "initbool"
		initBool = read_sensor()
	reading()
if __name__ == '__main__':
	main()	
