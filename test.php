<?php
// Configuration Constants
define('MAIL_PREFIX', 'your_email_prefix'); 
define('APP_PASSWORD', 'your_app_password');
define('UPI_ID', 'iybhathstalker@fam');
define('MERCHANT_NAME', 'DRX Net');

// Helper to make API curl requests
function makeRequest($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Handle AJAX actions from your front-end web UI
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'];
    $amount = $_GET['amount'] ?? 0;

    if ($action === 'generate') {
        $url = "https://subdict.qzz.io/genqr?upi=" . urlencode(UPI_ID) . "&amount=" . urlencode($amount) . "&name=" . urlencode(MERCHANT_NAME);
        echo json_encode(makeRequest($url));
        exit;
    }

    if ($action === 'verify') {
        $type = $_GET['type'] ?? 'utr'; // 'utr' or 'txnid'
        $value = $_GET['value'] ?? '';

        if ($type === 'utr') {
            $url = "https://subdict.qzz.io/check?mail=" . MAIL_PREFIX . "@gmail&apppass=" . APP_PASSWORD . "&utr=" . urlencode($value) . "&amount=" . urlencode($amount);
        } else {
            $url = "https://subdict.qzz.io/check?mail=" . MAIL_PREFIX . "@gmail&apppass=" . APP_PASSWORD . "&txnid=" . urlencode($value) . "&amount=" . urlencode($amount);
        }
        echo json_encode(makeRequest($url));
        exit;
    }
}
?>

<!-- Minimalistic Web UI Demonstration Embedded -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FamPay Web Gateway Checkout</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 40px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;}
        .hidden { display: none; }
        button { margin-top: 10px; width: 100%; padding: 10px; background: #5c6bc0; color: white; border: none; cursor: pointer; border-radius: 4px; }
        input, select { width: 100%; padding: 8px; margin: 5px 0; box-sizing: border-box; }
        img { max-width: 100%; margin-top: 15px; }
    </style>
</head>
<body>

    <h3>FamPay Sandbox Checkout</h3>
    
    <!-- Step 1: Input Amount -->
    <div id="step-1">
        <label>Enter Amount (₹):</label>
        <input type="number" id="amount" value="5">
        <button onclick="generatePaymentQR()">Generate dynamic QR</button>
    </div>

    <!-- Step 2: Show QR & Verification Panel -->
    <div id="step-2" class="hidden">
        <h4>Scan to Pay</h4>
        <div id="qr-container"></div>
        <hr>
        <label>Verify using:</label>
        <select id="verify-type">
            <option value="utr">UTR Number</option>
            <option value="txnid">Transaction ID (TXN ID)</option>
        </select>
        <input type="text" id="verify-value" placeholder="Enter Ref ID here">
        <button onclick="verifyWebPayment()">Verify Now</button>
    </div>

    <script>
        let currentAmount = 0;

        async function generatePaymentQR() {
            currentAmount = document.getElementById('amount').value;
            let response = await fetch(`test.php?action=generate&amount=${currentAmount}`);
            let data = await response.json();
            
            if(data.status === 'success') {
                document.getElementById('qr-container').innerHTML = `<img src="${data.image_url}" alt="QR Code">`;
                document.getElementById('step-1').classList.add('hidden');
                document.getElementById('step-2').classList.remove('hidden');
            } else {
                alert('Failed generating gateway parameters.');
            }
        }

        async function verifyWebPayment() {
            let type = document.getElementById('verify-type').value;
            let value = document.getElementById('verify-value').value;
            
            let response = await fetch(`test.php?action=verify&type=${type}&value=${value}&amount=${currentAmount}`);
            let data = await response.json();
            
            if(data.status === 'found') {
                alert(`🎉 Order successful! Paid by: ${data.sender_name}`);
                location.reload();
            } else {
                alert('❌ Payment validation rejected. Double check input ID.');
            }
        }
    </script>
</body>
</html>
