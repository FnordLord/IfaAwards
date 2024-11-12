# IFA Awards
Simple awards plugin for phpVMSv7 created by MattW for IceFireAir Virtual Airline. Feel free to use for your own VA.
* IFA: https://www.icefireair.com
* phpVMS: https://github.com/nabeelio/phpvms

## Features
* Extends the phpVMS Award system with additional award types for statistical achievements which will automatically be issued once the desired threshold is reached.
* Currently available: Total distance flown in nautical miles.
* More to come as time goes by.

## Requirements
* Make sure you run a recent dev version of phpVMS v7, from Sept 2024 or later (just pull the latest dev, don't use any of the older "releases")
* You should be familiar with and have access to ssh, composer and git on your webhosting system

## Installation
1. Go into the ```modules``` folder in your phpVMS installation and clone this git repository: ```git clone https://github.com/FnordLord/IfaAwards.git```
2. In your phpVMS backend go to the addons/modules section and activate the IfaAwards plugin.
3. Start creating Awards with the Award Class "Pilot Flown Distance" and insert the desired value for flown distance in nautical miles, e.g. "100" or "10000"
4. That should do it. You're welcome to add issues here in the github repo if you encounter problems. You can bet your butt there are still bugs I haven't discovered yet, despite best efforts.   
