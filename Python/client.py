import socket, threading, subprocess

#class message:
	#def __init__(self)

#Receive data from the server
def receive(conn):
	recData = True
	print "I am at function receive"
	while recData:
		reply = conn.recv(1024)
		if reply == "end!":
			print "Server has sent escape character!"	
			recData = False
		else:
			print "From the Server: %s." %(reply)

#Send data to the client
def send(conn):
	endChat = False
	while not endChat:
		mess = raw_input("Message: ")
		if mess != "end!":	
			conn.send(mess)
		else:
			conn.send("end!")
			conn.close()
			endChat = True
	
def main():
	#creates socket 's'
	server = socket.socket()

	#Asks for port used for server (type 'str')
	port = raw_input("Enter the port you want to connect to: ")
	#port = 1234

	#Aks for server ip
	#serv_ip = raw_input("Please enter the servers ip address: ")
	#serv_ip = '169.254.152.22'
	serv_ip = '127.0.0.1'

	#Attempts to connect to the server if it is listening
	server.connect((serv_ip, int(port)))

	t = threading.Thread(target=receive, args=(server,))
	t.start()
	send(server)
	print 'Connecting to IP %s throuhg Port %s' %(serv_ip, port)

	#Prints the received data from the server up to 1024 byte buffer limit
	#This is only used for non-threaded test


if __name__ == '__main__':
	main()
