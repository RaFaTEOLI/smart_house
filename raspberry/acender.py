import RPi.GPIO as GPIO 
import time 
import sys 
import os 
GPIO.setmode(GPIO.BOARD) 
GPIO.setwarnings(False) 
GPIO.setup(sys.argv[1], GPIO.OUT) 
rele_sala = sys.argv[1]

def ligar(): 
    GPIO.output(rele_sala, GPIO.HIGH) 

if __name__ == "__main__": 
    ligar()