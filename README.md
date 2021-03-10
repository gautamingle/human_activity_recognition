# human_activity_recognition

HIS-Master-Projekt, Winter 2018  

## Introduction

This is a web application created to simulate a heatlh detection and warning system for Human Fall.  

## Prerequisites

This webapp required a browser able to run Php scripts
Data file (CSV)  

## Dataset
- The data required is sourced from 


## Mathematical Model

Formula :

Acceleration [g]: (2 ∗ Range)/(2̊Resolution ) ∗ AD

Where, AD stands for acceleration data in all 3 axes (x, y, z) Acceleration Sensor 1 -> Range: ±16G , Resolution: 13 bits
Acceleration Sensor 2 -> Range: ±8G , Resolution: 14 bits
We will get new values as Gx,Gy,Gz parameters. For calculating the overall acceleration, we have used
below formula:
Overall acceleration Gmax = 􏰀G2x + G2y + G2z ................................................. ..7.2
We have termed highest value as Gmaxand lowest value as Gmin
If Gmax − Gmin > 2.5G, Gmax should occur after Gmin then it is a critical fall