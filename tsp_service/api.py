from fastapi import FastAPI, HTTPException
from tsp_path import calculate_tsp_path
from pydantic import BaseModel
from typing import List

app = FastAPI()

@app.get("/")
def read_root():
    return {"Hello": "World"}

class Point(BaseModel):
    lat: float
    long: float
    klasifikasi: int

@app.post("/calculate-tsp")
def calculate_tsp(points: List[Point]):
    data = [[point.lat, point.long, point.klasifikasi] for point in points if point.klasifikasi == 1]
    if len(data) < 2:
        raise HTTPException(status_code=400, detail="At least two points with klasifikasi 1 are required")
    path = calculate_tsp_path(data, len(data))
    return {"path": path}