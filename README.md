<p align="center">
  <img src="https://files.catbox.moe/040fog.png" alt="FamPay API Banner" width="100%" />
</p>

<h1 align="center">FamPay API</h1>

<p align="center">
  Automatic Payment Verification for Bots & Websites
</p>

<p align="center">
  <a href="https://t.me/subdict">
    <img src="https://img.shields.io/badge/Support-%40subdict-0088cc?style=flat-square&logo=telegram" alt="Support" />
  </a>
  <a href="https://t.me/istakrr/23">
    <img src="https://img.shields.io/badge/API%20Guide-Telegram-0088cc?style=flat-square&logo=telegram" alt="API Guide" />
  </a>
  <a href="https://subdict.qzz.io">
    <img src="https://img.shields.io/badge/Main%20Website-subdict.qzz.io-blue?style=flat-square" alt="Website" />
  </a>
</p>

---

## Overview

FamPay API provides a simple and free solution for automatic payment verification in your bots and websites. Integrate UTR and Transaction ID lookups directly into your applications without any cost.

---

## Features

- **Automatic Payment Verification** - Verify FamPay transactions instantly via UTR or Transaction ID
- **QR Code Generation** - Generate payment QR codes programmatically
- **Free for Everyone** - No charges, no limits
- **Easy Integration** - Simple HTTP GET endpoints, works with any programming language
- **JSON Responses** - Clean, structured response data

---

## Quick Links

