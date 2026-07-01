import pickle
from fastapi import FastAPI
from pydantic import BaseModel
import numpy as np
import pandas as pd

app = FastAPI()

with open("models/model_xgboost_bayesian.pkl", "rb") as f:
    bundle = pickle.load(f)

models = bundle['models']
mappings = bundle['mappings']
scaler = bundle['scaler']
le_jk = bundle['le_jk']

class BalitaInput(BaseModel):
    jk: str            
    usia_ukur: float   
    berat: float
    tinggi: float

@app.post("/predict")
def predict_status_gizi(data: BalitaInput):
    jk_encoded = le_jk.transform([data.jk])[0]
    
    fitur_numerik = np.array([[data.usia_ukur, data.berat, data.tinggi]])
    fitur_numerik_scaled = scaler.transform(fitur_numerik)
    
    input_df = pd.DataFrame([{
        'JK': jk_encoded,
        'Usia Saat Ukur': fitur_numerik_scaled[0][0],
        'Berat': fitur_numerik_scaled[0][1],
        'Tinggi': fitur_numerik_scaled[0][2]
    }])
    
    output_status = {}
    target_indicators = ['BB/U', 'TB/U', 'BB/TB']
    
    for col in target_indicators:
        model_target = models[col]
        mapping_target = mappings[col]
        
        pred_mapped = model_target.predict(input_df)[0]
        
        inverse_mapping = {v: k for k, v in mapping_target.items()}
        label_integer_asli = inverse_mapping[pred_mapped]
        
        if col == 'BB/U':
            status_asli = bundle['le_bbu'].inverse_transform([label_integer_asli])[0]
        elif col == 'TB/U':
            status_asli = bundle['le_tbu'].inverse_transform([label_integer_asli])[0]
        elif col == 'BB/TB':
            status_asli = bundle['le_bbtb'].inverse_transform([label_integer_asli])[0]
            
        output_status[col] = status_asli

    return {
        "status": "success",
        "hasil": {
            "bbu": output_status['BB/U'],
            "tbu": output_status['TB/U'],
            "bbtb": output_status['BB/TB']
        }
    }