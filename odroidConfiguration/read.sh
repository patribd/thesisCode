#!/bin/bash

# Function to generate the JWT Token
generate_jwt() {
    local header='{"alg":"HS256","typ":"JWT"}'
    local payload='{"exp":1721907810}'
    local secret="raspberrypi"

    # Codificar header y payload en base64
    local encoded_header=$(echo -n "$header" | base64 | tr -d '\n=' | tr '/+' '_-')
    local encoded_payload=$(echo -n "$payload" | base64 | tr -d '\n=' | tr '/+' '_-')

    # Crear token JWT
    local token="$encoded_header.$encoded_payload"

    # Firmar el token con HMAC-SHA256
    local signature=$(echo -n "$token" | openssl dgst -sha256 -hmac "$secret" -binary | base64 | tr '+/' '-_' | tr -d '=')

    # Construir el token JWT completo
    local jwt="$token.$signature"

    echo "$jwt"
}

# Function to process the new line
process() {
        line="$1"
        tiemstamp="$2"
        jwt_token=$(generate_jwt)
        odroid_mac=$(get_mac_address "eth0")

        count=$(echo "$line" | awk -F"," '{print NF-1}')

        if [ $count -eq 2 ]; then
                # Routing packafe information
                IFS=',' read -r -a array <<< "$line"
                array=("${array[@]:0:1}" "$odroid_mac" "${array[@]:1:1}" "$timestamp")
                dataArray=$(IFS=','; echo  "${array[*]}")

        else
                IFS=',' read -r -a array <<< "$line"
                array=("${array[@]:0:4}" "$timestamp")
                dataArray=$(IFS=','; echo  "${array[*]}")
        fi

        curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer $jwt_token" -d "{\"data\":\"$dataArray\"}" http://192.168.0.130:5053/server.php
}

# Fuction to obtain the MAC address of the Odroid M1S to which the nRF Dongle is connected
get_mac_address(){
        local interface="$1"
        ifconfig "$interface" | awk '/ether/ {print $2}'
}

>data.txt

cat "/dev/ttyACM0" >> data.txt &
cat "/dev/ttyACM1"  >> data.txt &
# Shared secret key for JWT authentication
jwt_secret="raspberrypi"

# Find all ttyACM devices in /dev/
devices=( $(find /dev -type c -name 'ttyACM*' 2>/dev/null) )

# Loop to continuously monitor and process lines from all devices
stdbuf -oL tail -n0 -f "data.txt" | while read -r line; do
        timestamp=$(date +'%s.%N')
        process "$line" "$timestamp"
done