| Resource | URL |
|----------|-----|
| Main Website | [https://subdict.qzz.io](https://subdict.qzz.io) |
| API Documentation | [https://subdict.qzz.io/docs](https://subdict.qzz.io/docs) |
| Test Web | [https://subdict.qzz.io/test](https://subdict.qzz.io/test) |
| App Password Guide | [https://subdict.qzz.io/apppass](https://subdict.qzz.io/apppass) |
| API Guide (Telegram) | [https://t.me/istakrr/23](https://t.me/istakrr/23) |
| Support | [@subdict on Telegram](https://t.me/subdict) |

---

## API Endpoints

### 1. Verify Payment by UTR

Search for a transaction using the UTR (Unique Transaction Reference) number.

```
GET https://subdict.qzz.io/check?mail={mail}@gmail&apppass={app-password}&utr={UTR}&amount={AMOUNT}
```

**Parameters:**

| Parameter | Description | Example |
|-----------|-------------|---------|
| `mail` | Gmail address **without** `@gmail.com`. Must be linked with FamPay and have IMAP enabled. | `stkkr` |
| `apppass` | 16-digit Google App Password generated from your Google Account. | `hdkshudsoshs` |
| `utr` | The UTR number of the transaction to verify. | `123456789012` |
| `amount` | Transaction amount in INR. | `100` |

**Example Request:**

```
https://subdict.qzz.io/check?mail=stkkr@gmail&apppass=hdkshudsoshs&utr=123456789012&amount=100
```

### 2. Verify Payment by Transaction ID

Search for a transaction using the FamPay Transaction ID.

```
GET https://subdict.qzz.io/check?mail={mail}@gmail&apppass={app-password}&txnid={TXN-ID}&amount={AMOUNT}
```

**Parameters:**

| Parameter | Description | Example |
|-----------|-------------|---------|
| `mail` | Gmail address **without** `@gmail.com`. Must be linked with FamPay and have IMAP enabled. | `stkkr` |
| `apppass` | 16-digit Google App Password generated from your Google Account. | `hdkshudsoshs` |
| `txnid` | The FamPay Transaction ID. | `FMPIB5*******` |
| `amount` | Transaction amount in INR. | `100` |

**Example Request:**

```
https://subdict.qzz.io/check?mail=stkkr@gmail&apppass=hdkshudsoshs&txnid=FMPIB5*******&amount=100
```

### 3. Generate Payment QR Code

Generate a UPI QR code for receiving payments.

```
GET https://subdict.qzz.io/genqr?upi={UPI-ID}&amount={AMOUNT}&name={NAME}
```

**Parameters:**

| Parameter | Description | Example |
|-----------|-------------|---------|
| `upi` | Your FamPay UPI ID. | `iybhathstalker@fam` |
| `amount` | Payment amount in INR. | `5` |
| `name` | Display name on the QR code (URL-encoded). | `DRX%20Net` |

**Example Request:**

```
https://subdict.qzz.io/genqr?upi=iybhathstalker@fam&amount=5&name=DRX%20Net
```

---

## Response Format

### Payment Verification Response

```json
{
  "status": "found",
  "result": "Found",
  "utr": "1708*********",
  "amount": "Rs.1.0",
  "sender_name": "Stalker",
  "date_time": "04:14 PM IST, 16 May 2026",
  "transaction_id": "FMPIB5*******"
}
```

### QR Code Generation Response

```json
{
  "status": "success",
  "result": "Generated QR",
  "name": "DRX Net",
  "upi": "iybhathstalker@fam",
  "amount": "Rs.5",
  "image_url": "https://subdict.qzz.io/genqr/?upi=iybhathstalker%40fam&amount=5&name=DRX+Net&img=1",
  "time_of_generation": "2026-05-16 15:40:00 UTC"
}
```

---

## Setup Guide

### Step 1: Generate a Google App Password

1. Go to your [Google Account Security Settings](https://myaccount.google.com/security)
2. Enable **2-Step Verification** (required)
3. Navigate to **App Passwords**
4. Select **Mail** as the app and **Other** as the device
5. Generate and copy the 16-digit app password

> Full guide available at: [https://subdict.qzz.io/apppass](https://subdict.qzz.io/apppass)

### Step 2: Enable IMAP on Gmail

1. Open [Gmail Settings](https://mail.google.com/mail/u/0/#settings/fwdandpop)
2. Go to the **Forwarding and POP/IMAP** tab
3. In the **IMAP Access** section, select **Enable IMAP**
4. Save changes

### Step 3: Link Gmail with FamPay

Ensure the Gmail account you are using is linked with your FamPay account for transaction emails.

---

## Usage Examples

### Python

```python
import requests

mail = "yourname"           # without @gmail.com
app_pass = "your16digitpass"
utr = "123456789012"
amount = "100"

url = f"https://subdict.qzz.io/check?mail={mail}@gmail&apppass={app_pass}&utr={utr}&amount={amount}"

response = requests.get(url)
print(response.json())
```

### cURL

```bash
curl -X GET "https://subdict.qzz.io/check?mail=yourname@gmail&apppass=your16digitpass&utr=123456789012&amount=100"
```

### JavaScript (Fetch)

```javascript
const mail = "yourname";
const appPass = "your16digitpass";
const utr = "123456789012";
const amount = "100";

fetch(`https://subdict.qzz.io/check?mail=${mail}@gmail&apppass=${appPass}&utr=${utr}&amount=${amount}`)
  .then(response => response.json())
  .then(data => console.log(data));
```

---

## Important Notes

- **Store used UTRs in your database** to prevent duplicate verifications and replay attacks.
- The `mail` parameter should **not** include `@gmail.com`.
- Ensure IMAP is enabled on the Gmail account linked with FamPay.
- App Passwords are different from your regular Gmail password. You must generate one from your Google Account.

---

## Support

For help, bug reports, or feature requests, reach out on Telegram:

<a href="https://t.me/subdict">
  <img src="https://img.shields.io/badge/%40subdict-Support-0088cc?style=for-the-badge&logo=telegram&logoColor=white" alt="Telegram Support" />
</a>

---

## Developer

**SubDict**

<a href="https://t.me/subdict">
  <img src="https://img.shields.io/badge/Telegram-%40subdict-0088cc?style=flat-square&logo=telegram" alt="Developer" />
</a>

---

<p align="center">
  Made with care by <a href="https://t.me/subdict">SubDict</a>
</p>
