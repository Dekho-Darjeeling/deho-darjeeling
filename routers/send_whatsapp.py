# app/services/send_whatsapp.py
import httpx
from app.config import WA_API_URL, WA_ACCESS_TOKEN

def send_whatsapp(booking):
    headers = {
        "Authorization": f"Bearer {WA_ACCESS_TOKEN}",
        "Content-Type": "application/json"
    }
    message = f"New Booking: {booking.booking_type}\nName: {booking.name}\nPhone: {booking.phone}\nEmail: {booking.email}\nPackage: {booking.package}\nDate: {booking.date}\nPersons: {booking.persons}"
    data = {
        "messaging_product": "whatsapp",
        "to": "ADMIN_PHONE_NUMBER",
        "type": "text",
        "text": {"body": message}
    }
    try:
        httpx.post(WA_API_URL, headers=headers, json=data)
    except Exception as e:
        print("WhatsApp API error:", e)
