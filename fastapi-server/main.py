import pickle
from fastapi import FastAPI
from pydantic import BaseModel
import numpy as np
import pandas as pd

app = FastAPI()

# 1. Memuat file bundle model baru hasil Bayesian Optimization
# Pastikan file 'model_xgboost_bayesian.pkl' sudah ditaruh di folder yang benar
with open("models/model_xgboost_bayesian.pkl", "rb") as f:
    bundle = pickle.load(f)

# Ekstraksi komponen dari dalam bundle
models = bundle['models']
mappings = bundle['mappings']
scaler = bundle['scaler']
le_jk = bundle['le_jk']

# 2. Skema data input dari Laravel
class BalitaInput(BaseModel):
    jk: str            # 'L' atau 'P'
    usia_ukur: float   # Total bulan hasil konversi
    berat: float
    tinggi: float

@app.post("/predict")
def predict_status_gizi(data: BalitaInput):
    # a. Transformasi fitur kategori (Jenis Kelamin) menggunakan LabelEncoder asli
    jk_encoded = le_jk.transform([data.jk])[0]
    
    # b. Transformasi fitur numerik menggunakan StandardScaler asli
    fitur_numerik = np.array([[data.usia_ukur, data.berat, data.tinggi]])
    fitur_numerik_scaled = scaler.transform(fitur_numerik)
    
    # c. Gabungkan semua fitur menjadi satu baris dataframe sesuai urutan training
    # Urutan: ['JK', 'Usia Saat Ukur', 'Berat', 'Tinggi']
    input_df = pd.DataFrame([{
        'JK': jk_encoded,
        'Usia Saat Ukur': fitur_numerik_scaled[0][0],
        'Berat': fitur_numerik_scaled[0][1],
        'Tinggi': fitur_numerik_scaled[0][2]
    }])
    
    # d. Proses Prediksi Adaptif menggunakan 3 model terpisah
    output_status = {}
    target_indicators = ['BB/U', 'TB/U', 'BB/TB']
    
    for col in target_indicators:
        model_target = models[col]
        mapping_target = mappings[col]
        
        # Prediksi menghasilkan indeks internal XGBoost
        pred_mapped = model_target.predict(input_df)[0]
        
        # Balikkan indeks internal XGBoost ke nilai label Kemenkes asli (0, 1, 2, dst)
        inverse_mapping = {v: k for k, v in mapping_target.items()}
        label_integer_asli = inverse_mapping[pred_mapped]
        
        # Terjemahkan nilai integer asli menggunakan LabelEncoder target bawaan
        if col == 'BB/U':
            status_asli = bundle['le_bbu'].inverse_transform([label_integer_asli])[0]
        elif col == 'TB/U':
            status_asli = bundle['le_tbu'].inverse_transform([label_integer_asli])[0]
        elif col == 'BB/TB':
            status_asli = bundle['le_bbtb'].inverse_transform([label_integer_asli])[0]
            
        output_status[col] = status_asli

    # e. Kembalikan hasil ke Laravel berupa JSON bersih
    return {
        "status": "success",
        "hasil": {
            "bbu": output_status['BB/U'],
            "tbu": output_status['TB/U'],
            "bbtb": output_status['BB/TB']
        }
    }