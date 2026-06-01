import pickle
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import numpy as np
import os

app = FastAPI(title="Mesin Prediksi Stunting & Gizi Balita - Random Forest")

# Alamat file model .pkl di dalam folder fastapi-server
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_BBU_PATH  = os.path.join(BASE_DIR, "model_bbu.pkl")
MODEL_TBU_PATH  = os.path.join(BASE_DIR, "model_tbu.pkl")
MODEL_BBTB_PATH = os.path.join(BASE_DIR, "model_bbtb.pkl")

# Amankan proses loading model saat server pertama kali menyala
try:
    with open(MODEL_BBU_PATH, "rb") as f:
        model_bbu = pickle.load(f)
    with open(MODEL_TBU_PATH, "rb") as f:
        model_tbu = pickle.load(f)
    with open(MODEL_BBTB_PATH, "rb") as f:
        model_bbtb = pickle.load(f)
    print("🚀 SUKSES: Semua model Random Forest (.pkl) berhasil dimuat!")
except Exception as e:
    print(f"❌ EROR: Gagal memuat file .pkl. Pastikan nama file sesuai! Pesan eror: {str(e)}")

# Struktur data input yang dikirim dari Laravel
class DataBalitaInput(BaseModel):
    umur_bulan: int
    jenis_kelamin: str  # "L" atau "P"
    berat_badan: float
    tinggi_badan: float

@app.get("/")
def home():
    return {"status": "Server FastAPI Aktif", "algoritma": "Random Forest Multi-Output"}

@app.post("/predict")
def predict_gizi(data: DataBalitaInput):
    try:
        # 1. Konversi jenis kelamin ke angka (sesuaikan dengan codingan Google Colab Anda)
        # Umumnya: Laki-laki = 1, Perempuan = 0 (atau sebaliknya)
        jk_numeric = 1 if data.jenis_kelamin.upper() == "L" else 0
        
        # 2. Susun fitur input sesuai urutan saat Anda melatih model di Colab
        # Contoh urutan standar: [umur_bulan, jenis_kelamin, berat_badan, tinggi_badan]
        fitur = np.array([[data.umur_bulan, jk_numeric, data.berat_badan, data.tinggi_badan]])
        
        # 3. Eksekusi prediksi ke 3 model terpisah
        pred_bbu  = model_bbu.predict(fitur)[0]
        pred_tbu  = model_tbu.predict(fitur)[0]
        pred_bbtb = model_bbtb.predict(fitur)[0]
        
        # 4. Kembalikan hasil prediksi dalam bentuk JSON ke Laravel
        return {
            "status": "success",
            "hasil": {
                "bbu": str(pred_bbu),   # Berat Badan menurut Umur
                "tbu": str(pred_tbu),   # Tinggi Badan menurut Umur (Indikator Stunting)
                "bbtb": str(pred_bbtb)  # Berat Badan menurut Tinggi Badan
            }
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Gagal melakukan prediksi: {str(e)}")