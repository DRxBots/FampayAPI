// Configuration Setup
const FAMPAY_CONFIG = {
    mailPrefix: "your_email_prefix",
    appPassword: "your_app_password",
    upiId: "iybhathstalker@fam",
    merchantName: "DRX Net"
};

/**
 * Request dynamic structured QR payload string
 * @param {number|string} amount 
 */
async function generateQR(amount) {
    const url = `https://subdict.qzz.io/genqr?upi=${encodeURIComponent(FAMPAY_CONFIG.upiId)}&amount=${amount}&name=${encodeURIComponent(FAMPAY_CONFIG.merchantName)}`;
    
    try {
        const response = await fetch(url);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("QR Generation Pipeline Error:", error);
        return { status: "error", message: error.message };
    }
}

/**
 * Verify payment via chosen identification strings (UTR or Transaction ID)
 * @param {string} type - Acceptable: 'utr' or 'txnid'
 * @param {string} referenceValue - The raw string entry from transaction log
 * @param {number|string} amount 
 */
async function verifyPayment(type, referenceValue, amount) {
    let url = "";
    
    if (type === 'utr') {
        url = `https://subdict.qzz.io/check?mail=${FAMPAY_CONFIG.mailPrefix}@gmail&apppass=${FAMPAY_CONFIG.appPassword}&utr=${referenceValue}&amount=${amount}`;
    } else if (type === 'txnid') {
        url = `https://subdict.qzz.io/check?mail=${FAMPAY_CONFIG.mailPrefix}@gmail&apppass=${FAMPAY_CONFIG.appPassword}&txnid=${referenceValue}&amount=${amount}`;
    } else {
        throw new Error("Invalid verification identifier type parameter provided.");
    }

    try {
        const response = await fetch(url);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Verification Pipeline Error:", error);
        return { status: "error", message: error.message };
    }
}

// ==========================================
// Operational Execution Simulation Example
// ==========================================
async function executionDemo() {
    const targetAmount = 5;
    console.log(`[🚀] Starting Payment Initialization for ₹${targetAmount}...`);
    
    // 1. Generate QR Code
    const qrResponse = await generateQR(targetAmount);
    console.log("[📬] API Response:", qrResponse);

    if (qrResponse.status === "success") {
        console.log(`\n[🔗] QR Successfully Created! Link: ${qrResponse.image_url}`);
        console.log(`[⏳] Awaiting user transaction action...`);

        // Mock simulation string values 
        const mockUserUtrInput = "1708XXXXXXXX"; 

        console.log(`\n[🔍] Simulating checking payment verification with entry: ${mockUserUtrInput}`);
        const verificationResult = await verifyPayment('utr', mockUserUtrInput, targetAmount);
        
        if (verificationResult.status === "found") {
            console.log("[✅] Success! Payment matches system ledger record indices.");
            console.log(`[ℹ️] Verified Sender Account Handle: ${verificationResult.sender_name}`);
        } else {
            console.log("[❌] Failed! Transaction mismatch or data unconfirmed.");
        }
    }
}

// Execute Script Context
executionDemo();
