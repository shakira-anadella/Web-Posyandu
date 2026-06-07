from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import pickle
import numpy as np
import pandas as pd

app = FastAPI(
    title="API Status Gizi Balita (XGBoost Multi-Output)",
    version="1.0"
)

# =========================================================================
# 1. LOAD MODEL, SCALER, DAN PEMETAAN LABEL KEMENKES
# =========================================================================
try:
    model_bbu = pickle.load(open("models/xgb_model_bbu.pkl", "rb"))
    model_tbu = pickle.load(open("models/xgb_model_tbu.pkl", "rb"))
    model_bbtb = pickle.load(open("models/xgb_model_bbtb.pkl", "rb"))
    scaler = pickle.load(open("models/scaler_antropometri.pkl", "rb"))
    print("🚀 Berhasil memuat seluruh Model XGBoost dan Scaler!")
except Exception as e:
    print(f"❌ Gagal memuat file model. Periksa path folder! Eror: {str(e)}")

# Pemetaan angka prediksi kembali ke teks medis resmi Kemenkes
LABEL_BBU = {0: "Gizi Buruk", 1: "Gizi Kurang", 2: "Gizi Baik", 3: "Gizi Lebih"}
LABEL_TBU = {0: "Sangat Pendek (Severely Stunting)", 1: "Pendek (Stunting)", 2: "Normal", 3: "Tinggi"}
LABEL_BBTB = {0: "Gizi Buruk (Severely Wasting)", 1: "Gizi Kurang (Wasting)", 2: "Gizi Baik (Normal)", 3: "Berisiko Gizi Lebih", 4: "Gizi Lebih"}

# =========================================================================
# 2. DEFINISI STRUKTUR INPUT DATA (Pydantic Model)
# =========================================================================
class DataBalitaInput(BaseModel):
    jk: str           # Menerima teks 'L' atau 'P' dari Laravel
    usia_bulan: float # Menerima total angka bulan hasil konversi
    berat_badan: float
    tinggi_badan: float

# =========================================================================
# 3. ENDPOINT API PREDIKSI
# =========================================================================
@app.post("/api/predict-gizi")
async def prediksi_status_gizi(data: DataBalitaInput):
    try:
        # A. Encoding Jenis Kelamin (L -> 0, P -> 1)
        jk_encoded = 0 if data.jk.upper() == 'L' else 1
        
        # B. Susun sesuai urutan fitur data latih: ['JK', 'Usia Saat Ukur', 'Berat', 'Tinggi']
        fitur_mentah = np.array([[jk_encoded, data.usia_bulan, data.berat_badan, data.tinggi_badan]])
        
        # C. Standardisasi fitur numerik menggunakan scaler bawaan (.pkl)
        df_fitur = pd.DataFrame(fitur_mentah, columns=['JK', 'Usia Saat Ukur', 'Berat', 'Tinggi'])
        df_fitur[['Usia Saat Ukur', 'Berat', 'Tinggi']] = scaler.transform(df_fitur[['Usia Saat Ukur', 'Berat', 'Tinggi']])
        
        # D. Eksekusi Prediksi Paralel ke 3 Model
        pred_bbu_code = int(model_bbu.predict(df_fitur)[0])
        pred_tbu_code = int(model_tbu.predict(df_fitur)[0])
        pred_bbtb_code = int(model_bbtb.predict(df_fitur)[0])
        
        # E. Ambil teks hasil mapping
        hasil_bbu = LABEL_BBU.get(pred_bbu_code, "Tidak Diketahui")
        hasil_tbu = LABEL_TBU.get(pred_tbu_code, "Tidak Diketahui")
        hasil_bbtb = LABEL_BBTB.get(pred_bbtb_code, "Tidak Diketahui")
        
        return {
            "status": "success",
            "hasil_diagnosis": {
                "bbu": hasil_bbu,
                "tbu_stunting": hasil_tbu,
                "bbtb_wasting": hasil_bbtb
            }
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Eror komputasi server AI: {str(e)}")