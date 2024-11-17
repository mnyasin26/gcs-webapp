# Import necessary libraries
import os
import sys
import copy
import math
import numpy
import random
import json
import requests

# Function to calculate the Haversine distance between two points on the Earth
def haversine_distance(origin, destination):
    # Convert latitude and longitude from degrees to radians
    lat1 = origin[0]*3.14/180
    lon1 = origin[1]*3.14/180
    lat2 = destination[0]*3.14/180
    lon2 = destination[1]*3.14/180

    # Radius of the Earth in kilometers
    radius = 6371 # km

    # Calculate the differences in latitude and longitude
    deltaLat = lat2 - lat1
    deltaLon = lon2 - lon1
    
    # Calculate the Haversine formula
    a = math.sin(deltaLat/2)**2 + math.cos(lat1)*math.cos(lat2) * math.sin(deltaLon/2)**2
    c = 2*math.atan2(math.sqrt(a),math.sqrt(1-a))

    # Calculate the distance
    dis = radius * c

    return dis

# Function to calculate the TSP path using simulated annealing
def calculate_tsp_path(points, points_num):
    # Generate a random initial tour
    tour = random.sample(range(points_num), points_num)

    # Simulated annealing process
    for temperature in numpy.logspace(0, 1, num=500000)[::-1]:
        # Select two positions in the tour to swap
        [i, j] = sorted(random.sample(range(points_num), 2))
        # Create a new tour by swapping the selected positions
        newTour = tour[:i] + tour[j:j+1] + tour[i+1:j] + tour[i:i+1] + tour[j+1:]

        # Calculate the total distance of the old tour
        oldDistances = 0
        newDistances = 0
        for k in [j, j-1, i, i-1]:
            lat = points[tour[k % points_num]][0]
            lon = points[tour[k % points_num]][1]
            lat2 = points[tour[(k+1) % points_num]][0]
            lon2 = points[tour[(k+1) % points_num]][1]

            origin = [lat, lon]
            destination = [lat2, lon2]

            test = haversine_distance(origin, destination)
            oldDistances += test

        # Calculate the total distance of the new tour
        for k in [j, j-1, i, i-1]:
            lat = points[newTour[k % points_num]][0]
            lon = points[newTour[k % points_num]][1]
            lat2 = points[newTour[(k+1) % points_num]][0]
            lon2 = points[newTour[(k+1) % points_num]][1]

            origin = [lat, lon]
            destination = [lat2, lon2]

            test = haversine_distance(origin, destination)
            newDistances += test

        # Convert distances to meters
        oldDistances = oldDistances * 1000
        newDistances = newDistances * 1000

        # Accept the new tour with a probability depending on the temperature
        if math.exp((oldDistances - newDistances) / temperature) > random.random():
            tour = copy.copy(newTour)

    # Create the final path as a list of dictionaries with latitude and longitude
    path = [{'lat': points[tour[i % points_num]][0], 'long': points[tour[i % points_num]][1]} for i in range(points_num)]
    return path



