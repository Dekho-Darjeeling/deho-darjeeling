# app/config.py
import os

# FastAPI Settings
APP_NAME = "Dekho Darjeeling"
BASE_URL = "http://localhost:8000"

# SQLite DB
DATABASE_URL = "sqlite:///./bookings.db"

# Gmail SMTP
SMTP_EMAIL = "yourgmail@gmail.com"
SMTP_PASSWORD = "your_app_password"  # Use App Password

# WhatsApp Cloud API
WA_PHONE_NUMBER_ID = "your_phone_number_id"
WA_ACCESS_TOKEN = "your_whatsapp_access_token"
WA_API_URL = f"https://graph.facebook.com/v17.0/{WA_PHONE_NUMBER_ID}/messages"

# reCAPTCHA
RECAPTCHA_SECRET = "your_recaptcha_secret_key"
