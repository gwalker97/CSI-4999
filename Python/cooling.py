import num
import RPi.GPIO as gpio
import temp

pin = 17


def coolOn():
	global pin
	gpio.setmode(gpio.BCM)
	gpio.setwarnings(False)
	gpio.setup(pin, gpio.OUT)
	gpio.output(pin, gpio.HIGH)

def coolOff():
	global pin
	gpio.setmode(gpio.BCM)
	gpio.setwarnings(False)
	gpio.setup(pin, gpio.OUT)
	gpio.output(pin, gpio.LOW)
