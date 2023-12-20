import hashlib
import mysql.connector
import pymongo
from virus_total_apis import PublicApi

# Configuración de la base de datos MariaDB
db_config = {
    "host": "localhost",
    "user": 'user',
    "password": "1234",
    "database": "projecte"
}

# Conexión a la base de datos MariaDB
conn = mysql.connector.connect(**db_config)
cursor = conn.cursor()

# Configuración de la conexión a MongoDB
mongo_client = pymongo.MongoClient("mongodb://localhost:27017/")
db = mongo_client["projecte"]
collection = db["projecte"]

API_KEY = '6269c415a16ff9ecb6d54ce699e40159628836e6d9780a057c93d2ac5478cf59'
api = PublicApi(API_KEY)

try:
    file_path = r'C:\Users\CF2022202\Downloads\ACTIVITAT1.docx'  # Ruta real de tu archivo
    with open(file_path, "rb") as file:
        file_hash = hashlib.md5(file.read()).hexdigest()
    response = api.get_file_report(file_hash)

    if response["response_code"] == 200:
        if response["results"]["positives"] > 30:
            print("Archivo Malicioso ha sido detectado en más de 30 antivirus")
        else:
            print("Menos de 30 antivirus lo han detectado")
    else:
        print("No ha podido obtenerse el análisis del archivo.")
except Exception as e:
    print("Error: ", str(e))

# Cerrar la conexión a la base de datos MariaDB
cursor.close()
conn.close()