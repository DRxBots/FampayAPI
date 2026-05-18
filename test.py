import telebot
import requests
from telebot.types import InlineKeyboardMarkup, InlineKeyboardButton

# Configuration
BOT_TOKEN = 'YOUR_TELEGRAM_BOT_TOKEN'
bot = telebot.TeleBot(BOT_TOKEN)

# FamPay API Details
MAIL_PREFIX = "your_email_prefix"  # your email without '@gmail.com'
APP_PASSWORD = "your_app_password"
UPI_ID = "iybhathstalker@fam"
MERCHANT_NAME = "DRX Net"

# Temporary memory to store user payment states
user_payments = {}

@bot.message_handler(commands=['start', 'pay'])
def initiate_payment(message):
    msg = bot.send_message(message.chat.id, "💰 Enter the amount you want to pay:")
    bot.register_next_step_handler(msg, process_amount)

def process_amount(message):
    try:
        amount = float(message.text)
        chat_id = message.chat.id
        user_payments[chat_id] = {'amount': amount}

        # Request QR from API
        qr_api_url = f"https://subdict.qzz.io/genqr?upi={UPI_ID}&amount={amount}&name={MERCHANT_NAME}"
        response = requests.get(qr_api_url).json()

        if response.get("status") == "success":
            img_url = response.get("image_url")
            
            # Create inline verification buttons
            markup = InlineKeyboardMarkup()
            markup.add(InlineKeyboardButton("✅ Verify via UTR", callback_data="verify_utr"))
            markup.add(InlineKeyboardButton("🔑 Verify via TXN ID", callback_data="verify_txn"))

            bot.send_photo(
                chat_id, 
                img_url, 
                caption=f"━━━━━━━━━━━━━━━━━━\n✨ *Pay Amount:* ₹{amount}\n━━━━━━━━━━━━━━━━━━\n\nScan the QR code above and select a verification method below once done.", 
                parse_mode="Markdown",
                reply_markup=markup
            )
        else:
            bot.send_message(chat_id, "❌ Failed to generate QR code. Try again later.")
    except ValueError:
        bot.send_message(message.chat.id, "⚠️ Invalid amount. Please enter a valid number.")

@bot.callback_query_handler(func=lambda call: call.data in ["verify_utr", "verify_txn"])
def prompt_verification_input(call):
    chat_id = call.message.chat.id
    method = call.data
    
    if chat_id not in user_payments:
        bot.send_message(chat_id, "❌ Session expired. Please start over using /pay.")
        return

    user_payments[chat_id]['method'] = method
    input_type = "UTR Number" if method == "verify_utr" else "Transaction ID (TXN ID)"
    
    msg = bot.send_message(chat_id, f"Please send your **{input_type}**:")
    bot.register_next_step_handler(msg, verify_payment)

def verify_payment(message):
    chat_id = message.chat.id
    user_input = message.text.strip()
    payment_info = user_payments.get(chat_id)

    if not payment_info:
        bot.send_message(chat_id, "❌ Session expired. Please restart.")
        return

    amount = payment_info['amount']
    method = payment_info['method']

    bot.send_message(chat_id, "⏳ Checking payment status... Please wait.")

    # Select endpoint based on user choice
    if method == "verify_utr":
        check_url = f"https://subdict.qzz.io/check?mail={MAIL_PREFIX}@gmail&apppass={APP_PASSWORD}&utr={user_input}&amount={amount}"
    else:
        check_url = f"https://subdict.qzz.io/check?mail={MAIL_PREFIX}@gmail&apppass={APP_PASSWORD}&txnid={user_input}&amount={amount}"

    try:
        res = requests.get(check_url).json()
        if res.get("status") == "found":
            bot.send_message(
                chat_id, 
                f"✅ *Payment Verified Successfully!*\n\n👤 *Sender:* {res.get('sender_name')}\n💵 *Amount:* {res.get('amount')}\n🆔 *TXN ID:* {res.get('transaction_id')}",
                parse_mode="Markdown"
            )
            del user_payments[chat_id]  # Clear session on success
        else:
            bot.send_message(chat_id, "❌ Payment not found. Check your input/amount and try verifying again.")
    except Exception:
        bot.send_message(chat_id, "⚙️ Verification server error. Try again shortly.")

bot.polling(none_stop=True)
