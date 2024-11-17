import pytest
from fastapi.testclient import TestClient
from api import app
import csv
import os

client = TestClient(app)

def read_csv(file_path):
    points = []
    with open(file_path, mode='r') as file:
        csv_reader = csv.reader(file)
        for row in csv_reader:
            points.append({"lat": float(row[0]), "long": float(row[1]), "klasifikasi": int(row[2])})
    return points

def get_csv_files(directory):
    return [f for f in os.listdir(directory) if f.endswith('.csv')]

@pytest.mark.parametrize("csv_file", get_csv_files("test_data"))
def test_calculate_tsp(csv_file):
    file_path = os.path.join("test_data", csv_file)
    points = read_csv(file_path)
    print(points)
    response = client.post("/calculate-tsp", json=points)
    # print(response.json())
    if sum(point['klasifikasi'] == 1 for point in points) < 2:
        assert response.status_code == 400
        assert response.json() == {"detail": "At least two points with klasifikasi 1 are required"}
    else:
        assert response.status_code == 200
        assert "path" in response.json()
        assert len(response.json()["path"]) > 0