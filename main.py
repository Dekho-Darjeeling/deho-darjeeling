# app/main.py
from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from app.routers import booking
from app.database import Base, engine

# Create tables
Base.metadata.create_all(bind=engine)

app = FastAPI(title="Dekho Darjeeling Backend")

# CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # update for production
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Routers
app.include_router(booking.router, prefix="/api")
