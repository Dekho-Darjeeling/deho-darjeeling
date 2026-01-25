# app/routers/booking.py
from fastapi import APIRouter, Depends, HTTPException, Request
from sqlalchemy.orm import Session
from app.database import get_db
from app.models import Booking
from app.services.send_mail import send_mail_admin, send_mail_user
from app.services.send_whatsapp import send_whatsapp
import httpx
from app.config import RECAPTCHA_SECRET

router = APIRouter()

def verify_recaptcha(token: str):
    url = "https://www.google.com/recaptcha/api/siteverify"
    data = {"secret": RECAPTCHA_SECRET, "response": token}
    response = httpx.post(url, data=data).json()
    return response.get("success", False)

@router.post("/book-tour")
async def book_tour(request: Request, db: Session = Depends(get_db)):
    data = await request.form()
    if not verify_recaptcha(data.get("captcha_token")):
        raise HTTPException(status_code=400, detail="reCAPTCHA failed")

    booking = Booking(
        name=data.get("name"),
        email=data.get("email"),
        phone=data.get("phone"),
        booking_type="tour",
        package=data.get("package"),
        date=data.get("date"),
        persons=int(data.get("persons")),
        notes=data.get("notes")
    )
    db.add(booking)
    db.commit()
    db.refresh(booking)

    # Send notifications
    send_mail_admin(booking)
    send_mail_user(booking)
    send_whatsapp(booking)

    return {"success": True, "message": "Tour booked successfully"}

@router.post("/book-hiking")
async def book_hiking(request: Request, db: Session = Depends(get_db)):
    data = await request.form()
    if not verify_recaptcha(data.get("captcha_token")):
        raise HTTPException(status_code=400, detail="reCAPTCHA failed")

    booking = Booking(
        name=data.get("name"),
        email=data.get("email"),
        phone=data.get("phone"),
        booking_type="hiking",
        package=data.get("package"),
        date=data.get("date"),
        persons=int(data.get("persons")),
        notes=data.get("notes")
    )
    db.add(booking)
    db.commit()
    db.refresh(booking)

    # Send notifications
    send_mail_admin(booking)
    send_mail_user(booking)
    send_whatsapp(booking)

    return {"success": True, "message": "Hiking booked successfully"}
