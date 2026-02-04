# app/models.py
from sqlalchemy import Column, Integer, String, DateTime
from sqlalchemy.sql import func
from app.database import Base

class Booking(Base):
    __tablename__ = "bookings"

    id = Column(Integer, primary_key=True, index=True)
    name = Column(String, nullable=False)
    email = Column(String, nullable=False)
    phone = Column(String, nullable=False)
    booking_type = Column(String, nullable=False)  # "tour" or "hiking"
    package = Column(String, nullable=False)
    date = Column(String, nullable=False)
    persons = Column(Integer, nullable=False)
    notes = Column(String, nullable=True)
    status = Column(String, default="Pending")
    timestamp = Column(DateTime(timezone=True), server_default=func.now())
