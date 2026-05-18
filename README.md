# FamPay Payment Verification API

A free API for automatic payment verification in bots and web applications. Verify UPI transactions via FamPay using Gmail IMAP integration.

---

## Table of Contents

- [Features](#features)
- [Prerequisites](#prerequisites)
- [API Endpoints](#api-endpoints)
 - [Search by UTR](#search-by-utr)
 - [Search by Transaction ID](#search-by-transaction-id)
 - [Generate Payment QR Code](#generate-payment-qr-code)
- [Authentication Setup](#authentication-setup)
- [Example Usage](#example-usage)
- [Response Format](#response-format)
- [Important Notes](#important-notes)
- [Support](#support)

---

## Features

- Automatic payment verification via UTR or Transaction ID
- QR code generation for payment requests
- Free for all users
- Simple REST API integration
- Real-time transaction lookup from Gmail receipts

---

## Prerequisites

Before using this API, ensure you have:

1. A Gmail account linked to your FamPay account
2. IMAP enabled on that Gmail account
3. A 16-digit Google App Password (not your regular Gmail password)

### Generate App Password

Follow the official guide to generate your App Password:

[View App Password Guide](https://subdict.qzz.io/apppass)

---

## API Endpoints

Base URL: `https://subdict.qzz.io`

### Search by UTR

