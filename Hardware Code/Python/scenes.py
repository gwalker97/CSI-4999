#This script will take times from the query and then create a cron for that specific time.
#That will also run in this script.

import sys
import GPIO
def schedule(hour, minute, pin, identifier):
	#This will take the time and schedule it with cron
	#The identifier determines if it is start or end time as a 0 or 1

def perform(sys.argv[1], sys.argv[2]):
	#Args will carry the scene Id and if its the start or the end of a scene
	sceneId = sys.argv[1]
	state = sys.argv[2]
	query = "Select Addon_Pin from Addon where Addon.Addon_ID IN (select DISTINCT Addon_ID from Scene_Assignment where Scene_Assignment.Scene_ID = 1);"

