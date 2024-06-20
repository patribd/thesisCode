import odroid_wiringpi as wpi
import time
import requests
import jwt
import netifaces

def increment_variable():
        try:
                with open('variable.txt', 'r') as file:
                        value = int(file.read())
        except FileNotFoundError:
                value=0
        value+=1

        with open('variable.txt', 'w') as file:
                file.write(str(value))
        return value

try :
        while True:
                new_value=increment_variable()
                ADC_PIN = 1

                if wpi.wiringPiSetup() == -1:
                        print("Setup failed")
                        exit(1)

                adc_value=wpi.analogRead(ADC_PIN)
                if adc_value == -1:
                        print("Failed to read from ADC pin")
                        exit(1)

                ADC_MAX=1023
                V_REF=1.8

                voltage=(adc_value/ADC_MAX)*V_REF

                timestamp = time.time()

                data = f"I,{voltage},{timestamp},{new_value}"

                filename = 'dataCurrent.txt'


                with open(filename, 'a') as file:
                    file.write(data + '\n')

                time.sleep(10)
except KeyboardInterrupt:
        print("The system is not making any more measurements.")








