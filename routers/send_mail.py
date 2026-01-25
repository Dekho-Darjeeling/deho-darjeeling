# app/services/send_mail.py
import smtplib
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart
from app.config import SMTP_EMAIL, SMTP_PASSWORD

ADMIN_EMAIL = SMTP_EMAIL

def send_mail_admin(booking):
    msg = MIMEMultipart()
    msg['From'] = SMTP_EMAIL
    msg['To'] = ADMIN_EMAIL
    msg['Subject'] = f"New {booking.booking_type.capitalize()} Booking Received"
    body = f"""
    Name: {booking.name}
    Email: {booking.email}
    Phone: {booking.phone}
    Type: {booking.booking_type}
    Package: {booking.package}
    Date: {booking.date}
    Persons: {booking.persons}
    Notes: {booking.notes}
    """
    msg.attach(MIMEText(body, 'plain'))
    server = smtplib.SMTP('smtp.gmail.com', 587)
    server.starttls()
    server.login(SMTP_EMAIL, SMTP_PASSWORD)
    server.send_message(msg)
    server.quit()

def send_mail_user(booking):
    msg = MIMEMultipart()
    msg['From'] = SMTP_EMAIL
    msg['To'] = booking.email
    msg['Subject'] = f"Booking Confirmation - {booking.booking_type.capitalize()}"
    body = f"Hello {booking.name},\n\nYour {booking.booking_type} booking is confirmed for {booking.date}. Thank you for choosing Dekho Darjeeling!"
    msg.attach(MIMEText(body, 'plain'))
    server = smtplib.SMTP('smtp.gmail.com', 587)
    server.starttls()
    server.login(SMTP_EMAIL, SMTP_PASSWORD)
    server.send_message(msg)
    server.quit()
