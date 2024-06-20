#!/bin/bash

get_mac_address(){
        local interface="$1"
        ifconfig "$interface" | awk '/ether/ {print $2}'
}
odroid_mac=$(get_mac_address "eth0")

arguments=("$@")

length=${#arguments[@]}

if [ "$length" -lt 1 ];then
        echo "There are no arguments."
        echo "Include one MAC address for each nRF Dongle you want to register under this Odroid M1S."
        exit 1
fi


array_args=""

for ((i=0; i<${#arguments[@]}; i++ ))
do
        array_args+="${arguments[$i]}"
        if [ $i -lt $((${#arguments[@]}-1)) ]; then
        array_args+=","
        fi
done

if [ -n "$odroid_mac" ]; then
        array_args+=","
        array_args+="$odroid_mac"
else
        echo "Failed to obtain the MAC address of the Odroid, check if the interface is correct."
        exit 1
fi

generate_jwt() {
    local header='{"alg":"HS256","typ":"JWT"}'
    local payload='{"exp":1721907810}'
    local secret="raspberrypi"

    # Codify the header and payload in base64
    local encoded_header=$(echo -n "$header" | base64 | tr -d '\n=' | tr '/+' '_-')
    local encoded_payload=$(echo -n "$payload" | base64 | tr -d '\n=' | tr '/+' '_-')

    # Create token JWT
    local token="$encoded_header.$encoded_payload"

    # Sign the token using HMAC-SHA256
    local signature=$(echo -n "$token" | openssl dgst -sha256 -hmac "$secret" -binary | base64 | tr '+/' '-_' | tr -d '=')

    # Build the token
    local jwt="$token.$signature"

    echo "$jwt"
}

jwt_token=$(generate_jwt)       
curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer $jwt_token" -d "{\"data\":\"$array_args\"}" http://192.168.0.130:5053/registration.php


