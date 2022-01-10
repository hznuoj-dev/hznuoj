#! /bin/bash

function judge_round() {
    for config in $(sudo find -O2 "$1" -name judge.conf); do
        etc=$(dirname "$config")
        home=$(dirname "$etc")
        sudo judgeonce "$home" debug 1
    done
}

while true; do
    judge_round "${1}"
done
